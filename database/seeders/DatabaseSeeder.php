<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::create([
            'email' => 'ppramesi@visi8.com',
            'name' => 'ppramesi',
            'password' => Hash::make('123qweasd'),
            'full_name' => 'full_name',
            'phone' => 'phone',
            'date_of_birth' => '1989-04-20',
            'phone' => 'phone',
            'id_number' => 'id_number',
            'portfolio_url' => 'portfolio_url',
            'social_media' => '[{type: "facebook", url: "http://www.facebook.com"}]',
            'email_verified_at' => '2021-03-22 16:44:08'
        ]);

        $testuser = \App\Models\User::create([
            'email' => 'testuser@visi8.com',
            'name' => 'testuser',
            'password' => Hash::make('123qweasd'),
            'full_name' => 'test user',
            'phone' => '12345678',
            'date_of_birth' => '1989-04-20',
            'phone' => 'phone',
            'id_number' => '9087654',
            'portfolio_url' => 'http://www.facebook.com',
            'social_media' => '[{type: "facebook", url: "http://www.facebook.com"}]',
            'email_verified_at' => '2021-03-22 16:44:08'
        ]);

        $admin = Role::create(['name' => 'admin', 'display_name' => 'Admin', 'description' => 'Administrate website']);
        $judge = Role::create(['name' => 'judge', 'display_name' => 'Judge', 'description' => 'Judge submissions']);
        $artist = Role::create(['name' => 'artist', 'display_name' => 'Artist', 'description' => 'Regular user']);

        $createSub = Permission::create([
            'name' => 'create-submission',
            'display_name' => 'create submissions',
            'description' => 'create new submission'
        ]);

        $viewAllSub = Permission::create([
            'name' => 'view-all-submissions',
            'display_name' => 'view all submissions',
            'description' => 'View all submissions'
        ]);

        $viewOwnSub = Permission::create([
            'name' => 'view-own-submissions',
            'display_name' => 'view own submissions',
            'description' => 'View own submissions'
        ]);

        $changeRole = Permission::create([
            'name' => 'change-role',
            'display_name' => 'change users role',
            'description' => 'Change users role'
        ]);

        $admin->attachPermission($createSub);
        $admin->attachPermission($viewAllSub);
        $admin->attachPermission($viewOwnSub);
        $admin->attachPermission($changeRole);

        $judge->attachPermission($createSub);
        $judge->attachPermission($viewAllSub);
        $judge->attachPermission($viewOwnSub);

        $artist->attachPermission($createSub);
        $artist->attachPermission($viewOwnSub);

        $testuser = \App\Models\User::create([
            'email' => 'testuser@visi8.com',
            'name' => 'testuser',
            'password' => Hash::make('123qweasd'),
            'full_name' => 'test user',
            'phone' => '12345678',
            'date_of_birth' => '1989-04-20',
            'phone' => 'phone',
            'id_number' => '9087654',
            'portfolio_url' => 'http://www.facebook.com',
            'social_media' => '{"facebook": "http://www.facebook.com"}',
            'email_verified_at' => '2021-03-22 16:44:08'
        ]);

        $user->attachRole($admin);
        $testuser->attachRole($artist);
    }
}
