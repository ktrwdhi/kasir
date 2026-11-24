<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\outlet;
use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class transactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isOwner = ($user->role ?? null) === 'owner';

        if (! $isOwner && ! $user->outlet_id) {
            abort(403, 'Akun admin belum terhubung ke outlet mana pun.');
        }

        $baseQuery = sales::query()
            // Filter outlet untuk admin
            ->when(! $isOwner, function ($query) use ($user) {
                $query->where('outlet_id', $user->outlet_id);
            })
            // Filter outlet pilihan owner
            ->when(
                $isOwner && $request->filled('outlet_id'),
                function ($query) use ($request) {
                    $query->where('outlet_id', $request->outlet_id);
                }
            )
            // Cari nomor invoice
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('invoice', 'like', '%' . $request->search . '%');
            })
            // Filter metode pembayaran (sesuaikan 'payment_method' / 'payment' dengan kolom di DB)
            ->when($request->filled('payment'), function ($query) use ($request) {
                $query->where('payment_method', $request->payment);
            })
            // Filter tanggal transaksi
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            });

        $totalTransaksi = (clone $baseQuery)->count();
        $totalPendapatan = (clone $baseQuery)->sum('total');
        $totalTransaksi  = (clone $baseQuery)->count();
        $totalPendapatan = (clone $baseQuery)->sum('total');

        // Sesuaikan 'payment_method' jika nama kolom di tabel sales adalah 'payment'
        $totalCash = (clone $baseQuery)
            ->whereRaw('LOWER(payment) = ?', ['cash'])
            ->sum('total');

        $totalQris = (clone $baseQuery)
            ->whereRaw('LOWER(payment) = ?', ['qris'])
            ->sum('total');

        $transactions = (clone $baseQuery)
            ->with(['outlet', 'user'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $outlets = $isOwner ? outlet::orderBy('name')->get() : collect();

        return view('admin.transaction', compact(
            'transactions',
            'totalTransaksi',
            'totalPendapatan',
            'totalCash',
            'totalQris',
            'outlets'
        ));
    }
}