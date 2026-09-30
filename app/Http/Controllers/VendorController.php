<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request): JsonResponse { $query = Vendor::query()->when($request->search, fn ($q, $term) => $q->where(fn ($inner) => $inner->where('name','like',"%{$term}%")->orWhere('email','like',"%{$term}%"))); return response()->json($query->latest()->paginate(10)); }
    public function store(StoreVendorRequest $request): JsonResponse { return response()->json(['data' => Vendor::create($request->validated())], 201); }
    public function show(Vendor $vendor): JsonResponse { return response()->json(['data' => $vendor]); }
    public function update(UpdateVendorRequest $request, Vendor $vendor): JsonResponse { $vendor->update($request->validated()); return response()->json(['data' => $vendor->refresh()]); }
    public function destroy(Request $request, Vendor $vendor): JsonResponse { abort_unless($request->user()->isAdmin(), 403); $vendor->delete(); return response()->json(['message' => 'Vendor deleted.']); }
}