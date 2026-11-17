<?php

namespace App\Http\Controllers;

use App\Models\productDetail;
use App\Models\products;
use App\Models\sale_detail;
use App\Models\sales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class transactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = sales::with(['user', 'outlet'])
            ->latest();

        // Admin hanya melihat transaksi outlet sendiri
        if ($user->role === 'admin') {
            $query->where('outlet_id', $user->outlet_id);
        }

        // Owner dapat melihat seluruh transaksi
        $transactions = $query->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }
    
public function transaction(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.product_detail_id' => 'required|integer|min:1',
        'items.*.qty' => 'required|integer|min:1',
        'payment' => 'required|in:cash,qris',
        'cash' => 'required|numeric|min:0',
        'change' => 'nullable|numeric|min:0',
    ]);

    try {
        $sale = DB::transaction(function () use ($request, $validated) {

            $total = 0;
            $user = $request->user();
            $outletId = $user->outlet_id;

            if (!$outletId) {
                throw new \Exception(
                    'User belum terhubung dengan outlet.'
                );
            }

            $itemsData = [];

            // CEK DETAIL PRODUK DAN STOK
            foreach ($validated['items'] as $item) {

                // Cari detail produk berdasarkan ID dan outlet
                $detail = productDetail::with('product')
                    ->where('id', $item['product_detail_id'])
                    ->where('outlet_id', $outletId)
                    ->lockForUpdate()
                    ->first();

                if (!$detail) {
                    throw new \Exception(
                        "Detail produk dengan ID {$item['product_detail_id']} tidak ditemukan di outlet."
                    );
                }

                $product = $detail->product;

                if (!$product) {
                    throw new \Exception(
                        'Data produk tidak ditemukan.'
                    );
                }

                // CEK STOK
                if ($detail->stock < $item['qty']) {
                    throw new \Exception(
                        "Stok {$product->name} tidak mencukupi."
                    );
                }

                // AMBIL HARGA DARI PRODUCT DETAILS
                $price = $detail->price;

                $subtotal = $price * $item['qty'];

                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_varian_id' => $detail->product_varian_id,
                    'detail' => $detail,
                    'quantity' => $item['qty'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            // CEK PEMBAYARAN
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

            // GENERATE INVOICE
            $invoice = 'INV-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(substr(uniqid(), -4));

            // SIMPAN TRANSAKSI
            $sale = sales::create([
                'user_id' => $user->id,
                'outlet_id' => $outletId,
                'invoice' => $invoice,
                'total' => $total,
                'payment' => $request->payment,
                'cash' => $cash,
                'change' => $change,
            ]);

            // SIMPAN DETAIL TRANSAKSI DAN KURANGI STOK
            foreach ($itemsData as $data) {

                sale_detail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $data['product_id'],
                    'product_varian_id' => $data['product_varian_id'],
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                    'subtotal' => $data['subtotal'],
                ]);

                $data['detail']->decrement(
                    'stock',
                    $data['quantity']
                );
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
