<?php

namespace Database\Factories;

use App\Models\Paiement;
use App\Models\Eleve;
use App\Models\Annee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Paiement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'montant' => $this->faker->randomNumber(),
            'infos' => $this->faker->word(20),
            'eleve_id' => Eleve::factory(),
            'annee_id' => Annee::factory(),
        ];
    }
}
