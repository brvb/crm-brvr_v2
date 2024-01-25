<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\Districts::create([
            'id' => 1,
            'name' => "Aveiro",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 2,
            'name' => "Beja",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 3,
            'name' => "Braga",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 4,
            'name' => "Bragança",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 5,
            'name' => "Castelo Branco",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 6,
            'name' => "Coimbra",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 7,
            'name' => "Évora",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 8,
            'name' => "Faro",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 9,
            'name' => "Guarda",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 10,
            'name' => "Leiria",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 11,
            'name' => "Lisboa",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 12,
            'name' => "Portalegre",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 13,
            'name' => "Porto",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 14,
            'name' => "Santarém",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 15,
            'name' => "Setúbal",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 16,
            'name' => "Viana do Castelo",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 17,
            'name' => "Vila Real",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 18,
            'name' => "Viseu",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 31,
            'name' => "Ilha da Madeira",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 32,
            'name' => "Ilha de Porto Santo",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 41,
            'name' => "Ilha de Santa Maria",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 42,
            'name' => "Ilha de São Miguel",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 43,
            'name' => "Ilha Terceira",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 44,
            'name' => "Ilha da Graciosa",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 45,
            'name' => "Ilha de São Jorge",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 46,
            'name' => "Ilha do Pico",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 47,
            'name' => "Ilha do Faial",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 48,
            'name' => "Ilha das Flores",
            'active' => 1,
        ]);

        \App\Models\Districts::create([
            'id' => 49,
            'name' => "Ilha do Corvo",
            'active' => 1,
        ]);

    }
}
