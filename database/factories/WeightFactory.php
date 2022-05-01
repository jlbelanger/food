<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WeightFactory extends Factory
{
	/**
	 * Defines the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'user_id' => \App\Models\User::factory(),
			'weight' => 123.4,
			'date' => '2001-02-03',
		];
	}
}
