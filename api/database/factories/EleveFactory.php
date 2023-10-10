<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\Niveau;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class EleveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Eleve::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
	    'nom' => $this->faker->name,
	    'prenom' => $this->faker->name,
	    'matricule' => $this->faker->randomDigit,
	    'naissance' => $this->faker->date(),
	    'sexe'=> $this->faker->randomElement(['F','M']),
	    'adresse'=> $this->faker->word,
	    'niveau_id' => Niveau::factory(),
	    'classe_id' => Classe::factory(),
        ];
    }
}
