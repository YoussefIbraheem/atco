<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ["admin", "user"];

        foreach ($roles as $role) {
            $roleExists = Role::query()->where("name", $role)->exists();
            if ($roleExists) {
                continue;
            }
            Role::create(["name" => $role]);
        }
    }
}
