<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
{
	use HandlesAuthorization;

	public function view(User $currentUser, Meal $meal) : bool
	{
		return $meal->user_id === $currentUser->id;
	}

	public function create(User $currentUser, Meal $meal) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	public function delete(User $currentUser, Meal $meal) : bool
	{
		return $this->view($currentUser, $meal);
	}

	public function update(User $currentUser, Meal $meal) : bool
	{
		return $this->view($currentUser, $meal);
	}

	public function viewAny(User $currentUser, Meal $meal) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
