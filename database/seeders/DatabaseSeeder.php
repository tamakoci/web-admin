<?php

namespace Database\Seeders;

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
        \App\Models\TopupDiamon::create([
            'diamon' => 100,
            'price' => 10000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 500,
            'price' => 50000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 10000,
            'price' => 100000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 5000,
            'price' => 500000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 10000,
            'price' => 1000000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 50000,
            'price' => 5000000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 100000,
            'price' => 10000000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 250000,
            'price' => 23000000
        ]);
        \App\Models\TopupDiamon::create([
            'diamon' => 500000,
            'price' => 45000000
        ]);

        \App\Models\TopupPangan::create([
            'pangan'=>10,
            'diamon'=>100
        ]);
        \App\Models\TopupPangan::create([
            'pangan'=>50,
            'diamon'=>500
        ]);
        \App\Models\TopupPangan::create([
            'pangan'=>100,
            'diamon'=>1000
        ]);
        \App\Models\TopupPangan::create([
            'pangan'=>500,
            'diamon'=>5000
        ]);
        \App\Models\TopupPangan::create([
            'pangan'=>2000,
            'diamon'=>20000
        ]);
        \App\Models\TopupPangan::create([
            'pangan'=>5000,
            'diamon'=>50000
        ]);
        \App\Models\TopupPangan::create([
            'pangan'=>10000,
            'diamon'=>100000
        ]);
        


        \App\Models\RequestMarket::create([
            'customer' => 1,
            'product'=>'daging',
            'qty'=>10,
            'satuan'=>'kg'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 2,
            'product'=>'daging',
            'qty'=>2,
            'satuan'=>'kg'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 3,
            'product'=>'susu',
            'qty'=>20,
            'satuan'=>'liter'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 4,
            'product'=>'telur',
            'qty'=>100,
            'satuan'=>'butir'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 5,
            'product'=>'susu',
            'qty'=>100,
            'satuan'=>'liter'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 6,
            'product'=>'telur',
            'qty'=>10,
            'satuan'=>'butir'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 7,
            'product'=>'daging',
            'qty'=>30,
            'satuan'=>'kg'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 8,
            'product'=>'telur',
            'qty'=>40,
            'satuan'=>'butir'
        ]);\App\Models\RequestMarket::create([
            'customer' => 9,
            'product'=>'susu',
            'qty'=>10,
            'satuan'=>'liter'
        ]);\App\Models\RequestMarket::create([
            'customer' => 10,
            'product'=>'daging',
            'qty'=>2,
            'satuan'=>'kg'
        ]);\App\Models\RequestMarket::create([
            'customer' => 11,
            'product'=>'susu',
            'qty'=>21,
            'satuan'=>'liter'
        ]);
        \App\Models\RequestMarket::create([
            'customer' => 12,
            'product'=>'telur',
            'qty'=>101,
            'satuan'=>'butir'
        ]);\App\Models\RequestMarket::create([
            'customer' => 13,
            'product'=>'susu',
            'qty'=>101,
            'satuan'=>'liter'
        ]);\App\Models\RequestMarket::create([
            'customer' => 14,
            'product'=>'telur',
            'qty'=>11,
            'satuan'=>'butir'
        ]);\App\Models\RequestMarket::create([
            'customer' => 15,
            'product'=>'daging',
            'qty'=>30,
            'satuan'=>'kg'
        ]);\App\Models\RequestMarket::create([
            'customer' => 16,
            'product'=>'telur',
            'qty'=>41,
            'satuan'=>'butir'
        ]);\App\Models\RequestMarket::create([
            'customer' => 17,
            'product'=>'telur',
            'qty'=>10,
            'satuan'=>'butir'
        ]);\App\Models\RequestMarket::create([
            'customer' => 18,
            'product'=>'telur',
            'qty'=>3,
            'satuan'=>'butir'
        ]);\App\Models\RequestMarket::create([
            'customer' => 19,
            'product'=>'susu',
            'qty'=>22,
            'satuan'=>'liter'
        ]);\App\Models\RequestMarket::create([
            'customer' => 20,
            'product'=>'susu',
            'qty'=>100,
            'satuan'=>'liter'
        ]);\App\Models\RequestMarket::create([
            'customer' => 21,
            'product'=>'telur',
            'qty'=>10,
            'satuan'=>'butir'
        ]);
    }
}
