<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\productDetail;
use App\Models\products;
use App\Models\sale_detail;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index()
    {
        $totalProducts = products::count();

        $totalStock = productDetail::sum('stock');

        $lowStock = productDetail::where('stock', '<=', 5)->count();
        $transactionCount = sale_detail::distinct('product_id')->count('product_id');

        $recentSales = sale_detail::with([
            'product',
            'sale.outlet'
        ])
        ->latest()
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStock',
            'lowStock',
            'recentSales',
            'transactionCount'
        ));
    }
}
