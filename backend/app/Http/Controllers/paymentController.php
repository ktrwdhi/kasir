<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product; // Disarankan singular: Product
use App\Models\productDetail;
use App\Models\products;
use App\Services\QrisService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function qris(Request $request, QrisService $qrisService)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_detail_id' => 'required|integer|exists:product_details,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $outletId = $request->user()->outlet_id;

        $details = productDetail::with(['product', 'varians'])
            ->where('outlet_id', $outletId)
            ->whereIn(
                'id',
                collect($validated['items'])
                    ->pluck('product_detail_id')
            )
            ->get()
            ->keyBy('id');

        $total = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $detail = $details->get($item['product_detail_id']);

            if (!$detail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan di outlet ini.',
                ], 404);
            }

            if ($detail->stock < $item['qty']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk ' . $detail->product->name . ' tidak mencukupi.',
                ], 422);
            }

            $subtotal = $detail->price * $item['qty'];

            $total += $subtotal;

            $orderItems[] = [
                'product_detail_id' => $detail->id,
                'product_name' => $detail->product->name,
                'variant' => $detail->varians?->name,
                'price' => $detail->price,
                'qty' => $item['qty'],
                'subtotal' => $subtotal,
            ];
        }

        // Generate QRIS
        $dynamicQris = $qrisService->generate(
            config('payment.qris'),
            $total
        );

        return response()->json([
            'success' => true,
            'amount' => $total,
            'items' => $orderItems,
            'qris' => $dynamicQris,
        ]);
    }

    
}