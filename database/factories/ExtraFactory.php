<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExtraFactory extends Factory
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
			'note' => 'Foo',
			'date' => '2001-02-03',
		];
	}
}
