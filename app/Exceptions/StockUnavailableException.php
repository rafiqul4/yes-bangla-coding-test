<?php

namespace App\Exceptions;

use RuntimeException;

class StockUnavailableException extends RuntimeException
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => 'The requested stock is no longer available.',
            'code' => 'stock_unavailable',
        ], 409);
    }
}