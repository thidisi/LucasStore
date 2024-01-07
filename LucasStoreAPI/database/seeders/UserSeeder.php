<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        User::insert([
            [
                'id' => (string)\Str::uuid(),
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => '$2y$10$4Qi7YDnqmz5W4xzJlENTa.0olHCFguwsc4233HCDlcKXGt25fB9hm$2y$10$dfqoMOYSiFUmmkPYOwZq1uEnxsVJSNgRmj/RoykBQo7j5ytirvfCG',
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
