<?php

namespace Database\Factories;

use App\Models\Matiere;
use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatiereFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Matiere::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
	    'code' => $this->faker->word,
	    'nom' => $this->faker->word,
	    'coef' => $this->faker->randomDigit,

	    'niveau_id' => Niveau::factory(),
        ];
    }
}
