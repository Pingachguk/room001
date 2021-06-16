<?php

namespace App\Http\Controllers\API\lists;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataController;
use App\Models\lists\Filial;
use Illuminate\Http\Request;

class FilialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index()
    {
        return Filial::orderBy('name')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Filial::create([
                'name' => $request->input('filialName'),
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
        $data = $request->input('filialName');
        info($data);
        Filial::where('id', $id)
            ->update([
                'name' => $request->input('filialName'),
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
        Filial::destroy($id);
        return response(DataController::getAdminData(),200);
    }
}
