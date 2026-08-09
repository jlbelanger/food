<?php

namespace App\Policies;

use App\Models\Trackable;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrackablePolicy
{
	use HandlesAuthorization;

	public function view(User $currentUser, Trackable $trackable) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	public function create(User $currentUser, Trackable $trackable) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return !empty($currentUser->is_admin);
	}

	public function delete(User $currentUser, Trackable $trackable) : bool
	{
		return $this->view($currentUser, $trackable);
	}

	public function update(User $currentUser, Trackable $trackable) : bool
	{
		return $this->view($currentUser, $trackable);
	}

	public function viewAny(User $currentUser, Trackable $trackable) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
