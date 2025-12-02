<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('1'),
        ]);

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('1'),
        ]);

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);

        $admin = User::where('email', 'admin@gmail.com')->first();
        $admin->assignRole('admin');

        $superAdmin = User::where('email', 'superadmin@gmail.com')->first();
        $superAdmin->assignRole('super_admin');

        // Seeder untuk Master Data
        $this->call([
            DivisiSeeder::class,
            StatusSeeder::class,
            VisitStatusSeeder::class,
        ]);
    }
}
