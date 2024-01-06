<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
               'weight' => $this->faker->randomFloat(2,100,200),
               'name' => $name = $this->faker->unique()->catchPhrase(),
               'description' => $this->faker->realText(),
               'price' => $this->faker->randomFloat(2, 80, 400),
               'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
               'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
