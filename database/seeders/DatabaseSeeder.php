<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['name' => 'Admin Morgan', 'email' => 'admin@northstar.test', 'password' => 'password', 'role' => 'admin']);
        User::factory()->create(['name' => 'Staff Taylor', 'email' => 'staff@northstar.test', 'password' => 'password', 'role' => 'staff']);
        Customer::insert([
            ['name' => 'Maya Chen', 'phone' => '+1 415 555 0192', 'email' => 'maya.chen@example.com', 'address' => 'San Francisco, CA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jon Bell', 'phone' => '+1 212 555 0181', 'email' => 'jon.bell@example.com', 'address' => 'Brooklyn, NY', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Elena Rossi', 'phone' => '+39 02 555 0118', 'email' => 'elena.rossi@example.com', 'address' => 'Milan, Italy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Samir Patel', 'phone' => '+44 20 5550 1452', 'email' => 'samir.patel@example.com', 'address' => 'London, UK', 'created_at' => now(), 'updated_at' => now()],
        ]);
        Product::insert([
            ['name' => 'AeroFlex Running Shoes', 'sku' => 'AF-RUN-042', 'category' => 'Footwear', 'price' => 129, 'stock_quantity' => 8, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Core Daily Backpack', 'sku' => 'CD-BAG-018', 'category' => 'Accessories', 'price' => 74, 'stock_quantity' => 23, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Northstar Field Watch', 'sku' => 'NF-WAT-006', 'category' => 'Accessories', 'price' => 189, 'stock_quantity' => 3, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Meridian Knit Hoodie', 'sku' => 'MK-HOD-033', 'category' => 'Apparel', 'price' => 96, 'stock_quantity' => 0, 'status' => 'Out of stock', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Studio Ceramic Bottle', 'sku' => 'SC-BOT-021', 'category' => 'Lifestyle', 'price' => 42, 'stock_quantity' => 16, 'status' => 'Active', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}