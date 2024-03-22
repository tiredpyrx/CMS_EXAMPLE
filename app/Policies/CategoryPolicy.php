<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role_name === 'admin' || $user->role_name === 'editor';
    }

    public function view(User $user, Category $category): bool
    {
        return $user->role_name !== 'observer';
    }

    public function create(User $user): bool
    {
        return $user->role_name === 'admin' || $user->role === 'editor';
    }

    public function update(User $user, Category $category): bool
    {
        return $user->role_name === 'admin' || $user->role === 'editor';
    }

    public function delete(User $user, Category $category): bool
    {
        if ($category->required) {
            return $user->role_name === 'admin';
        }
        return $user->role_name === 'admin' || $user->role === 'editor';
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->role_name === 'admin' || $user->role === 'editor';
    }

    public function forceDelete(User $user, Category $category): bool
    {
        if ($category->allow_force_deletion) {
            return $user->role_name === 'admin' || $user->role === 'editor';
        }

        return $user->role_name === 'admin';
    }
}
