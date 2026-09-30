<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __invoke(): JsonResponse { return response()->json(['customers' => Customer::count(), 'products' => Product::count(), 'orders' => Order::count(), 'sales' => Order::where('status','!=','Cancelled')->sum('total'), 'low_stock_products' => Product::where('stock_quantity','<',5)->count()]); }
}