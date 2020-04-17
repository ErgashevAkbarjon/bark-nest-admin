<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outage extends Model
{
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
