<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Eleve;

class EleveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	Eleve::factory()->times(100)->create();
    }
}
