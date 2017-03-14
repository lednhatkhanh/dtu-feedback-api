<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CampusesTableSeeder::class);

        factory(\App\User::class, 5)->create();
        factory(\App\Feedback::class, 30)->create();
        factory(\App\Comment::class, 60)->create();
        for($i = 2; $i <= 6; $i++) {
            DB::table('user_has_roles')->insert([
                'role_id' => 2,
                'user_id' => $i
            ]);
        }
    }
}
