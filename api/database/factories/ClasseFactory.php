<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Classe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
	    'libelle' => $this->faker->word,
	    'description' => $this->faker->word,
	    'niveau_id' => Niveau::factory(),
        ];
    }
}
