<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

       // Utilisateur "admin"
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Utilisateur "user"
        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password'),
            ]
        );

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $viewDashboard = Permission::firstOrCreate(['name' => 'view dashboard']);
        $adminRole->givePermissionTo($viewDashboard);
        $admin->assignRole($adminRole);

        // Produits de l'admin
        if (! Product::where('user_id', $admin->id)->exists()) {
            Product::factory()->count(3)->create([
                'user_id' => $admin->id,
            ]);
        }

        // Produits de l'utilisateur simple
        if (! Product::where('user_id', $user->id)->exists()) {
            Product::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
