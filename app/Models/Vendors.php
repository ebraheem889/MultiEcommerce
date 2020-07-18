<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{

    protected $table='vendors';
    protected $fillable = [
         'name', 'mobile','address','email','category_id','active','logo','created_at','updated_at'
    ];


   
}
