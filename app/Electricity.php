<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Electricity extends Model
{
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
