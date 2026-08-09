<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
	use HandlesAuthorization;

	public function view(User $currentUser, User $user) : bool
	{
		return $currentUser->id === $user->id;
	}

	public function create(User $currentUser, User $user) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return false;
	}

	public function delete(User $currentUser, User $user) : bool
	{
		return $this->view($currentUser, $user) && $currentUser->username !== 'demo';
	}

	public function update(User $currentUser, User $user) : bool
	{
		return $this->view($currentUser, $user);
	}

	public function viewAny(User $currentUser, User $user) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return false;
	}
}
