<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\lists\CityController;
use App\Http\Controllers\API\lists\MetroController;
use App\Models\User;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function getAdminData () {
//        $users = ;
        $cities = CityController::index();
        $metro = MetroController::index();
        return response(['ok',
//            'users' => $users,
            'cities' => $cities,
            'metro' => $metro
        ],200);
    }
}
