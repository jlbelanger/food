<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FoodFactory extends Factory
{
	public function definition() : array
	{
		return [
			'name' => 'Apple',
			'slug' => 'apple',
			'serving_size' => 1.5,
		];
	}
}
