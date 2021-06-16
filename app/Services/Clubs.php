<?php

namespace App\Services;

use App\Models\Club;

class Clubs
{
    public function getClubById($id)
    {
        return Club::find($id);
    }

    public static function getClubKeyById($clubId) {
        $club = Club::find($clubId);
        return $club->apikey;
    }

    public static function getClubIdById($clubId) {
        $club = Club::find($clubId);
        return $club->club_id;
    }
}
