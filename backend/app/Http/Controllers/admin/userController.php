<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\userMail;
use App\Models\outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use illuminate\Support\Str;

class userController extends Controller
{
    public function index(Request $request)
    {
        $userLogin = Auth::user();

        $users = User::with('outlet')
                ->where('role', '!=', 'owner')

            // Admin hanya melihat user dari outlet miliknya
            ->when($userLogin->role === 'admin', function ($query) use ($userLogin) {
                $query->where('outlet_id', $userLogin->outlet_id);
            })

            // Owner dapat memilih outlet
            ->when(
                $userLogin->role === 'owner' && $request->filled('outlet_id'),
                function ($query) use ($request) {
                    $query->where('outlet_id', $request->outlet_id);
                }
            )

            // Filter pencarian
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('username', 'like', '%' . $request->search . '%');
                });
            })

            // Filter role
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Total user mengikuti filter outlet
        $totalUsers = User::when(
            $userLogin->role === 'admin',
            function ($query) use ($userLogin) {
                $query->where('outlet_id', $userLogin->outlet_id);
            }
        )
        ->when(
            $userLogin->role === 'owner' && $request->filled('outlet_id'),
            function ($query) use ($request) {
                $query->where('outlet_id', $request->outlet_id);
            }
        )
        ->count();

        // Dropdown outlet untuk Owner
        $outlets = outlet::all();

        return view('admin.user', compact(
            'users',
            'totalUsers',
            'outlets'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:admin,kasir',
            'outlet_id' => Auth::user()->role === 'owner'
                ? 'required|exists:outlets,id'
                : 'nullable',
        ]);

        // Menentukan outlet
        if (Auth::user()->role === 'owner') {
            $outletId = $request->outlet_id;
        } else {
            $outletId = Auth::user()->outlet_id;
        }

        // Generate username dan password
        $username = Str::random(15);
        $password = Str::random(10);

        // Simpan user
        $user = User::create([
            'uuid' => Str::uuid(),
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'outlet_id' => $outletId,
        ]);

        // Kirim email
        Mail::to($user->email)->send(
            new UserMail(
                $user->username,
                $password,
                $user->uuid
            )
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan dan username serta password telah dikirim ke email.');

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

        $data = [
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
