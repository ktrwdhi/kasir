<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\outlet;
use App\Models\productDetail;
use App\Models\products;
use App\Models\productVarian;
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
            'varian' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request) {

            $image = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image')
                    ->store('products', 'public');
            }

            $product = products::firstOrCreate(
                [
                    'name' => $request->name,
                ],
                [
                    'category_id' => $request->category_id,
                    'image' => $image,
                ]
            );

            $existingVarian = productVarian::where('product_id', $product->id)
                ->where('name', $request->varian)
                ->first();
            
            if(!$existingVarian){
                if($request->varian !== null && $request->varian !== ''){
                    $existingVarian = productVarian::create([
                        'product_id' => $product->id,
                        'name' => $request->varian,
                    ]);
                } else {
                    $existingVarian = null;
                }
            }
            
                
            productDetail::create([
                'product_id' => $product->id,
                'product_varian_id' => $existingVarian?->id,
                'outlet_id' => $request->outlet_id,
                'stock' => $request->stock,
                'price' => $request->price,
            ]);
                
        });

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }
    
    
    public function update(Request $request, $id)
    {
        $product = products::findOrFail($id);
        $productDetail = productDetail::where('product_id', $id)->first();

        if ($request->submit == 'updateStock') {

            $request->validate([
                'stock' => 'required|integer|min:0'
            ]);

            $productDetail->update([
                'stock' => $request->stock
            ]);

        } elseif ($request->submit == 'updateImage') {

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

        } elseif ($request->submit == 'updateProduct') {

            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
            ]);

            $product->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
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

    public function attach(Request $request)
    {
        $validate = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak ditemukan.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',

            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
        ]);

        $outletId = $request->outlet_id;
        $productId = $request->product_id;

        $cekProduct = $this->cekProductByOutlet($productId, $outletId);

        if ($cekProduct) {
            return back()->with('error', 'Produk sudah tersedia di outlet ini.');
        }

        productDetail::create([
            'product_id' => $productId,
            'outlet_id' => $outletId,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke outlet.');
    }

    public function storeVarian(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'name' => 'required|string|max:15',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak ditemukan.',

            'name.required' => 'Nama varian wajib diisi.',
            'name.string' => 'Nama varian harus berupa teks.',
            'name.max' => 'Nama varian tidak boleh lebih dari 15 karakter.',
        ]);

        // Ambil produk
        $cek = products::findOrFail($request->product_id);

        // Ambil nama produk dan input varian
        $productName = trim($cek->name);
        $name = trim($request->name);

        // Hapus unsur nama produk dari input
        $name = trim(str_ireplace($productName, '', $name));

        // Pastikan nama varian tidak kosong
        if ($name === '') {
            return back()->with('error', 'Nama varian tidak boleh kosong.');
        }

        // Cek varian yang sudah ada
        $existingVarian = productVarian::where('product_id', $request->product_id)
            ->where('name', $name)
            ->first();

        if ($existingVarian) {
            return back()->with('error', 'Varian sudah ada untuk produk ini.');
        }

        // Simpan varian
        $varian = productVarian::create([
            'product_id' => $request->product_id,
            'name' => $name,
        ]);

        if($varian){
            productDetail::create([
                'product_id' => $request->product_id,
                'product_varian_id' => $varian->id,
                'outlet_id' => $request->outlet_id,
                'stock' => $request->stock ?? 0, 
                'price' => $request->price ?? 0, 
            ]);
        }

        return back()->with('success', 'Varian berhasil ditambahkan.');
    }

    private function cekProductByOutlet($productId, $outletId)
    {
        return productDetail::where('product_id', $productId)
            ->where('outlet_id', $outletId)
            ->exists();
    }
}
