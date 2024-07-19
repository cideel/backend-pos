<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = [
            [
                'name' => 'John Doe',
                'phone_number' => '081234567890',
                'email' => 'johndoe@example.com',
                'is_logged_in' => false,
                'points' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'phone_number' => '082345678901',
                'email' => 'janesmith@example.com',
                'is_logged_in' => false,
                'points' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Johnson',
                'phone_number' => '083456789012',
                'email' => 'alicejohnson@example.com',
                'is_logged_in' => false,
                'points' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
