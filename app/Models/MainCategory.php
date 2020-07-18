<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{

    protected $table='main_categories';
    protected $fillable = [
        'translation_lang', 'translation_of', 'name','slug','photo','active','created_at','updated_at'
    ];


function scopeActive($query){


    return $query->where('active',1);


} // end of active

function scopeSelection($query){

    return $query->select("id","name","translation_lang","slug","photo","active");
}// end of selecton


public function getPhotoAttribute($val){


    return ($val !=null) ? asset('assets/' .$val) : '';
}


public function getActive(){

    return $this->active == 1 ?'مفعل':'غير مفعل ';

}// end of active


public function categories(){

   return $this->hasMany(self::class,'translation_of');

}//end of categories

}
