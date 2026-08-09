<?php

namespace App\Policies;

use App\Models\Food;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodPolicy
{
	use HandlesAuthorization;

	public function view(User $currentUser, Food $food) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	public function create(User $currentUser, Food $food) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	public function delete(User $currentUser, Food $food) : bool
	{
		return $this->view($currentUser, $food) && $food->getDeleteableAttribute();
	}

	public function update(User $currentUser, Food $food) : bool
	{
		return $this->view($currentUser, $food);
	}

	public function viewAny(User $currentUser, Food $food) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
