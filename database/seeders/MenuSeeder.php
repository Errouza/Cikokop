<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['nama' => 'Espresso', 'deskripsi' => 'Single shot espresso', 'harga' => 12000, 'kategori' => 'coffee', 'gambar_url' => null],
            ['nama' => 'Americano', 'deskripsi' => 'Espresso with hot water', 'harga' => 15000, 'kategori' => 'coffee', 'gambar_url' => null],
            ['nama' => 'Latte', 'deskripsi' => 'Milk with espresso', 'harga' => 20000, 'kategori' => 'coffee', 'gambar_url' => null],
            ['nama' => 'Cappuccino', 'deskripsi' => 'Frothed milk with espresso', 'harga' => 21000, 'kategori' => 'coffee', 'gambar_url' => null],
            ['nama' => 'Chocolate Cake', 'deskripsi' => 'Rich chocolate slice', 'harga' => 30000, 'kategori' => 'dessert', 'gambar_url' => null],
        ];

        foreach($items as $it){
            Menu::create($it);
        }
    }
}
