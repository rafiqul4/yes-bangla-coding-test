<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse { $query = Product::query()->when($request->search, fn ($q, $term) => $q->where(fn ($inner) => $inner->where('name','like',"%{$term}%")->orWhere('sku','like',"%{$term}%")))->when($request->category, fn ($q, $category) => $q->where('category', $category)); return response()->json($query->latest()->paginate(10)); }
    public function store(StoreProductRequest $request): JsonResponse { return response()->json(['data' => Product::create($request->validated())], 201); }
    public function show(Product $product): JsonResponse { return response()->json(['data' => $product]); }
    public function update(UpdateProductRequest $request, Product $product): JsonResponse { $product->update($request->validated()); return response()->json(['data' => $product->refresh()]); }
    public function destroy(Request $request, Product $product): JsonResponse { abort_unless($request->user()->isAdmin(), 403); $product->delete(); return response()->json(['message' => 'Product deleted.']); }
}