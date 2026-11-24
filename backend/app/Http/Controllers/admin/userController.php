<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\userMail;
use App\Models\outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use illuminate\Support\Str;

class userController extends Controller
{
    public function index(Request $request)
    {
        $userLogin = Auth::user();
        $isAdmin = $userLogin->role === 'admin';

        $baseQuery = User::with('outlet')
            ->where('role', '!=', 'owner')
            ->when($isAdmin, function ($query) use ($userLogin) {
                $query->where('role', 'kasir')
                    ->where('outlet_id', $userLogin->outlet_id);
            })
            ->when(
                !$isAdmin && $request->filled('outlet_id'),
                function ($query) use ($request) {
                    $query->where('outlet_id', $request->outlet_id);
                }
            )
            ->when(
                !$isAdmin && $request->filled('role'),
                function ($query) use ($request) {
                    $query->where('role', $request->role);
                }
            )
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
                });
            });

        $totalUsers = (clone $baseQuery)->count();

        $users = $baseQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $outlets = $isAdmin ? collect() : Outlet::all();

        return view('admin.user', compact(
            'users',
            'totalUsers',
            'outlets'
        ));
    }
    
    public function store(Request $request)
    {
        $authUser = Auth::user();
        $isOwner = $authUser->role === 'owner';

        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
            'role' => $isOwner ? 'required|in:admin,kasir' : 'nullable',
            'outlet_id' => $isOwner ? 'required|exists:outlets,id' : 'nullable',
        ]);

        $role = $isOwner ? $validated['role'] : 'kasir';
        $outletId = $isOwner ? $validated['outlet_id'] : $authUser->outlet_id;

        $uuid = (string) Str::uuid();
        $username = Str::random(15);
        $password = Str::random(10);

        try {
            Mail::to($validated['email'])->send(
                new UserMail(
                    $username,
                    $password,
                    $uuid
                )
            );

            DB::transaction(function () use (
                $uuid,
                $username,
                $validated,
                $password,
                $role,
                $outletId
            ) {
                User::create([
                    'uuid' => $uuid,
                    'username' => $username,
                    'email' => $validated['email'],
                    'password' => Hash::make($password),
                    'role' => $role,
                    'outlet_id' => $outletId,
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengirim email aktivasi. Data user belum disimpan: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User berhasil ditambahkan dan username serta password telah dikirim ke email.'
            );
    }

    public function edit($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        return view('admin.userEdit', compact('user'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'sex' => 'required|in:L,P',
            'username' => 'nullable|string|max:255|unique:users,username,' . $request->uuid . ',uuid',
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::where('uuid', $request->uuid)->firstOrFail();
        $uuid = Str::uuid();

        $data = [
            'uuid' => $uuid,
            'name' => $request->name,
            'phone' => $request->phone,
            'sex' => $request->sex,
        ];

        if ($request->filled('username')) {
            $data['username'] = $request->username;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()
            ->with('success', 'Profil user berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $currentUser = Auth::user();

        // Tidak boleh menghapus akun sendiri
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        // Akun owner tidak boleh dihapus
        if ($user->role === 'owner') {
            abort(403, 'Akun owner tidak dapat dihapus.');
        }

        // Jika yang login adalah owner
        if ($currentUser->role === 'owner') {

            $user->delete();

            return back()->with('success', 'User berhasil dihapus.');

        }

        // Jika yang login adalah admin
        if ($currentUser->role === 'admin') {

            // Admin hanya boleh menghapus kasir di outlet sendiri
            if (
                $user->role !== 'kasir' ||
                $user->outlet_id !== $currentUser->outlet_id
            ) {
                abort(403, 'Kamu tidak memiliki akses menghapus user ini.');
            }

            $user->delete();

            return back()->with('success', 'User berhasil dihapus.');
        }

        abort(403, 'Kamu tidak memiliki akses untuk menghapus user.');
    }
}
