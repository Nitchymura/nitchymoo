<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Takeshi',
                'email' => 'takeshi@mail.com',
                'password' => Hash::make('takeshi1234'),
                'role_id' => 1,
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],[
                'name' => 'Bell',
                'email' => 'bell@mail.com',
                'password' => Hash::make('bell2222'),
                'role_id' => 2,
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],[
                'name' => 'Smith',
                'email' => 'smith@mail.com',
                'password' => Hash::make('smith3333'),
                'role_id' => 2,
                'updated_at' => NOW(),
                'created_at' => NOW()
            ]
        ];
        User::insert($users);
    }
}
