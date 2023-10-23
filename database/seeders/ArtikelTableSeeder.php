<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ArtikelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Artikel::factory(10)->create();
    }
}