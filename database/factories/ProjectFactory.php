<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->streetName,
            'province' => 'Istanbul',
            'district' => $this->faker->city,
            'under_constructions' => $this->randBool(mt_rand(1, 1000)),
            'min_price' => mt_rand(50000, 200000) + 0.42,
            'max_price' => mt_rand(50000, 200000) + 0.35,
            'type' => 'site',
            'specifications' => json_encode($this->faker->words(mt_rand(5,10))),
            'is_archived' => false,

        ];
    }


    private function randBool($num)
    {
        if ($num % 2 == 0) {
            return true;
        } else {
            return false;
        }
    }
}
