<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles;
    use Notifiable;
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'email', 'password', 'phone_number'];

    protected $searchableFields = ['*'];

    protected $hidden = ['password'];

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public function canAccessFilament(): bool
    {
        return true;
    }
}
