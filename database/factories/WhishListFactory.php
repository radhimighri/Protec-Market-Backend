<?php

namespace Database\Factories;

use App\Models\WhishList;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class WhishListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WhishList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [];
    }
}
