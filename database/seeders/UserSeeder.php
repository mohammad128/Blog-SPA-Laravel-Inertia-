<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admini',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin')
        ]);
        $role = Role::where('name','admin')->first();
        $user->syncRoles($role);


        $users = User::factory()->count(8)->create();
        $role = \Spatie\Permission\Models\Role::where('name', 'Subscriber')->first();
        foreach($users as $user) {
            $user->syncRoles($role);
        }

    }
}
