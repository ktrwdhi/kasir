<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product; // Disarankan singular: Product
use App\Models\products;
use App\Services\QrisService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function qris(Request $request, QrisService $qrisService)
    {
        // 1. Validasi input menggunakan Validator Laravel
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $items = $validated['items'];

        // 2. Ambil semua ID produk & Query 1x ke DB (Mencegah N+1 Query)
        $productIds = collect($items)->pluck('id');
        $products = products::whereIn('id', $productIds)->get()->keyBy('id');

        // 3. Hitung total harga
        $total = 0;
        foreach ($items as $item) {
            $product = $products->get($item['id']);
            $total += $product->price * $item['qty'];
        }

        // 4. Generate Dynamic QRIS
        $dynamicQris = $qrisService->generate(
            config('payment.qris'),
            $total
        );

        return response()->json([
            'success' => true,
            'amount' => $total,
            'qris' => $dynamicQris,
        ]);
    }

    
}