<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WhishList;
use Illuminate\Auth\Access\HandlesAuthorization;

class WhishListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the whishList can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list whishlists');
    }

    /**
     * Determine whether the whishList can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\WhishList  $model
     * @return mixed
     */
    public function view(User $user, WhishList $model)
    {
        return $user->hasPermissionTo('view whishlists');
    }

    /**
     * Determine whether the whishList can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create whishlists');
    }

    /**
     * Determine whether the whishList can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\WhishList  $model
     * @return mixed
     */
    public function update(User $user, WhishList $model)
    {
        return $user->hasPermissionTo('update whishlists');
    }

    /**
     * Determine whether the whishList can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\WhishList  $model
     * @return mixed
     */
    public function delete(User $user, WhishList $model)
    {
        return $user->hasPermissionTo('delete whishlists');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\WhishList  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete whishlists');
    }

    /**
     * Determine whether the whishList can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\WhishList  $model
     * @return mixed
     */
    public function restore(User $user, WhishList $model)
    {
        return false;
    }

    /**
     * Determine whether the whishList can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\WhishList  $model
     * @return mixed
     */
    public function forceDelete(User $user, WhishList $model)
    {
        return false;
    }
}
