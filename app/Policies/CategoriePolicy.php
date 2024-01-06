<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Categorie;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the categorie can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list categories');
    }

    /**
     * Determine whether the categorie can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Categorie  $model
     * @return mixed
     */
    public function view(User $user, Categorie $model)
    {
        return $user->hasPermissionTo('view categories');
    }

    /**
     * Determine whether the categorie can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create categories');
    }

    /**
     * Determine whether the categorie can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Categorie  $model
     * @return mixed
     */
    public function update(User $user, Categorie $model)
    {
        return $user->hasPermissionTo('update categories');
    }

    /**
     * Determine whether the categorie can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Categorie  $model
     * @return mixed
     */
    public function delete(User $user, Categorie $model)
    {
        return $user->hasPermissionTo('delete categories');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Categorie  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete categories');
    }

    /**
     * Determine whether the categorie can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Categorie  $model
     * @return mixed
     */
    public function restore(User $user, Categorie $model)
    {
        return false;
    }

    /**
     * Determine whether the categorie can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Categorie  $model
     * @return mixed
     */
    public function forceDelete(User $user, Categorie $model)
    {
        return false;
    }
}
