<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $suppliers = [
            [
                'name' => 'PT Bahan Bangunan Jaya',
                'address' => 'Jl. Merdeka No. 45, Jakarta',
                'phone' => '081234567890',
                'email' => 'contact@bbjaya.co.id',
                'pic' => 'Budi Santoso',
            ],
            [
                'name' => 'CV Elektronik Nusantara',
                'address' => 'Jl. Soekarno Hatta No. 21, Bandung',
                'phone' => '082233445566',
                'email' => 'sales@elnusantara.com',
                'pic' => 'Agus Setiawan',
            ],
            [
                'name' => 'PT Pangan Makmur',
                'address' => 'Jl. Raya Bogor KM 17, Depok',
                'phone' => '081355667788',
                'email' => 'info@panganmakmur.id',
                'pic' => 'Siti Aminah',
            ],
            [
                'name' => 'UD Cahaya Plastik',
                'address' => 'Jl. Diponegoro No. 10, Surabaya',
                'phone' => '081988877766',
                'email' => 'support@cahayaplastik.com',
                'pic' => 'Hendra Wijaya',
            ],
            [
                'name' => 'PT Logam Prima',
                'address' => 'Jl. Gatot Subroto No. 99, Medan',
                'phone' => '081277889900',
                'email' => 'cs@logamprima.co.id',
                'pic' => 'Rudi Hartono',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
