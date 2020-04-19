<?php

namespace App\Http\Controllers;

use App\Electricity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ElectricityController extends Controller
{
    public function index(Request $request)
    {
        return $this->get($request);
    }

    public function get(Request $request)
    {
        $electricity = Electricity::with('region.parent');

        $this->handleDateFilters($electricity, $request);

        if ($request->has('region_id')) {
            $electricity->where('region_id', $request->region_id);
        }

        $tooBigResult = $electricity->count() > 30;

        if($tooBigResult){
            return $electricity->paginate(30)->appends($request->all());
        }

        return $electricity->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_id' => 'required',
            'hours' => 'required',
            'date' => 'required|date_format:Y-m-d',
        ]);

        Electricity::create($request->all());

        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        $electricityToUpdate = Electricity::find($id);

        if(!$electricityToUpdate){
            return redirect()->back()->withErrors('Подобной записи нет');
        }

        if($request->has('hours')){
            $electricityToUpdate->hours = $request->hours;
        }

        if($request->has('comment')){
            $electricityToUpdate->comment = $request->comment;
        }

        $electricityToUpdate->save();

        return redirect()->back();
    }

    public function delete($id)
    {
        $electricityToDelete = Electricity::find($id);

        if(!$electricityToDelete){
            return redirect()->back()->withErrors('Подобной записи нет');
        }

        $electricityToDelete->delete();

        return redirect()->back();
    }

    /**
     * Helpers
     * 
     */
    private function handleDateFilters($query, Request $request)
    {
        $requestDateFrom = $request->date_from;
        $requestDateTo = $request->date_to;

        if($requestDateFrom || $requestDateTo){
            
            $query->where('date', '>=', $requestDateFrom ?: '2012-02-12');
            $query->where('date', '<=', $requestDateTo ?: Carbon::today()->toDateString());

            return;
        } 

        if ($request->has('date')){
            $query->where('date', $request->date);
        }   
    }
}
