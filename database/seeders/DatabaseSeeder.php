<?php

namespace Database\Seeders;

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

        \App\Models\User::create([
            'email' => 'ppramesi@visi8.com',
            'name' => 'ppramesi',
            'password' => Hash::make('123qweasd'),
            'full_name' => 'full_name',
            'phone' => 'phone',
            'date_of_birth' => '1989-04-20',
            'phone' => 'phone',
            'id_number' => 'id_number',
            'portfolio_url' => 'portfolio_url',
            'social_media' => '{"facebook": "http://www.facebook.com"}',
        ]);
    }
}
