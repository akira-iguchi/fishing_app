<?php

namespace App\Policies;

use App\Models\Spot;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpotPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->authorizeResource(Spot::class);
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spot  $spot
     * @return mixed
     */
    public function view(User $user, Spot $spot)
    {
        return $user->id == $spot->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spot  $spot
     * @return mixed
     */
    public function create(User $user, Spot $spot)
    {
        return $user->id == $spot->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spot  $spot
     * @return mixed
     */
    public function update(User $user, Spot $spot)
    {
        return $user->id === $spot->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Spot  $spot
     * @return mixed
     */
    public function delete(User $user, Spot $spot)
    {
        return $user->id === $spot->user_id;
    }
}
