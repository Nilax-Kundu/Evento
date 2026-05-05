<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password) {
            Log::warning('AdminSeeder: Skipping admin creation. ADMIN_EMAIL or ADMIN_PASSWORD not set in environment.');
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => 'System Admin',
                'password' => Hash::make($password),
                'role'     => 'admin',
            ]
        );
    }
}
