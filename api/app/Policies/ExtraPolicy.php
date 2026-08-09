<?php

namespace App\Policies;

use App\Models\Extra;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExtraPolicy
{
	use HandlesAuthorization;

	public function view(User $currentUser, Extra $extra) : bool
	{
		return $extra->user_id === $currentUser->id;
	}

	public function create(User $currentUser, Extra $extra) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	public function delete(User $currentUser, Extra $extra) : bool
	{
		return $this->view($currentUser, $extra);
	}

	public function update(User $currentUser, Extra $extra) : bool
	{
		return $this->view($currentUser, $extra);
	}

	public function viewAny(User $currentUser, Extra $extra) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
