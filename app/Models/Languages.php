<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{

    protected $table='languages';
    protected $fillable = [
        'abbr', 'locale', 'name','direction','active','created_at','updated_at'
    ];


    public function scopeActive($q){


        return $q->where('active',1);


    }//end of active function

    public function scopeSelection($query){


        return $query->select('id','abbr','name','direction','active');

    }// end of selection

public function getActive(){

        return $this->active == 1 ?'مفعل':'غير مفعل ';

}

}
