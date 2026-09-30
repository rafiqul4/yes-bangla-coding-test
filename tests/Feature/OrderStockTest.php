<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_uses_database_price_and_reduces_stock(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        $customer = Customer::create(['name' => 'Test Customer', 'phone' => '555', 'email' => 'test@example.com', 'address' => 'Test address']);
        $product = Product::create(['name' => 'Test Product', 'sku' => 'TEST-1', 'category' => 'Test', 'price' => 10, 'stock_quantity' => 5, 'status' => 'Active']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/orders', ['customer_id' => $customer->id, 'items' => [['product_id' => $product->id, 'quantity' => 2]], 'total' => 0]);

        $response->assertCreated()->assertJsonPath('data.total', '20.00');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 3]);
    }

    public function test_order_returns_conflict_when_stock_is_insufficient(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        $customer = Customer::create(['name' => 'Test Customer', 'phone' => '555', 'email' => 'test@example.com', 'address' => 'Test address']);
        $product = Product::create(['name' => 'Limited Product', 'sku' => 'LIMIT-1', 'category' => 'Test', 'price' => 10, 'stock_quantity' => 1, 'status' => 'Active']);
        Sanctum::actingAs($user);

        $this->postJson('/api/orders', ['customer_id' => $customer->id, 'items' => [['product_id' => $product->id, 'quantity' => 2]]])->assertStatus(409)->assertJsonPath('code', 'stock_unavailable');
    }
}