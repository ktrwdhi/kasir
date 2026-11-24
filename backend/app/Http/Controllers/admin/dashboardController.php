<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\productDetail;
use App\Models\products;
use App\Models\sale_detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    public function index()
    {
        $totalProducts = products::count();

        $totalStock = productDetail::sum('stock');

        $lowStockThreshold = 5; // Sesuai aturan awal kamu (stock <= 5)

        $lowStock = productDetail::where('stock', '>', 0)
            ->where('stock', '<=', $lowStockThreshold)
            ->count();

        $transactionCount = sale_detail::distinct('product_id')->count('product_id');

        $recentSales = sale_detail::with([
            'product',
            'sale.outlet'
        ])
        ->latest()
        ->take(5)
        ->get();

        $transactionsByOutlet = DB::table('sales')
            ->join('outlets', 'sales.outlet_id', '=', 'outlets.id')
            ->select(
                'outlets.name as outlet_name',
                DB::raw('COUNT(sales.id) as total_transactions')
            )
            ->groupBy('outlets.id', 'outlets.name')
            ->orderByDesc('total_transactions')
            ->get();

        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        // Sesuaikan nama tabel jika di DB bernama sale_details
        $saleDetailTable = (new sale_detail())->getTable();
        $productTable    = (new products())->getTable();

        $rawDailySales = sale_detail::query()
            ->select(
                DB::raw('DATE(created_at) as raw_date'),
                DB::raw('SUM(price * quantity) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('raw_date', 'asc')
            ->pluck('total', 'raw_date');

        // Pastikan hari yang tidak ada transaksi tetap muncul dengan nilai 0
        $dailySales = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $key = $date->format('Y-m-d');
            $dailySales->push([
                'date'  => $date->translatedFormat('d M'),
                'total' => (int) ($rawDailySales[$key] ?? 0),
            ]);
        }

        $topProducts = sale_detail::query()
            ->join($productTable, "{$saleDetailTable}.product_id", '=', "{$productTable}.id")
            ->select(
                "{$productTable}.name as product_name",
                DB::raw("SUM({$saleDetailTable}.quantity) as total_qty")
            )
            ->groupBy("{$productTable}.id", "{$productTable}.name")
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $outOfStockCount = productDetail::where('stock', '<=', 0)->count();
        $safeStockCount  = productDetail::where('stock', '>', $lowStockThreshold)->count();

        // Urutan: [Stok Aman, Stok Menipis, Stok Habis]
        $stockStatus = [$safeStockCount, $lowStock, $outOfStockCount];

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStock',
            'lowStock',
            'recentSales',
            'transactionCount',
            'transactionsByOutlet',
            'dailySales',
            'topProducts',
            'stockStatus'
        ));
    }
}