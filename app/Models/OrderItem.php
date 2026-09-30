<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'line_total'];
    protected function casts(): array { return ['quantity' => 'integer', 'unit_price' => 'decimal:2', 'line_total' => 'decimal:2']; }
    public function product() { return $this->belongsTo(Product::class); }
}