<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sale_detail;
use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class transactionController extends Controller
{
    public function transaction(Request $request){
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
            'payment' => 'required|string',
            'cash' => 'required|numeric|min:0',
            'change' => 'required|numeric|min:0',
        ]);

        try {

            $sale = DB::transaction(function () use ($request) {

                $total = 0;
                $user = $request->user();
                $outlet_id = $user->outlet_id;

                foreach ($request->items as $item) {

                    $product = products::find($item['id']);

                    if (!$product) {
                        throw new \Exception(
                            "Produk dengan ID {$item['id']} tidak ditemukan."
                        );
                    }

                    if ($product->stock < $item['qty']) {
                        throw new \Exception(
                            "Stok {$product->name} tidak mencukupi."
                        );
                    }

                    $total += $product->price * $item['qty'];
                }

                $invoice = 'INV-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -4));

                $cash = $request->cash;

                if($request->payment == "qris"){
                    $cash = $total;
                }
                $sale = sales::create([
                    'user_id' => $user->id,
                    'outlet_id' => $outlet_id,
                    'invoice' => $invoice,
                    'total' => $total,
                    'payment' => $request->payment,
                    'cash' => $cash,
                    'change' => $request->change
                ]);

                // =========================
                // SIMPAN DETAIL + KURANGI STOK
                // =========================
                foreach ($request->items as $item) {

                    $product = products::find($item['id']);

                    sale_detail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $product->price * $item['qty'],
                    ]);

                    // Kurangi stok
                    $product->decrement('stock', $item['qty']);
                    
                }

                return $sale;
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'data' => $sale,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
