<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $parentRegion = $request->parent_id ?: 0;

        $regions =  Region::where('parent_id', $parentRegion)
            ->with('subregions')
            ->get();

        return view('regions', compact('regions'));
    }
}
