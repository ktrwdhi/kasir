<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\outlet;
use App\Models\products;
use Illuminate\Http\Request;

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

        $products = $outlet->products()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->category, function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products', compact(
            'categories',
            'outlet',
            'products'
        ));

        // return $products;
    }
}
