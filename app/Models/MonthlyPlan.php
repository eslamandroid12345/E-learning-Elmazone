<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyPlan extends Model
{
    use HasFactory;

    protected $fillable = ['background_color','title_ar','title_en','description_ar','description_en','start','end','season_id','term_id'];

    public function term(){

        return $this->belongsTo(Term::class,'term_id','id');
    }

}
