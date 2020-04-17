<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionType extends Model
{
    public $timestamps = false;

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
