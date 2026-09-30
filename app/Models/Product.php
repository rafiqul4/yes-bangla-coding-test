<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'sku', 'category', 'price', 'stock_quantity', 'status'];
    protected function casts(): array { return ['price' => 'decimal:2', 'stock_quantity' => 'integer']; }
    public function getStockAttribute(): int { return $this->stock_quantity; }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
}