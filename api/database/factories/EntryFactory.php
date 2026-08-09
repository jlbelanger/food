<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
	public function definition() : array
	{
		return [
			'food_id' => \App\Models\Food::factory(),
			'user_id' => \App\Models\User::factory(),
			'user_serving_size' => 1,
			'date' => '2001-02-03',
		];
	}
}
