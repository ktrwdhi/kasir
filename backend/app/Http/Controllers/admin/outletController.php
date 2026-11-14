<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\outlet;
use App\Models\productDetail;
use App\Models\products;
use App\Models\productVarian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class outletController extends Controller
{
    public function index()
    {
        $outlets = outlet::latest()->get();
        return view('admin.outlet', compact('outlets'));
    }
    
public function show(Request $request, $uuid)
{
    $categories = category::all();

    $outlet = outlet::where('uuid', $uuid)->firstOrFail();

    // Ambil ID produk unik yang tersedia di outlet ini
    $productIds = productDetail::where('outlet_id', $outlet->id)
        ->select('product_id')
        ->distinct()
        ->pluck('product_id');

    // Tampilkan produk unik, bukan baris product_details
    $products = products::with('category')
        ->whereIn('id', $productIds)
        ->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })
        ->when($request->category, function ($query) use ($request) {
            $query->where('category_id', $request->category);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Ambil seluruh detail produk untuk produk yang tampil di halaman ini
    $detailsByProduct = productDetail::with('varians')
        ->where('outlet_id', $outlet->id)
        ->whereIn('product_id', $products->pluck('id'))
        ->get()
        ->groupBy('product_id');

    // Produk yang belum pernah ditambahkan ke outlet
    $availableProducts = products::with('category')
        ->whereNotIn('id', $productIds)
        ->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })
        ->when($request->category, function ($query) use ($request) {
            $query->where('category_id', $request->category);
        })
        ->paginate(10)
        ->withQueryString();

    return view('admin.products', compact(
        'categories',
        'outlet',
        'products',
        'detailsByProduct',
        'availableProducts'
    ));
}

    public function showOutletByProduct($id)
    {
        $product = products::findOrFail($id);
        $categories = category::all();

        // Cari semua outlet yang menjual produk tersebut
        $outlets = productDetail::with('outlet')
            ->where('product_id', $product->id)
            ->get()
            ->pluck('outlet')
            ->filter()
            ->unique('id')
            ->values();

        $product = products::findOrFail($id);

        $availableOutlets = outlet::whereDoesntHave('productDetails', function ($query) use ($id) {
            $query->where('product_id', $id);
        })->get();
        
        return view('admin.outlet', compact(
            'product',
            'outlets',
            'categories',
            'availableOutlets'
        ));
        

        // return $availableOutlets;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        outlet::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Outlet berhasil ditambahkan!');
    }
}
