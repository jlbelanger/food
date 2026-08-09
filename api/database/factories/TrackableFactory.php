<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrackableFactory extends Factory
{
	public function definition() : array
	{
		return [
			'name' => 'Calories',
			'slug' => 'calories',
		];
	}
}
