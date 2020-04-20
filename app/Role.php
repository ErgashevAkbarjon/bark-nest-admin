<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ADMIN_ROLE_NAME = 'Админ';
    const OPERATOR_ROLE_NAME = 'Оператор';

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
