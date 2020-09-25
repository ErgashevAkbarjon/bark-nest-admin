<?php

namespace App\Http\Controllers;

use App\Region;
use App\RegionType;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $parentRegion = $request->parent_id ?: 0;

        $regions =  Region::where('parent_id', $parentRegion)
            ->with(['type','subregions.type'])
            ->get();

        $regionTypes = RegionType::all();
        $parentRegions = Region::where('parent_id', 0)->get();

        return view('regions.index', compact(['regions', 'regionTypes', 'parentRegions']));
    }

    public function get(Request $request)
    {
        return Region::where('parent_id', 0)
            ->with(['type','subregions.type'])
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'region_type_id' => 'required'
        ]);

        Region::create($request->all());

        return redirect('/regions');
    }

    public function update($id, Request $request)
    {
        $regionToUpdate = Region::find($id);

        if (!$regionToUpdate) {
            return redirect()
                ->back()
                ->withErrors("Подобного региона не существует");
        }

        $regionToUpdate->update($request->all());

        return redirect('/regions');
    }
    
    public function delete($id)
    {
        $regionToDelete = Region::find($id);
        
        if (!$regionToDelete) {
            return redirect()
                ->back()
                ->withErrors("Подобного региона не существует");
        }

        $regionToDelete->delete();

        return redirect('/regions');
    }
}
