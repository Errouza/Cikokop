<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing menu items
        Menu::truncate();

        $items = [
            // Coffee
            [
                'nama' => 'Americano',
                'deskripsi' => 'Espresso dengan air dingin dan es.',
                'harga' => 8000,
                'kategori' => 'coffee',
                'gambar_url' => 'image/FotoKopi/Americano.png'
            ],
            [
                'nama' => 'Long Black',
                'deskripsi' => 'Espresso kental dengan air dingin dan es.',
                'harga' => 8000,
                'kategori' => 'coffee',
                'gambar_url' => 'image/FotoKopi/LongBlack.png'
            ],
            [
                'nama' => 'Cilatte',
                'deskripsi' => 'Susu segar dengan espresso dan es.',
                'harga' => 10000,
                'kategori' => 'coffee',
                'gambar_url' => 'image/FotoKopi/Cilatte.png'
            ],
            [
                'nama' => 'Aren Latte',
                'deskripsi' => 'Susu segar dengan espresso dan es.',
                'harga' => 10000,
                'kategori' => 'coffee',
                'gambar_url' => 'image/FotoKopi/Aren Latte.png'
            ],

            // Matcha
            [
                'nama' => 'Matcha Ekspresso',
                'deskripsi' => 'Matcha dengan espresso, disajikan dingin dengan es.',
                'harga' => 12000,
                'kategori' => 'matcha',
                'gambar_url' => 'image/FotoKopi/MatchaEkspresso.png'
            ],
            [
                'nama' => 'Matcha Latte',
                'deskripsi' => 'Susu segar dengan matcha dan es.',
                'harga' => 12000,
                'kategori' => 'matcha',
                'gambar_url' => 'image/FotoKopi/Matcha.png'
            ],

            // Ricebowl
            [
                'nama' => 'Ayam Katsu',
                'deskripsi' => 'Ayam katsu krispi dengan nasi dan sayuran.',
                'harga' => 15000,
                'kategori' => 'ricebowl',
                'gambar_url' => 'image/FotoRicebowl/AyamKatsu.png'
            ],
            [
                'nama' => 'Ayam Teriyaki',
                'deskripsi' => 'Ayam panggang saus teriyaki dengan nasi.',
                'harga' => 15000,
                'kategori' => 'ricebowl',
                'gambar_url' => 'image/FotoRicebowl/AyamTeriyaki.png'
            ],
            [
                'nama' => 'Ayam Barbeque',
                'deskripsi' => 'Ayam panggang saus barbeque dengan nasi.',
                'harga' => 15000,
                'kategori' => 'ricebowl',
                'gambar_url' => 'image/FotoRicebowl/AyamBarbeque.png'
            ],
            [
                'nama' => 'Cumi Goreng Tepung',
                'deskripsi' => 'Cumi goreng tepung krispi dengan nasi.',
                'harga' => 15000,
                'kategori' => 'ricebowl',
                'gambar_url' => 'image/FotoRicebowl/CumiGorengTepung.png'
            ],
        ];

        foreach($items as $item) {
            Menu::create($item);
        }
    }
}