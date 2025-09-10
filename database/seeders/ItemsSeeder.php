<?php

namespace Database\Seeders;

use App\Models\Items;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Beras Premium', 'category' => 'Bahan Pokok', 'stock' => 100, 'price_buy' => 10000, 'price_sell' => 12000],
            ['name' => 'Gula Pasir', 'category' => 'Bahan Pokok', 'stock' => 80, 'price_buy' => 9000, 'price_sell' => 11000],
            ['name' => 'Minyak Goreng', 'category' => 'Bahan Pokok', 'stock' => 60, 'price_buy' => 14000, 'price_sell' => 16000],
            ['name' => 'Tepung Terigu', 'category' => 'Bahan Pokok', 'stock' => 70, 'price_buy' => 8000, 'price_sell' => 10000],
            ['name' => 'Kopi Bubuk', 'category' => 'Bahan Pokok', 'stock' => 50, 'price_buy' => 15000, 'price_sell' => 18000],
            ['name' => 'Teh Celup', 'category' => 'Bahan Pokok', 'stock' => 60, 'price_buy' => 12000, 'price_sell' => 15000],
            ['name' => 'Susu Bubuk', 'category' => 'Bahan Pokok', 'stock' => 40, 'price_buy' => 25000, 'price_sell' => 28000],
            ['name' => 'Mie Instan', 'category' => 'Bahan Pokok', 'stock' => 200, 'price_buy' => 2500, 'price_sell' => 3000],
            ['name' => 'Telur Ayam', 'category' => 'Bahan Pokok', 'stock' => 100, 'price_buy' => 22000, 'price_sell' => 25000],
            ['name' => 'Garam Dapur', 'category' => 'Bahan Pokok', 'stock' => 90, 'price_buy' => 4000, 'price_sell' => 6000],

            ['name' => 'Sabun Mandi', 'category' => 'Bahan Penunjang', 'stock' => 70, 'price_buy' => 5000, 'price_sell' => 7000],
            ['name' => 'Shampoo', 'category' => 'Bahan Penunjang', 'stock' => 60, 'price_buy' => 15000, 'price_sell' => 18000],
            ['name' => 'Pasta Gigi', 'category' => 'Bahan Penunjang', 'stock' => 80, 'price_buy' => 9000, 'price_sell' => 12000],
            ['name' => 'Deterjen Bubuk', 'category' => 'Bahan Penunjang', 'stock' => 50, 'price_buy' => 16000, 'price_sell' => 19000],
            ['name' => 'Pewangi Pakaian', 'category' => 'Bahan Penunjang', 'stock' => 45, 'price_buy' => 10000, 'price_sell' => 13000],
            ['name' => 'Tisu Wajah', 'category' => 'Bahan Penunjang', 'stock' => 55, 'price_buy' => 8000, 'price_sell' => 10000],
            ['name' => 'Air Mineral 600ml', 'category' => 'Bahan Penunjang', 'stock' => 100, 'price_buy' => 3000, 'price_sell' => 4000],
            ['name' => 'Baterai AA', 'category' => 'Bahan Penunjang', 'stock' => 40, 'price_buy' => 10000, 'price_sell' => 13000],
            ['name' => 'Lampu LED', 'category' => 'Bahan Penunjang', 'stock' => 30, 'price_buy' => 20000, 'price_sell' => 25000],
            ['name' => 'Sapu Lantai', 'category' => 'Bahan Penunjang', 'stock' => 20, 'price_buy' => 15000, 'price_sell' => 20000],
        ];

        foreach ($items as $index => $item) {
            Items::create([
                'code' => 'BRG' . str_pad($index + 1, 3, '0', STR_PAD_LEFT), // BRG001 dst
                'name' => $item['name'],
                'category' => $item['category'],
                'stock' => $item['stock'],
                'price_buy' => $item['price_buy'],
                'price_sell' => $item['price_sell'],
            ]);
        }
    }
}
