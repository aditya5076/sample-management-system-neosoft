<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','company','designation','role_id','is_active','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * Defined ORM Has One Relationship between Users table & User role master.
    * @param Foreign : id
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function roles() {
        return $this->hasOne('App\Models\UserRoleMaster','id','role_id');
}
}
