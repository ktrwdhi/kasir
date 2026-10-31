<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\outlet;
use App\Models\productDetail;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class productController extends Controller
{
    public function index(Request $request)
    {
        $categories = category::all();

        $products = products::with('category')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->category, function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->latest('id')
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
            'category_id' => 'required|exists:categories,id',
            'outlet_id' => 'required|exists:outlets,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($request, $image) {

            $product = products::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'price' => $request->price,
                'image' => $image,
            ]);

            productDetail::create([
                'product_id' => $product->id,
                'outlet_id' => $request->outlet_id,
                'stock' => $request->stock,
            ]);
        });

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }
    
    
    public function update(Request $request, $id)
    {
        $product = products::findOrFail($id);

        if ($request->type == 'stock') {

            $request->validate([
                'stock' => 'required|integer|min:0'
            ]);

            $product->update([
                'stock' => $request->stock
            ]);

        } elseif ($request->type == 'image') {

            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            if (
                $product->image &&
                Storage::disk('public')->exists($product->image)
            ) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image')->store('products', 'public');

            $product->update([
                'image' => $image
            ]);

        } elseif ($request->type == 'data') {

            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0'
            ]);

            $product->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price
            ]);

        } else {

            abort(400, 'Jenis perubahan tidak valid.');

        }

        return back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = products::findOrFail($id);

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function attach(Request $request){
        $outletId = $request->uuid;
        $productId = $request->product_id;
        $cekProduct = $this->cekProductByOutlet($outletId,$productId);
        if($cekProduct){
            return "ada";
        }
        else{
            return "gaada";
        }
    }

    private function cekProductByOutlet($outletId,$productId)
    {
        $product = outlet::where('id', $outletId)
            ->whereHas('products', function ($query) use ($productId) {
                $query->where('id', $productId);
            })
            ->exists();

        return $product;
    }
}
