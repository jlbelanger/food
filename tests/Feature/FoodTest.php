<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodTest extends TestCase
{
	use RefreshDatabase;

	protected $path = '/food';

	protected function setUp() : void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create(['username' => 'bar', 'email' => 'bar@example.com']);
		$this->food = Food::factory()->create(['user_id' => $this->user->id]);
		$this->otherFood = Food::factory()->create(['name' => 'Banana', 'slug' => 'banana', 'user_id' => $this->otherUser->id]);
	}

	public function testIndex() : void
	{
		$response = $this->actingAs($this->user)->json('GET', $this->path);
		$response->assertExactJson([
			'data' => [
				[
					'id' => (string) $this->food->id,
					'type' => 'food',
					'attributes' => [
						'name' => 'Apple',
						'slug' => 'apple',
						'serving_size' => 1.5,
						'serving_units' => null,
						'front_image' => null,
						'info_image' => null,
						'calories' => null,
						'fat' => null,
						'saturated_fat' => null,
						'trans_fat' => null,
						'polyunsaturated_fat' => null,
						'omega_6' => null,
						'omega_3' => null,
						'monounsaturated_fat' => null,
						'cholesterol' => null,
						'sodium' => null,
						'potassium' => null,
						'carbohydrate' => null,
						'fibre' => null,
						'sugars' => null,
						'protein' => null,
						'vitamin_a' => null,
						'vitamin_c' => null,
						'calcium' => null,
						'iron' => null,
						'vitamin_d' => null,
						'vitamin_e' => null,
						'vitamin_k' => null,
						'thiamin' => null,
						'riboflavin' => null,
						'niacin' => null,
						'vitamin_b6' => null,
						'folate' => null,
						'vitamin_b12' => null,
						'biotin' => null,
						'pantothenate' => null,
						'phosphorus' => null,
						'iodine' => null,
						'magnesium' => null,
						'zinc' => null,
						'selenium' => null,
						'copper' => null,
						'manganese' => null,
						'chromium' => null,
						'molybdenum' => null,
						'chloride' => null,
						'is_favourite' => false,
						'is_verified' => false,
						'deleteable' => true,
					],
				],
				[
					'id' => (string) $this->otherFood->id,
					'type' => 'food',
					'attributes' => [
						'name' => 'Banana',
						'slug' => 'banana',
						'serving_size' => 1.5,
						'serving_units' => null,
						'front_image' => null,
						'info_image' => null,
						'calories' => null,
						'fat' => null,
						'saturated_fat' => null,
						'trans_fat' => null,
						'polyunsaturated_fat' => null,
						'omega_6' => null,
						'omega_3' => null,
						'monounsaturated_fat' => null,
						'cholesterol' => null,
						'sodium' => null,
						'potassium' => null,
						'carbohydrate' => null,
						'fibre' => null,
						'sugars' => null,
						'protein' => null,
						'vitamin_a' => null,
						'vitamin_c' => null,
						'calcium' => null,
						'iron' => null,
						'vitamin_d' => null,
						'vitamin_e' => null,
						'vitamin_k' => null,
						'thiamin' => null,
						'riboflavin' => null,
						'niacin' => null,
						'vitamin_b6' => null,
						'folate' => null,
						'vitamin_b12' => null,
						'biotin' => null,
						'pantothenate' => null,
						'phosphorus' => null,
						'iodine' => null,
						'magnesium' => null,
						'zinc' => null,
						'selenium' => null,
						'copper' => null,
						'manganese' => null,
						'chromium' => null,
						'molybdenum' => null,
						'chloride' => null,
						'is_favourite' => false,
						'is_verified' => false,
						'deleteable' => true,
					],
				],
			],
		]);
		$response->assertStatus(200);
	}

	public static function storeProvider() : array
	{
		return [
			[[
				'body' => [
					'data' => [
						'type' => 'food',
						'attributes' => [
							'name' => 'Coconut',
							'slug' => 'coconut',
							'serving_size' => 2,
							'serving_units' => null,
							'front_image' => null,
							'info_image' => null,
							'calories' => null,
							'fat' => null,
							'saturated_fat' => null,
							'trans_fat' => null,
							'polyunsaturated_fat' => null,
							'omega_6' => null,
							'omega_3' => null,
							'monounsaturated_fat' => null,
							'cholesterol' => null,
							'sodium' => null,
							'potassium' => null,
							'carbohydrate' => null,
							'fibre' => null,
							'sugars' => null,
							'protein' => null,
							'vitamin_a' => null,
							'vitamin_c' => null,
							'calcium' => null,
							'iron' => null,
							'vitamin_d' => null,
							'vitamin_e' => null,
							'vitamin_k' => null,
							'thiamin' => null,
							'riboflavin' => null,
							'niacin' => null,
							'vitamin_b6' => null,
							'folate' => null,
							'vitamin_b12' => null,
							'biotin' => null,
							'pantothenate' => null,
							'phosphorus' => null,
							'iodine' => null,
							'magnesium' => null,
							'zinc' => null,
							'selenium' => null,
							'copper' => null,
							'manganese' => null,
							'chromium' => null,
							'molybdenum' => null,
							'chloride' => null,
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'food',
						'attributes' => [
							'name' => 'Coconut',
							'slug' => 'coconut',
							'serving_size' => 2,
							'serving_units' => null,
							'front_image' => null,
							'info_image' => null,
							'calories' => null,
							'fat' => null,
							'saturated_fat' => null,
							'trans_fat' => null,
							'polyunsaturated_fat' => null,
							'omega_6' => null,
							'omega_3' => null,
							'monounsaturated_fat' => null,
							'cholesterol' => null,
							'sodium' => null,
							'potassium' => null,
							'carbohydrate' => null,
							'fibre' => null,
							'sugars' => null,
							'protein' => null,
							'vitamin_a' => null,
							'vitamin_c' => null,
							'calcium' => null,
							'iron' => null,
							'vitamin_d' => null,
							'vitamin_e' => null,
							'vitamin_k' => null,
							'thiamin' => null,
							'riboflavin' => null,
							'niacin' => null,
							'vitamin_b6' => null,
							'folate' => null,
							'vitamin_b12' => null,
							'biotin' => null,
							'pantothenate' => null,
							'phosphorus' => null,
							'iodine' => null,
							'magnesium' => null,
							'zinc' => null,
							'selenium' => null,
							'copper' => null,
							'manganese' => null,
							'chromium' => null,
							'molybdenum' => null,
							'chloride' => null,
							'is_favourite' => false,
							'is_verified' => false,
							'deleteable' => true,
						],
					],
				],
				'code' => 201,
			]],
		];
	}

	/**
	 * @dataProvider storeProvider
	 */
	public function testStore(array $args) : void
	{
		$response = $this->actingAs($this->user)->json('POST', $this->path, $args['body']);
		if (!empty($response['data']['id'])) {
			$args['response'] = $this->replaceToken('%id%', $response['data']['id'], $args['response']);
		}
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function showProvider() : array
	{
		return [
			[[
				'key' => 'food',
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'food',
						'attributes' => [
							'name' => 'Apple',
							'slug' => 'apple',
							'serving_size' => 1.5,
							'serving_units' => null,
							'front_image' => null,
							'info_image' => null,
							'calories' => null,
							'fat' => null,
							'saturated_fat' => null,
							'trans_fat' => null,
							'polyunsaturated_fat' => null,
							'omega_6' => null,
							'omega_3' => null,
							'monounsaturated_fat' => null,
							'cholesterol' => null,
							'sodium' => null,
							'potassium' => null,
							'carbohydrate' => null,
							'fibre' => null,
							'sugars' => null,
							'protein' => null,
							'vitamin_a' => null,
							'vitamin_c' => null,
							'calcium' => null,
							'iron' => null,
							'vitamin_d' => null,
							'vitamin_e' => null,
							'vitamin_k' => null,
							'thiamin' => null,
							'riboflavin' => null,
							'niacin' => null,
							'vitamin_b6' => null,
							'folate' => null,
							'vitamin_b12' => null,
							'biotin' => null,
							'pantothenate' => null,
							'phosphorus' => null,
							'iodine' => null,
							'magnesium' => null,
							'zinc' => null,
							'selenium' => null,
							'copper' => null,
							'manganese' => null,
							'chromium' => null,
							'molybdenum' => null,
							'chloride' => null,
							'is_favourite' => false,
							'is_verified' => false,
							'deleteable' => true,
						],
					],
				],
				'code' => 200,
			]],
		];
	}

	/**
	 * @dataProvider showProvider
	 */
	public function testShow(array $args) : void
	{
		$args['response'] = $this->replaceToken('%id%', (string) $this->food->id, $args['response']);
		$response = $this->actingAs($this->user)->json('GET', $this->path . '/' . $this->{$args['key']}->id);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function updateProvider() : array
	{
		return [
			[[
				'key' => 'food',
				'body' => [
					'data' => [
						'id' => '%id%',
						'type' => 'food',
						'attributes' => [
							'name' => 'Coconut',
							'slug' => 'coconut',
							'serving_size' => 2,
							'serving_units' => null,
							'front_image' => null,
							'info_image' => null,
							'calories' => null,
							'fat' => null,
							'saturated_fat' => null,
							'trans_fat' => null,
							'polyunsaturated_fat' => null,
							'omega_6' => null,
							'omega_3' => null,
							'monounsaturated_fat' => null,
							'cholesterol' => null,
							'sodium' => null,
							'potassium' => null,
							'carbohydrate' => null,
							'fibre' => null,
							'sugars' => null,
							'protein' => null,
							'vitamin_a' => null,
							'vitamin_c' => null,
							'calcium' => null,
							'iron' => null,
							'vitamin_d' => null,
							'vitamin_e' => null,
							'vitamin_k' => null,
							'thiamin' => null,
							'riboflavin' => null,
							'niacin' => null,
							'vitamin_b6' => null,
							'folate' => null,
							'vitamin_b12' => null,
							'biotin' => null,
							'pantothenate' => null,
							'phosphorus' => null,
							'iodine' => null,
							'magnesium' => null,
							'zinc' => null,
							'selenium' => null,
							'copper' => null,
							'manganese' => null,
							'chromium' => null,
							'molybdenum' => null,
							'chloride' => null,
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'food',
						'attributes' => [
							'name' => 'Coconut',
							'slug' => 'coconut',
							'serving_size' => 2,
							'serving_units' => null,
							'front_image' => null,
							'info_image' => null,
							'calories' => null,
							'fat' => null,
							'saturated_fat' => null,
							'trans_fat' => null,
							'polyunsaturated_fat' => null,
							'omega_6' => null,
							'omega_3' => null,
							'monounsaturated_fat' => null,
							'cholesterol' => null,
							'sodium' => null,
							'potassium' => null,
							'carbohydrate' => null,
							'fibre' => null,
							'sugars' => null,
							'protein' => null,
							'vitamin_a' => null,
							'vitamin_c' => null,
							'calcium' => null,
							'iron' => null,
							'vitamin_d' => null,
							'vitamin_e' => null,
							'vitamin_k' => null,
							'thiamin' => null,
							'riboflavin' => null,
							'niacin' => null,
							'vitamin_b6' => null,
							'folate' => null,
							'vitamin_b12' => null,
							'biotin' => null,
							'pantothenate' => null,
							'phosphorus' => null,
							'iodine' => null,
							'magnesium' => null,
							'zinc' => null,
							'selenium' => null,
							'copper' => null,
							'manganese' => null,
							'chromium' => null,
							'molybdenum' => null,
							'chloride' => null,
							'is_favourite' => false,
							'is_verified' => false,
							'deleteable' => true,
						],
					],
				],
				'code' => 200,
			]],
		];
	}

	/**
	 * @dataProvider updateProvider
	 */
	public function testUpdate(array $args) : void
	{
		$args['body'] = $this->replaceToken('%id%', (string) $this->food->id, $args['body']);
		$args['response'] = $this->replaceToken('%id%', (string) $this->food->id, $args['response']);
		$response = $this->actingAs($this->user)->json('PUT', $this->path . '/' . $this->{$args['key']}->id, $args['body']);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function destroyProvider() : array
	{
		return [
			[[
				'key' => 'food',
				'response' => null,
				'code' => 204,
			]],
		];
	}

	/**
	 * @dataProvider destroyProvider
	 */
	public function testDestroy(array $args) : void
	{
		$response = $this->actingAs($this->user)->json('DELETE', $this->path . '/' . $this->{$args['key']}->id);
		if ($args['response']) {
			$response->assertExactJson($args['response']);
			$response->assertStatus($args['code']);
		} else {
			$response->assertNoContent($args['code']);
		}
	}
}
