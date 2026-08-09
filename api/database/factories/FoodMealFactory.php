<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FoodMealFactory extends Factory
{
	public function definition() : array
	{
		return [
			'food_id' => \App\Models\Food::factory(),
			'meal_id' => \App\Models\Meal::factory(),
			'user_serving_size' => 1,
		];
	}
}
