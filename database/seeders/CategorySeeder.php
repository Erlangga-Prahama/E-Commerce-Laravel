<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik',
            'Fashion Pria',
            'Fashion Wanita',
            'Perlengkapan Rumah Tangga',
            'Kesehatan & Kecantikan',
            'Makanan & Minuman',
            'Olahraga & Outdoor',
            'Buku & Alat Tulis',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}