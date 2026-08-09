<?php

namespace App\Models;

use App\Models\FoodMeal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Jlbelanger\Tapioca\Traits\Resource;

class Meal extends Model
{
	use HasFactory, Resource, SoftDeletes;

	protected $fillable = [
		'user_id',
		'name',
		'is_favourite',
	];

	protected $casts = [
		'user_id' => 'integer',
		'is_favourite' => 'boolean',
	];

	public function defaultAttributes(array $data) : array // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
	{
		return [
			'user_id' => Auth::guard('sanctum')->id(),
		];
	}

	public function defaultFilter() : array
	{
		return [
			'user_id' => [
				'eq' => Auth::guard('sanctum')->id(),
			],
		];
	}

	public function foods() : HasMany
	{
		return $this->hasMany(FoodMeal::class, 'meal_id');
	}

	public function multiRelationships() : array
	{
		return ['foods'];
	}

	public function rules() : array
	{
		return [
			'data.attributes.name' => [$this->requiredOnCreate(), 'max:255'],
			'data.attributes.is_favourite' => ['boolean'],
		];
	}

	public function singularRelationships() : array
	{
		return ['user'];
	}

	public function user() : BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
