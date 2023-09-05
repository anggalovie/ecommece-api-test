<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required',
            'payment_method_id' => 'required',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            // Membuat Order
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'customer_address_id' => Customer::with('customer_address:id,address')->find(1)->customer_address->id,
                'payment_method_id' => $request->payment_method_id,
                'order_date' => date(now())
            ]);
            // Membuat Order Product
            foreach ($request->products as $productData) {
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity']
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Pesanan gagal dibuat ' . $th . ''], 200);
        }

        return response()->json(['message' => 'Pesanan berhasil dibuat'], 201);
    }

    public function getOrder($id)
    {
        $order = Order::with('orderProducts', 'paymentMethod')->find($id);
        if ($order) {
            return response()->json([
                'message' => 'Data ditemukan!',
                'data' => $order
            ], 200);
        }
        return response()->json(['message' => 'Data tidak ditemukan!'], 200);
    }
}
