<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_number', 'customer_id', 'user_id', 'total', 'status', 'ordered_at'];
    protected function casts(): array { return ['total' => 'decimal:2', 'ordered_at' => 'datetime']; }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }
}