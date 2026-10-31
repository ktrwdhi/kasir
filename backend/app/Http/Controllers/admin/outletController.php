<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\outlet;
use App\Models\productDetail;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class outletController extends Controller
{
    public function index()
    {
        $outlets = outlet::latest()->get();
        return view('admin.outlet', compact('outlets'));
    }
    public function show(Request $request,$uuid){
        
        // $outlet = products::with(['outlet', 'category'])
        //     ->whereHas('outlet', function ($query) use ($uuid) {
        //         $query->where('uuid', $uuid);
        //     })
        //     ->when($request->search, function ($query) use ($request) {
        //         $query->where('name', 'like', '%' . $request->search . '%');
        //     })
        //     ->when($request->category, function ($query) use ($request) {
        //         $query->where('category_id', $request->category);
        //     })
        //     ->latest()
        //     ->paginate(10)
        //     ->withQueryString();
        
        $categories = category::all();

        $outlet = outlet::where('uuid', $uuid)->firstOrFail();

        $products = $outlet->productDetails()
            ->with('product.category')
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('category_id', $request->category);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(function ($detail) {
                $product = $detail->product;

                $product->stock = $detail->stock;

                return $product;
            });

        $availableProducts = products::with('category')
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
            'availableProducts'
        ));

        return $products;
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

        return view('admin.outlet', compact(
            'product',
            'outlets',
            'categories'
        ));
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
