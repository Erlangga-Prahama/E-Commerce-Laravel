<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Elektronik' => [
                ['Headphone Bluetooth JBL Tune 510', 350000],
                ['Power Bank Anker 10000mAh', 275000],
                ['Mouse Wireless Logitech M170', 95000],
                ['Kabel Charger USB-C 1 Meter', 35000],
            ],
            'Fashion Pria' => [
                ['Kemeja Flanel Lengan Panjang', 149000],
                ['Celana Chino Slim Fit', 189000],
                ['Kaos Polos Cotton Combed 30s', 59000],
            ],
            'Fashion Wanita' => [
                ['Dress Casual Motif Bunga', 175000],
                ['Blouse Wanita Lengan Balon', 129000],
                ['Rok Plisket Midi', 139000],
            ],
            'Perlengkapan Rumah Tangga' => [
                ['Rak Piring Stainless 2 Susun', 89000],
                ['Set Panci Anti Lengket 3pcs', 245000],
                ['Sapu Lantai Serat Halus', 45000],
            ],
            'Kesehatan & Kecantikan' => [
                ['Sunscreen SPF 50 PA+++', 65000],
                ['Vitamin C 1000mg 30 Tablet', 55000],
                ['Hand Sanitizer 500ml', 25000],
            ],
            'Makanan & Minuman' => [
                ['Kopi Arabika Gayo 200gr', 68000],
                ['Madu Hutan Asli 350ml', 95000],
                ['Keripik Singkong Balado 250gr', 22000],
            ],
            'Olahraga & Outdoor' => [
                ['Matras Yoga Anti Slip', 115000],
                ['Botol Minum Olahraga 1L', 45000],
                ['Sepatu Lari Ringan', 299000],
            ],
            'Buku & Alat Tulis' => [
                ['Buku Tulis 58 Lembar isi 10', 32000],
                ['Pulpen Gel 0.5mm isi 12', 28000],
                ['Notebook Hardcover A5', 42000],
            ],
        ];

        foreach ($data as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->first();

            foreach ($products as [$name, $price]) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
                    'description' => fake()->paragraph(3),
                    'price' => $price,
                    'stock' => fake()->numberBetween(5, 80),
                    'is_active' => true,
                ]);
            }
        }
    }
}