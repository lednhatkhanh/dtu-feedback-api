<?php

use Illuminate\Database\Seeder;

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
    }
}
