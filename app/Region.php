<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function outages()
    {
        return $this->hasMany(Outage::class);
    }

    public function type()
    {
        return $this->belongsTo(RegionType::class, 'region_type_id');
    }

    public function subregions()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }
}
