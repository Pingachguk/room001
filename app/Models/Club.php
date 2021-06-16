<?php

namespace App\Models;

use App\Http\Traits\DBTrait;
use App\Models\lists\City;
use App\Models\lists\Filial;
use App\Models\lists\GK;
use App\Models\lists\Metro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
    use DBTrait;
    protected $fillable = [
        'name',
        'city_id',
        'metro_id',
        'gk_id',
        'filial_id',
        'address',
        'club_id',
        'apikey',
    ];

    protected $hidden = array('pivot');
}
