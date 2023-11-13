<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoleMaster extends Model
{
    protected $table = 'user_role_master';

    protected $fillable = ['role_name','short_code','is_active','created_at','updated_at'];

    /**
    * Defined ORM Has One Relationship between User role master & Users table
    * @param Foreign : role_id
    * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function user_roles() {
        return $this->hasOne('App\User','role_id','id');
    }
}
