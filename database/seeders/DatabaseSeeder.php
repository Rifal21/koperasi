<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        $this->call(RoleSeeder::class);
        User::factory()->create([
            'name' => 'Admin User',
            'role_id' => Role::where('name', 'admin')->first()->id,
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'), // password
        ]);
        $this->call(ItemsSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ClientSeeder::class);

    }
}
