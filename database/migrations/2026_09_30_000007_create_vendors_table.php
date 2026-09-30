<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { public function up(): void { Schema::create('vendors', function (Blueprint $table): void { $table->id(); $table->string('name'); $table->string('contact'); $table->string('email')->unique(); $table->string('category'); $table->decimal('rating', 2, 1)->default(0); $table->enum('status', ['Active','Review','Paused'])->default('Active'); $table->softDeletes(); $table->timestamps(); $table->index(['category','status']); }); } public function down(): void { Schema::dropIfExists('vendors'); } };