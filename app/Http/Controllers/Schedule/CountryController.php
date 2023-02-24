<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.country.index'), 'name'=>"Countries"], ['name'=>"list of Countries"]
        ];
        if (request()->ajax()) {
            $countries = Country::query();
            return Datatables::eloquent($countries)
            ->addColumn('action', function(Country $country) {
                            return Utilities::actionButtons([['route' => route('settings.country.edit', $country->id), 'name' => 'Edit'],
                                                             ['route' => route('settings.country.destroy', $country->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this country ' . $country->name . '?', 'text' => 'Delete']
                                                            ]);
                        })
            ->editColumn('updated_at', function (Country $country) {
                return $country->updated_at->diffForHumans() . ' | ' . $country->updatedByName;
            })
            ->editColumn('created_at', function (Country $country) {
                return $country->created_at->format('M d, Y') . ' | ' . $country->createdByName;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('app.setting.country.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.country.index'), 'name'=>"Countries"], ['name'=>"Create New Country"]
        ];
        return view('app.setting.country.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'unique:country,name,{id},id,deleted_at,NULL'],
            'timezone' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $country = Country::create($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Country added successfully!',
                        'redirect' => route('settings.country.index'),
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('app.setting.country.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'unique:country,name,' . $country->id . ',id,deleted_at,NULL'],
            'timezone' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $country = $country->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Country updated successfully!',
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        try {
            DB::beginTransaction();
            $country = $country->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Country deleted successfully!',
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }
}
