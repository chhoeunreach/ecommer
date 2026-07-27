<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy for phone library administration.
 */
class PhoneLibraryPolicy
{
    /**
     * Determine whether the user can view the library.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('phone_library.view');
    }

    /**
     * Determine whether the user can create library records.
     */
    public function create(User $user): bool
    {
        return $user->can('phone_library.create');
    }

    /**
     * Determine whether the user can update library records.
     */
    public function update(User $user): bool
    {
        return $user->can('phone_library.edit');
    }

    /**
     * Determine whether the user can delete library records.
     */
    public function delete(User $user): bool
    {
        return $user->can('phone_library.delete');
    }
}
