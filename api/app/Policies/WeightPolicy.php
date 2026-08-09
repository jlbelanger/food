<?php

namespace App\Policies;

use App\Models\Weight;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeightPolicy
{
	use HandlesAuthorization;

	public function view(User $currentUser, Weight $weight) : bool
	{
		return $weight->user_id === $currentUser->id;
	}

	public function create(User $currentUser, Weight $weight) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	public function delete(User $currentUser, Weight $weight) : bool
	{
		return $this->view($currentUser, $weight);
	}

	public function update(User $currentUser, Weight $weight) : bool
	{
		return $this->view($currentUser, $weight);
	}

	public function viewAny(User $currentUser, Weight $weight) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
