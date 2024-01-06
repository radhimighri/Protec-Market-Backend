<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => 'OR' . $this->faker->unique()->randomNumber(6),
            'customer_id' => \App\Models\Customer::factory(),
            'total_price' => $this->faker->randomFloat(2, 100, 2000),
            'stauts' => $this->faker->randomElement(['pending', 'processing', 'packed', 'picked', 'cancelled']),
            //'shipping_price' => $this->faker->randomFloat(2, 100, 500),
           // 'shipping_method' => $this->faker->randomElement(['free', 'flat', 'flat_rate', 'flat_rate_per_item']),
           // 'notes' => $this->faker->realText(100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
            'pick_up_date' => $this->faker->dateTimeBetween('now', '+4 day'),

        ];
    }

    
}
