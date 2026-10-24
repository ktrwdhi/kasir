<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\products;
use Illuminate\Http\Request;

class productController extends Controller
{
    public function index(Request $request)
    {
        $categories = category::all();

        $products = products::with('category')
            ->when($request->search, function ($query) use ($request) {
                $query->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                );
            })
            ->when($request->category, function ($query) use ($request) {
                $query->where(
                    'category_id',
                    $request->category
                );
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products', compact(
            'products',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('products', 'public');
        }

        products::create([
            'outlet_id'=>$request->outlet_id,
            'category_id'=>$request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $product = products::findOrFail($id);

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = products::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = products::findOrFail($id);

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
