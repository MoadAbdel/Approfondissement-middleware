<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
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
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Utilisateur "user"
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $viewDashboard = Permission::firstOrCreate(['name' => 'view dashboard']);
        $adminRole->givePermissionTo($viewDashboard);
        $admin->assignRole($adminRole);

        // Produits de l'admin
        if (!Product::where('user_id', $admin->id)->exists()) {
            Product::factory()->count(3)->create([
                'user_id' => $admin->id,
            ]);
        }

        // Produits de l'utilisateur simple
        if (!Product::where('user_id', $user->id)->exists()) {
            Product::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
