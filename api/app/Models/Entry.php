<?php

namespace App\Models;

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Jlbelanger\Tapioca\Traits\Resource;

class Entry extends Model
{
	use HasFactory, Resource, SoftDeletes;

	protected $fillable = [
		'food_id',
		'user_id',
		'user_serving_size',
		'date',
	];

	protected $casts = [
		'food_id' => 'integer',
		'user_id' => 'integer',
		'user_serving_size' => 'float',
	];

	public function defaultAttributes(array $data) : array // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
	{
		$foodId = !empty($data['attributes']['food_id']) ? $data['attributes']['food_id'] : null;
		return [
			'user_id' => Auth::guard('sanctum')->id(),
			'user_serving_size' => $foodId ? Food::find($foodId)->serving_size : 0,
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

	public function food() : BelongsTo
	{
		return $this->belongsTo(Food::class);
	}

	public function rules() : array
	{
		return [
			'data.relationships.food' => [$this->requiredOnCreate()],
			'data.attributes.date' => [$this->requiredOnCreate(), 'date'],
		];
	}

	public function singularRelationships() : array
	{
		return ['food', 'user'];
	}

	public function user() : BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
