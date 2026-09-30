<?php

namespace App\Services;

use App\Exceptions\StockUnavailableException;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId): Order {
            $lockedProducts = collect($data['items'])
                ->sortBy('product_id')
                ->mapWithKeys(fn (array $item) => [$item['product_id'] => Product::query()->lockForUpdate()->findOrFail($item['product_id'])]);

            $orderItems = [];
            $total = 0;
            foreach ($data['items'] as $item) {
                $product = $lockedProducts[$item['product_id']];
                if ($product->stock_quantity < $item['quantity']) {
                    throw new StockUnavailableException("{$product->name} no longer has enough stock.");
                }
                $lineTotal = $product->price * $item['quantity'];
                $total += $lineTotal;
                $orderItems[] = [$product, $item['quantity'], $product->price, $lineTotal];
            }

            $order = Order::create([
                'order_number' => 'ORD-'.now()->format('ymdHis').random_int(10, 99),
                'customer_id' => $data['customer_id'],
                'user_id' => $userId,
                'total' => $total,
                'status' => 'Paid',
                'ordered_at' => now(),
            ]);

            foreach ($orderItems as [$product, $quantity, $unitPrice, $lineTotal]) {
                $order->items()->create(['product_id' => $product->id, 'quantity' => $quantity, 'unit_price' => $unitPrice, 'line_total' => $lineTotal]);
                $product->decrement('stock_quantity', $quantity);
                $product->update(['status' => $product->stock_quantity > 0 ? 'Active' : 'Out of stock']);
            }
            return $order->load(['customer', 'items.product']);
        });
    }

    public function cancel(Order $order): Order
    {
        return DB::transaction(function () use ($order): Order {
            $order = Order::query()->lockForUpdate()->with('items')->findOrFail($order->id);
            if ($order->status === 'Cancelled') return $order->load(['customer', 'items.product']);
            foreach ($order->items as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);
                $product->increment('stock_quantity', $item->quantity);
                $product->update(['status' => 'Active']);
            }
            $order->update(['status' => 'Cancelled']);
            return $order->load(['customer', 'items.product']);
        });
    }
}