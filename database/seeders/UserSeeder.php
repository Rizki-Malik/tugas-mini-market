<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Toko
        $stores = [
            'Minimarket Pusat' => 'Jakarta',
            'Minimarket Cabang 1' => 'Bandung',
            'Minimarket Cabang 2' => 'Surabaya',
            'Minimarket Cabang 3' => 'Semarang',
            'Minimarket Cabang 4' => 'Yogyakarta',
        ];

        $storeModels = [];
        foreach ($stores as $name => $city) {
            $storeModels[] = Store::create([
                'name' => $name,
                'address' => 'Jl. ' . $city . ' No. ' . rand(1, 100),
                'city' => $city,
            ]);
        }

        // Owner (Pak Jayusman)
        $owner = User::create([
            'name' => 'Pak Jayusman',
            'email' => 'owner@minimarket.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $owner->assignRole('owner');

        // Manajer (satu untuk setiap toko)
        foreach ($storeModels as $store) {
            $manager = User::create([
                'name' => 'Manager ' . $store->city,
                'email' => 'manager.' . strtolower($store->city) . '@minimarket.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'store_id' => $store->id,
            ]);
            $manager->assignRole('store_manager');
        }

        // Supervisor (dua untuk setiap toko)
        foreach ($storeModels as $store) {
            for ($i = 1; $i <= 2; $i++) {
                $supervisor = User::create([
                    'name' => 'Supervisor ' . $i . ' ' . $store->city,
                    'email' => 'supervisor' . $i . '.' . strtolower($store->city) . '@minimarket.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'store_id' => $store->id,
                ]);
                $supervisor->assignRole('supervisor');
            }
        }

        // Kasir (tiga untuk setiap toko)
        foreach ($storeModels as $store) {
            for ($i = 1; $i <= 3; $i++) {
                $cashier = User::create([
                    'name' => 'Cashier ' . $i . ' ' . $store->city,
                    'email' => 'cashier' . $i . '.' . strtolower($store->city) . '@minimarket.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'store_id' => $store->id,
                ]);
                $cashier->assignRole('cashier');
            }
        }

        // Pegawai Gudang (dua untuk setiap toko)
        foreach ($storeModels as $store) {
            for ($i = 1; $i <= 2; $i++) {
                $warehouse = User::create([
                    'name' => 'Warehouse ' . $i . ' ' . $store->city,
                    'email' => 'warehouse' . $i . '.' . strtolower($store->city) . '@minimarket.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'store_id' => $store->id,
                ]);
                $warehouse->assignRole('warehouse');
            }
        }
    }
}