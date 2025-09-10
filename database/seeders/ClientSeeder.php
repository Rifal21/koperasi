<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'name' => 'PT Sinar Jaya',
                'address' => 'Jl. Merdeka No. 12, Jakarta',
                'phone' => '021-5551234',
                'email' => 'sinarjaya@example.com',
                'pic' => 'Budi Santoso',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CV Maju Bersama',
                'address' => 'Jl. Raya Bandung No. 45, Bandung',
                'phone' => '022-8887654',
                'email' => 'majubersama@example.com',
                'pic' => 'Andi Prasetyo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UD Sejahtera Abadi',
                'address' => 'Jl. Sudirman No. 99, Surabaya',
                'phone' => '031-7779988',
                'email' => 'sejahteraabadi@example.com',
                'pic' => 'Siti Aminah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PT Nusantara Makmur',
                'address' => 'Jl. Diponegoro No. 88, Yogyakarta',
                'phone' => '0274-223344',
                'email' => 'nusantaramakmur@example.com',
                'pic' => 'Rahmat Hidayat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CV Karya Mandiri',
                'address' => 'Jl. Gatot Subroto No. 10, Medan',
                'phone' => '061-334455',
                'email' => 'karyamandiri@example.com',
                'pic' => 'Dewi Lestari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
    
}
