<?php

namespace App\Policies;

use App\Models\CategoryLimit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryLimitPolicy
{


    public function manage(User $user, CategoryLimit $limit = null): Response
    {
        // For create actions where $limit is null
        if ($limit === null) {
            return $user->team_id
                ? Response::allow()
                : Response::deny('You need to be in a team to manage limits.');
        }

        // For other actions
        return $user->team_id === $limit->team_id
            ? Response::allow()
            : Response::deny('You can only manage limits for your own team.');
    }


    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CategoryLimit $categoryLimit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CategoryLimit $categoryLimit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CategoryLimit $categoryLimit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CategoryLimit $categoryLimit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CategoryLimit $categoryLimit): bool
    {
        return false;
    }
}
