<?php

namespace App\Http\Controllers\API\lists;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataController;
use App\Http\Traits\DBTrait;
use App\Models\lists\Metro;
use Illuminate\Http\Request;

class MetroController extends Controller
{
    use DBTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index()
    {
        $metro = new Metro();
//        return $metro->orderBy('name')->with(?????city($metro))->get();

        return Metro::orderBy('name')->with('city')->get();
//        return Metro::orderBy('name')->with(DBTrait::city())->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Metro::create([
                'name' => $request->input('metroName'),
                'city_id' => $request->input('cityId'),
            ]
        );
        return response(DataController::getAdminData(),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        info('show');
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Metro::where('id', $id)
            ->update([
                'name' => $request->input('metroName'),
                'city_id' => $request->input('cityId'),
            ]);
        return response(DataController::getAdminData(),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Metro::destroy($id);
        return response(DataController::getAdminData(),200);
    }
}
