<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'province_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['user_roles', 'access_cities'];

    /**
     * @return array
     */
    public function getUserRolesAttribute() : array
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->name;
        }

        return $roles;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cities() : BelongsToMany
    {
        return $this->belongsToMany(City::class, 'user_city');
    }

    public function questionnaire() : BelongsTo
    {
        return $this->belongsTo(Questionnaire::class, 'user_id', 'id');
    }

    public function getAccessCitiesAttribute()
    {
        return $this->cities->pluck('id');
    }
}
