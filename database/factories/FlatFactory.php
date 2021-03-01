<?php

namespace Database\Factories;

use App\Models\Flat;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Flat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'flat_type' => strval(mt_rand(1,4)).'x'.strval(mt_rand(1,2)),
            'total_meter' => mt_rand(120,250),
            'net_meter' => mt_rand(85,180),
            'project_id' => mt_rand(1,50),
        ];
    }
}
