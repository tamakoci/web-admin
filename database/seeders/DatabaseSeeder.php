<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Ternak;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\UserRole::create([
            'role_name'=>'User'
        ]);
        \App\Models\UserRole::create([
            'role_name'=>'Admin'
        ]);

        Product::create([
            'name'      => 'Telur',
            'satuan'    => 'Butir',
            'dm'        => 1,
            'status'    => 1
        ]);
        Ternak::create([
            'ternaks'   => 'Ayam',
            'price'     => '10000',
            'duration'  => 30,
            'produk_id' => 1,
            'avatar'    => 
        ])

        
    }
}
