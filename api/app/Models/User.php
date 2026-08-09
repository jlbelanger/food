<?php

namespace App\Models;

use App\Models\Food;
use App\Models\Trackable;
use App\Models\Weight;
use DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Jlbelanger\Tapioca\Traits\Resource;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable, Resource, SoftDeletes;

	protected $fillable = [
		'username',
		'email',
		'password',
		'sex',
		'age',
		'height',
		'activity_level',
		'measurement_units',
		'favourites_only',
		'is_admin',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'age' => 'integer',
		'height' => 'integer',
		'activity_level' => 'integer',
		'favourites_only' => 'boolean',
		'is_admin' => 'boolean',
		'email_verified_at' => 'datetime',
	];

	public function additionalAttributes() : array
	{
		return ['weight', 'weight_date'];
	}

	public function clearFavouritesCache() : void
	{
		if (config('cache.enable')) {
			Cache::forget('favourites_' . $this->id);
		}
	}

	public function favourites() : BelongsToMany
	{
		return $this->belongsToMany(Food::class);
	}

	public function favouriteFoodIds() : array
	{
		if (config('cache.enable')) {
			return Cache::remember('favourites_' . $this->id, 3600, function () {
				return $this->favourites()->pluck('food_id')->toArray();
			});
		}
		return $this->favourites()->pluck('food_id')->toArray();
	}

	public function getAuthInfo(bool $remember) : array
	{
		return [
			'id' => $this->getKey(),
			'is_admin' => $this->is_admin,
			'measurement_units' => $this->measurement_units,
			'remember' => $remember,
			'trackables' => $this->trackables()->orderBy('trackables.id')->pluck('trackables.slug')->toArray(),
		];
	}

	public function getDataByDate(Collection $trackables, ?int $year = null) : array
	{
		$select = ['entries.date'];
		foreach ($trackables as $trackable) {
			$cleanSlug = preg_replace('/[^a-z0-9_]+/', '', $trackable->slug);
			$select[] = DB::raw('SUM(ROUND((entries.user_serving_size / food.serving_size) * food.`' . $cleanSlug . '`)) AS ' . $cleanSlug);
		}

		$data = DB::table('entries')
			->select($select)
			->join('food', 'entries.food_id', 'food.id')
			->where('entries.user_id', '=', $this->id);
		if ($year) {
			$data = $data->where('entries.date', 'LIKE', $year . '-%');
		}
		$data = $data->whereNull('entries.deleted_at')
			->groupBy('entries.date')
			->get();

		$output = [];
		foreach ($data as $d) {
			$output[$d->date] = $d;
		}

		$weights = DB::table('weights')
			->select(['weight', 'date'])
			->where('user_id', '=', $this->id);
		if ($year) {
			$weights = $weights->where('date', 'LIKE', $year . '-%');
		}
		$weights = $weights->get();
		foreach ($weights as $weight) {
			if (empty($output[$weight->date])) {
				$output[$weight->date] = new \stdClass;
				$output[$weight->date]->date = $weight->date;
			}
			$output[$weight->date]->weight = $weight->weight;
		}

		return $output;
	}

	public function getWeightAttribute() : ?Weight
	{
		return $this->weights()->select(['date', 'weight'])->orderBy('date', 'desc')->first();
	}

	public function multiRelationships() : array
	{
		return ['trackables'];
	}

	public function rules() : array
	{
		$rules = [
			'data.attributes.username' => [$this->requiredOnCreate(), 'alpha_num', 'max:255', $this->unique('username')],
			'data.attributes.email' => ['prohibited'],
			'data.attributes.password' => ['prohibited'],
			'data.attributes.sex' => ['nullable', Rule::in(['f', 'm'])],
			'data.attributes.age' => ['nullable', 'integer'],
			'data.attributes.height' => ['nullable', 'integer'],
			'data.attributes.activity_level' => ['nullable', 'integer'],
			'data.attributes.measurement_units' => [Rule::in(['i', 'm'])],
			'data.attributes.favourites_only' => ['boolean'],
			'data.attributes.is_admin' => ['prohibited'],
		];

		if (Auth::guard('sanctum')->user()->username === 'demo') {
			$rules['data.attributes.username'][] = 'prohibited';
		}

		return $rules;
	}

	public function trackables() : BelongsToMany
	{
		return $this->belongsToMany(Trackable::class);
	}

	public function weights() : HasMany
	{
		return $this->hasMany(Weight::class);
	}

	public function whitelistedAttributes() : array
	{
		return array_merge($this->fillable, ['password_confirmation']);
	}
}
