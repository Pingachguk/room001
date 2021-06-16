<?php

namespace App\Models\lists;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Traits\DBTrait;


class Metro extends Model
{
    use HasFactory;
    use DBTrait;

    protected $table = "metros";
    protected $fillable = [
        'name',
        'city_id',
    ];

//    public function city() {
//        return $this->belongsToCity($this);
//    }

//    public function clubs() {
//        return $this->belongsToManyClubs($this);
//    }
}
