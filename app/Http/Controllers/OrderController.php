<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orders) {}
    public function index(Request $request): JsonResponse { $query = Order::with('customer')->when($request->search, fn ($q, $term) => $q->where('order_number','like',"%{$term}%")); return response()->json($query->latest()->paginate(10)); }
    public function store(StoreOrderRequest $request): JsonResponse { return response()->json(['data' => $this->orders->create($request->validated(), $request->user()->id)], 201); }
    public function show(Order $order): JsonResponse { return response()->json(['data' => $order->load(['customer','items.product','user'])]); }
    public function cancel(Order $order): JsonResponse { abort_if($order->status === 'Cancelled', 422, 'Order is already cancelled.'); return response()->json(['data' => $this->orders->cancel($order)]); }
}