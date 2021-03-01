<?php

namespace Database\Factories;

use App\Models\Repres;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepresFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repres::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'repres_name'=>$this->faker->name,
            'phone_number' => "0090".strval($this->faker->randomNumber(3)).strval($this->faker->randomNumber(7)),
            'project_id' => mt_rand(0,50),
        ];
    }
}
