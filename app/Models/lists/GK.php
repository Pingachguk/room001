<?php

namespace App\Models\lists;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GK extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'city_id',
    ];

    public function city() {
//        return $this->hasOne(City::class, 'city_id');
        return $this->belongsTo(City::class)->select(['id', 'name']);
    }
}
