<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataController;
use App\Http\Traits\DBTrait;
use App\Models\Club;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClubController extends Controller
{
    use DBTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index()
    {
        return Club::orderBy('name')
            ->with('city', 'gk', 'filial', 'metros')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        Club::create([
//                'name' => $request->input('clubName'),
//                'city_id' => $request->input('cityId'),
//                'gk_id' => $request->input('gkId'),
//                'filial_id' => $request->input('filialId'),
//                'address' => $request->input('clubAddress'),
//                'club_id' => $request->input('clubId'),
//                'apikey' => $request->input('apikey'),
//            ]
//        );
        $club = new Club();
        $club->name = $request->input('clubName');
        $club->city_id = $request->input('cityId');
        $club->gk_id = $request->input('gkId');
        $club->filial_id = $request->input('filialId');
        $club->address = $request->input('clubAddress');
        $club->club_id = $request->input('clubId');
        $club->apikey = $request->input('apikey');
        $metroIds = $request->input('metroIds');

        $club->save();
        $club->metros()->attach($metroIds);

//        DB::transaction(function ($club, $metroIds) {
//
//        });
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
        $club = Club::find($id);

        $club->name = $request->input('clubName');
        $club->city_id = $request->input('cityId');
        $club->gk_id = $request->input('gkId');
        $club->filial_id = $request->input('filialId');
        $club->address = $request->input('clubAddress');
        $club->club_id = $request->input('clubId');
        $club->apikey = $request->input('apikey');
        $metroIds = $request->input('metroIds');

        $club->save();
        $club->metros()->detach();
        $club->metros()->attach($metroIds);


//        Club::where('id', $id)
//            ->update([
//                'name' => $request->input('metroName'),
//                'city_id' => $request->input('cityId'),
//            ]);
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
        $club = Club::find($id);
        $club->metros()->detach();
        $club->destroy($id);
        return response(DataController::getAdminData(),200);
    }
}
