<?php

namespace App\Policies;

use App\Models\Field;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FieldPolicy
{

    public function beforeAll(User $user, Field $field)
    {
        return !$user->grounded && ($user->role_name === 'editor' || $user->role_name === 'admin');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role_name === 'editor' || $user->role_name === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Field $field): bool
    {
        return $user->role_name === 'editor' || $user->role_name === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role_name === 'editor' || $user->role_name === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Field $field): bool
    {
        return $user->role_name === 'editor' || $user->role_name === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Field $field): bool
    {
        return $user->role_name === 'editor' || $user->role_name === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Field $field): bool
    {
        return $user->role_name === 'editor' || $user->role_name === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Field $field): bool
    {
        return $user->role_name === 'editor' || $user->role_name === 'admin';
    }
}
