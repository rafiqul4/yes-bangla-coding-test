<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { public function up(): void { Schema::create('products', function (Blueprint $table): void { $table->id(); $table->string('name'); $table->string('sku')->unique(); $table->string('category'); $table->decimal('price', 12, 2); $table->unsignedInteger('stock_quantity')->default(0); $table->enum('status', ['Active','Out of stock','Archived'])->default('Active'); $table->softDeletes(); $table->timestamps(); $table->index(['category','status']); }); } public function down(): void { Schema::dropIfExists('products'); } };