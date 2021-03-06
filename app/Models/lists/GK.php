<?php

namespace App\Models\lists;

use App\Http\Traits\DBTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GK extends Model
{
    use HasFactory;
    use DBTrait;
    protected $fillable = [
        'name',
        'city_id',
    ];

//    public function city() {
//        return $this->belongsToCity($this);
//    }
}
