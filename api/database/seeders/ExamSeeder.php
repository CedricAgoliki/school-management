<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Examen;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    Examen::create(['code' => 'EV1', 'libelle' => 'Evaluation 1']);
	    Examen::create(['code' => 'EV2', 'libelle' => 'Evaluation 2']);
	    Examen::create(['code' => 'EV3', 'libelle' => 'Evaluation 3']);
	    Examen::create(['code' => 'COMP', 'libelle' => 'Composition']);
        //
    }
}
