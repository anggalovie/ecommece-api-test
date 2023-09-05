<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase; // Untuk mereset database setelah setiap pengujian

    /** @test */
    public function it_can_create_an_order()
    {
        // Buat data pelanggan, produk, dan metode pembayaran
        $customer = Customer::create(['customer_name' => 'Customer A']);
        $customer_address = CustomerAddress::create(['customer_id' => $customer->id, 'address' => 'Jakarta Selatan']);
        $product = Product::create(['name' => 'Pasta Gigi',  'price' => 5000]);
        $paymentMethod = PaymentMethod::create(['name' => 'Transfer Bank', 'is_active' => true]);

        $orderData = [
            'customer_id' => $customer->id,
            'payment_method_id' => $paymentMethod->id,
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ];

        // Kirim permintaan POST ke endpoint store
        $response = $this->postJson('/api/order', $orderData);

        // Periksa respons yang diharapkan
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Pesanan berhasil dibuat']);

        // Periksa apakah pesanan, order product, dan produk telah dibuat dalam database
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'payment_method_id' => $paymentMethod->id,
        ]);

        $this->assertDatabaseHas('order_products', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
