<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nom' => 'Admin'],
            ['nom' => 'Super-Admin'],
        ];

        foreach ($roles as $role) {
            $existingRole = Role::where('nom', $role['nom'])->first();
            if (!$existingRole) {
                Role::create($role);
            }
        }
    }
}
