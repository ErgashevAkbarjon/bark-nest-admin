<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Electricity extends Model
{
    protected $guarded = [];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
