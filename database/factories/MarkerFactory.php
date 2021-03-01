<?php

namespace Database\Factories;

use App\Models\Marker;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Marker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lat' => $this->mt_rand_float(40.97416,41.16486,'0000000000'),
            'lag' => $this->mt_rand_float(28.60897,28.87323,'0000000000'),
            'project_id' => $this->faker->unique()->numberBetween(1,50),
        ];
    }

    function mt_rand_float($min, $max, $countZero = '0') {
        $countZero = +('1'.$countZero);
        $min = floor($min*$countZero);
        $max = floor($max*$countZero);
        $rand = mt_rand($min, $max) / $countZero;
        return $rand;
    }
}
