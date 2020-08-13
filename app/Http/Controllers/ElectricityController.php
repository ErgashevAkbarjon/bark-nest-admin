<?php

namespace App\Http\Controllers;

use App\Electricity;
use App\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDO;

class ElectricityController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::where('parent_id', 0)
            ->with('subregions')
            ->get();

        return view('electricity.index', compact(['regions']));
    }

    public function table(Request $request)
    {
        $regions = Region::where('parent_id', 0)
            ->with('subregions')
            ->get();

        $acceptedLanguages = ["ru", "tj", "en"];

        $language = $acceptedLanguages[0];

        if ($request->has('lang') && in_array($request->lang, $acceptedLanguages)) {
            $language = $request->lang;
        }

        return view('electricity.table', compact(['regions', 'language']));
    }

    public function get(Request $request)
    {
        $electricity = Electricity::query();

        $this->handleDateFilters($electricity, $request);

        if ($request->has('region_id')) {
            $electricity->whereIn('region_id', explode(",", $request->region_id));
        }

        if ($request->has('last')){
            $lastAddedDate = Electricity::orderByDesc('date')->first()->date;

            $electricity->where('date', $lastAddedDate);
        }

        $electricity->with('region.parent');

        $tooBigResult = $electricity->count() > 30;

        if ($tooBigResult) {
            return $electricity->paginate(30)->appends($request->all());
        }

        return $electricity->get();
    }

    public function tableData(Request $request)
    {
        $electricity = Electricity::query();

        $this->handleDateFilters($electricity, $request);

        if ($request->has('region_id')) {
            $electricity->whereIn('region_id', explode(",", $request->region_id));
        }

        $grouped = $electricity
            ->get(['region_id', 'date', 'hours', 'day_period', 'night_period'])
            ->mapToGroups(function ($item, $key){
                return [
                    $item['date'] => [ 
                        'r' => $item['region_id'],
                        'h' => $item['hours'],
                        'd' => $item['day_period'],
                        'n' => $item['night_period']
                    ]
                ];
            });
        
        $flattenedGroup = [];

        foreach ($grouped->toArray() as $date => $regionData) {
            $group = ['date' => $date];
            
            foreach ($regionData as $region) {
                $group[$region['r']] = [$region['h'], $region['d'], $region['n']];
            }

            $flattenedGroup[] = $group;
        }

        return $flattenedGroup;
    }

    public function create()
    {
        $regions = Region::where('parent_id', 0)
            ->with('subregions')
            ->get();

        return view('electricity.create', compact(['regions']));
    }

    public function store(Request $request)
    {
        if (!$request->has('regionData')) {
            $request->validate([
                'region_id' => 'required',
                'hours' => 'required',
                'date' => 'required|date_format:Y-m-d',
            ]);

            Electricity::create($request->all());

            return redirect()->back();
        }

        $request->validate([
            'regionData.*.region_id' => 'required',
            'regionData.*.hours' => 'required',
            'regionData.*.date' => 'required|date_format:Y-m-d',
        ]);

        foreach ($request->regionData as $data) {
            Electricity::create($data);
        }

        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        $electricityToUpdate = Electricity::find($id);

        if (!$electricityToUpdate) {
            return redirect()->back()->withErrors('Подобной записи нет');
        }

        if ($request->has('hours')) {
            $electricityToUpdate->hours = $request->hours;
        }

        if ($request->has('comment')) {
            $electricityToUpdate->comment = $request->comment;
        }

        $electricityToUpdate->save();

        return redirect()->back();
    }

    public function delete($id)
    {
        $electricityToDelete = Electricity::find($id);

        if (!$electricityToDelete) {
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

        if ($requestDateFrom || $requestDateTo) {

            $query->where('date', '>=', $requestDateFrom ?: '2012-02-12');
            $query->where('date', '<=', $requestDateTo ?: Carbon::today()->toDateString());

            return;
        }

        if ($request->has('date')) {
            $query->where('date', $request->date);
        }
    }

    private function paginate($items, $perPage = 5, $page = null)
    {
        $options = [
            'path' => url()->full(),
        ];

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }
}
