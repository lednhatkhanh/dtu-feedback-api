<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Category::create(['name' => 'Giang Vien']);
        \App\Category::create(['name' => 'Co so vat chat']);
    }
}
