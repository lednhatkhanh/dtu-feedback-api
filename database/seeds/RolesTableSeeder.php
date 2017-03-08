<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = new Role();
        $admin_role->name = 'admin';
        $admin_role->save();
        $admin_role->givePermissionTo('access_backend');

        $user_role = new Role();
        $user_role->name = 'user';
        $user_role->save();
        $user_role->givePermissionTo('access_api');
    }
}
