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

        $transactions = Sales::with(['outlet', 'user'])
            // Filter outlet untuk admin
            ->when($user->role === 'admin', function ($query) use ($user) {
                $query->where('outlet_id', $user->outlet_id);
            })

            // Filter outlet pilihan owner
            ->when(
                $user->role === 'owner' && $request->filled('outlet_id'),
                function ($query) use ($request) {
                    $query->where('outlet_id', $request->outlet_id);
                }
            )

            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('invoice', 'like', '%' . $request->search . '%');
            })

            ->when($request->filled('payment'), function ($query) use ($request) {
                $query->where('payment_method', $request->payment);
            })

            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalTransaksi = Sales::when(
            $user->role === 'admin',
            function ($query) use ($user) {
                $query->where('outlet_id', $user->outlet_id);
            }
        )
        ->when(
            $user->role === 'owner' && $request->filled('outlet_id'),
            function ($query) use ($request) {
                $query->where('outlet_id', $request->outlet_id);
            }
        )
        ->count();

        $totalPendapatan = Sales::when(
            $user->role === 'admin',
            function ($query) use ($user) {
                $query->where('outlet_id', $user->outlet_id);
            }
        )
        ->when(
            $user->role === 'owner' && $request->filled('outlet_id'),
            function ($query) use ($request) {
                $query->where('outlet_id', $request->outlet_id);
            }
        )
        ->sum('total');

        // Data outlet untuk dropdown owner
        $outlets = outlet::all();

        return view('admin.transaction', compact(
            'transactions',
            'totalTransaksi',
            'totalPendapatan',
            'outlets'
        ));
    }

}
