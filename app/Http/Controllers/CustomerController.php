<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse { $query = Customer::query()->when($request->search, fn ($q, $term) => $q->where(fn ($inner) => $inner->where('name','like',"%{$term}%")->orWhere('email','like',"%{$term}%"))); return response()->json($query->latest()->paginate(10)); }
    public function store(StoreCustomerRequest $request): JsonResponse { return response()->json(['data' => Customer::create($request->validated())], 201); }
    public function show(Customer $customer): JsonResponse { return response()->json(['data' => $customer->load('orders')]); }
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse { $customer->update($request->validated()); return response()->json(['data' => $customer->refresh()]); }
    public function destroy(Request $request, Customer $customer): JsonResponse { abort_unless($request->user()->isAdmin(), 403); $customer->delete(); return response()->json(['message' => 'Customer deleted.']); }
}