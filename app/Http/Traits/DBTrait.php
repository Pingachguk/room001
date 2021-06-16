<?php

namespace App\Http\Traits;

use App\Models\Club;
use App\Models\lists\Filial;
use App\Models\lists\GK;
use App\Models\lists\Metro;
use Illuminate\Database\Eloquent\Model;
use App\Models\lists\City;

trait DBTrait {
    public function city () {
        return $this->belongsTo(City::class)->select(['id', 'name']);
    }

    public function clubs () {
        return $this->belongsToMany(Club::class, 'club-metro')->select(['id', 'name']);
    }

    public function metros () {
        return $this->belongsToMany(Metro::class, 'club-metro')->select(['id', 'name']);
    }

    public function gk () {
        return $this->belongsTo(GK::class)->select(['id', 'name']);
    }

    public function filial () {
        return $this->belongsTo(Filial::class)->select(['id', 'name']);
    }
}
