<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar','name_en'];

    public function countries(){

        return $this->hasMany(Country::class,'city_id','id');
    }


}
