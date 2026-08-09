<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
	use HandlesAuthorization;

	public function view(User $currentUser, Entry $entry) : bool
	{
		return $entry->user_id === $currentUser->id;
	}

	public function create(User $currentUser, Entry $entry) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	public function delete(User $currentUser, Entry $entry) : bool
	{
		return $this->view($currentUser, $entry);
	}

	public function update(User $currentUser, Entry $entry) : bool
	{
		return $this->view($currentUser, $entry);
	}

	public function viewAny(User $currentUser, Entry $entry) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
