<?php

use Illuminate\Database\Seeder;
use App\Campus;

class CampusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campus::create(['name' => 'Quang Trung', 'address' => '03 Quang Trung']);
        Campus::create(['name' => 'Nguyen Van Linh', 'address' => '182 Nguyen Van Linh']);
        Campus::create(['name' => 'Hoa Khanh', 'address' => 'Hoa Khanh']);
    }
}
