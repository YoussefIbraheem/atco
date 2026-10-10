<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $adminExists = User::where('email', 'admin@admin.com')->first();
        $password = "password@123";
        if (! $adminExists) {
            $admin = User::factory()->create([
                'name' => 'admin',
                'password' => Hash::make($password),
                'email' => 'admin@admin.com',
                'email_verified_at' => now()
            ]);
            $admin->assignRole('admin');
            $this->command->info("Admin created successfully!");
            $this->command->info("Email:".$admin->email);
            $this->command->info("Password:".$password);

        }
    }
}
