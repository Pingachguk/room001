<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\lists\CityController;
use App\Http\Controllers\API\lists\GKController;
use App\Http\Controllers\API\lists\MetroController;
//use App\Models\User;
//use Illuminate\Http\Request;

class DataController extends Controller
{
    public function loadData () {
        return $this->getAdminData();
    }

    public static function getAdminData () {
//        $users = ;
        $cities = CityController::index();
        $metro = MetroController::index();
        $gkList = GKController::index();
        return [
            'status' => 'ok',
//            'users' => $users,
            'cities' => $cities,
            'metro' => $metro,
            'gkList' => $gkList
        ];
    }
}
