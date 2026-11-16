<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sale_detail;
use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class transactionController extends Controller
{
    
    public function transaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|min:1',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.variant_id' => 'nullable|integer|min:1',
            'payment' => 'required|in:cash,qris',
            'cash' => 'required|numeric|min:0',
            'change' => 'nullable|numeric|min:0',
        ]);

        try {

            $sale = DB::transaction(function () use ($request) {

                $total = 0;
                $user = $request->user();
                $outlet_id = $user->outlet_id;

                if (!$outlet_id) {
                    throw new \Exception(
                        'User belum terhubung dengan outlet.'
                    );
                }

                $itemsData = [];

                // =========================
                // CEK PRODUK DAN STOK
                // =========================

                foreach ($request->items as $item) {

                    $product = products::find($item['id']);

                    if (!$product) {
                        throw new \Exception(
                            "Produk dengan ID {$item['id']} tidak ditemukan."
                        );
                    }

                    // Ambil detail produk berdasarkan outlet
                    $detailQuery = $product->productDetails()
                        ->where('outlet_id', $outlet_id)
                        ->lockForUpdate();

                    // Jika menggunakan varian
                    if (!empty($item['variant_id'])) {

                        $detailQuery->where(
                            'product_varian_id',
                            $item['variant_id']
                        );

                    } else {

                        // Produk non-varian
                        $detailQuery->whereNull(
                            'product_varian_id'
                        );

                    }

                    $detail = $detailQuery->first();

                    if (!$detail) {
                        throw new \Exception(
                            "Detail produk {$product->name} tidak ditemukan di outlet."
                        );
                    }

                    // Cek stok
                    if ($detail->stock < $item['qty']) {
                        throw new \Exception(
                            "Stok {$product->name} tidak mencukupi."
                        );
                    }

                    // Ambil harga dari database
                    $price = $detail->price;

                    // Hitung subtotal
                    $subtotal = $price * $item['qty'];

                    $total += $subtotal;

                    $itemsData[] = [
                        'product' => $product,
                        'detail' => $detail,
                        'quantity' => $item['qty'],
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }

                // =========================
                // CEK PEMBAYARAN
                // =========================

                $cash = $request->cash;
                $change = 0;

                if ($request->payment == 'cash') {

                    if ($cash < $total) {
                        throw new \Exception(
                            'Uang pembayaran tidak mencukupi.'
                        );
                    }

                    $change = $cash - $total;

                } elseif ($request->payment == 'qris') {

                    $cash = $total;
                    $change = 0;

                }

                // =========================
                // GENERATE INVOICE
                // =========================

                $invoice = 'INV-' .
                    now()->format('YmdHis') .
                    '-' .
                    strtoupper(substr(uniqid(), -4));

                // =========================
                // SIMPAN TRANSAKSI
                // =========================

                $sale = sales::create([
                    'user_id' => $user->id,
                    'outlet_id' => $outlet_id,
                    'invoice' => $invoice,
                    'total' => $total,
                    'payment' => $request->payment,
                    'cash' => $cash,
                    'change' => $change,
                ]);

                // =========================
                // SIMPAN DETAIL + KURANGI STOK
                // =========================

                foreach ($itemsData as $data) {

                    $product = $data['product'];
                    $detail = $data['detail'];

                    sale_detail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $data['quantity'],
                        'price' => $data['price'],
                        'subtotal' => $data['subtotal'],
                    ]);

                    // Kurangi stok detail produk outlet
                    $detail->decrement(
                        'stock',
                        $data['quantity']
                    );
                }

                return $sale;
            });

            // =========================
            // RESPONSE SUKSES
            // =========================

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
