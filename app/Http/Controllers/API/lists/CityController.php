<?php

namespace App\Http\Controllers\API\lists;

use App\Http\Controllers\Controller;
use App\Models\lists\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index()
    {
        return City::orderBy('name')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        City::create([
                'name' => $request->input('cityName'),
            ]
        );
        return response(['cities' => $this->index()],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        info('show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->input('cityName');
        info($data);
        City::where('id', $id)
            ->update([
                'name' => $request->input('cityName'),
                ]);
        return response(['cities' => $this->index()],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        City::destroy($id);
        return response(['cities' => $this->index()],200);
    }
}
