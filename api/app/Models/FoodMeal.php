<?php

namespace App\Models;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;
use Jlbelanger\Tapioca\Traits\Resource;

class FoodMeal extends Model
{
	use HasFactory, Resource;

	protected $table = 'food_meal';

	protected $fillable = [
		'food_id',
		'meal_id',
		'user_serving_size',
	];

	protected $casts = [
		'food_id' => 'integer',
		'meal_id' => 'integer',
		'user_serving_size' => 'float',
	];

	public function food() : BelongsTo
	{
		return $this->belongsTo(Food::class);
	}

	public function meal() : BelongsTo
	{
		return $this->belongsTo(Meal::class);
	}

	public function rules() : array
	{
		$rules = [
			'data.relationships.food' => [$this->requiredOnCreate()],
			'data.relationships.meal' => [$this->requiredOnCreate()],
			'data.attributes.user_serving_size' => [$this->requiredOnCreate(), 'numeric'],
		];

		$mealId = request('data.relationships.meal.data.id', $this->meal_id);
		$unique = Rule::unique($this->getTable(), 'food_id')->where(function ($query) use ($mealId) {
			return $query->where('meal_id', '=', $mealId);
		});
		if ($this->id) {
			$unique->ignore($this->id);
		}
		$rules['data.relationships.food'][] = $unique;

		return $rules;
	}

	public function singularRelationships() : array
	{
		return ['food', 'meal'];
	}
}
