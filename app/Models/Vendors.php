<?php

namespace App\Models;

use App\Models\MainCategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Vendors extends Model 
{

    use Notifiable;
  

    protected $table='vendors';

    protected $fillable = [
         'name', 'mobile','password','address','email','category_id','active','logo','created_at','updated_at'
    ];



    public function scopeActive($query){

            return $query->where('active', 1 );

    }// end of active 

    public function getlogoAttribute($val){

        return ($val !=null) ? asset('assets/' .$val) : '';
 
    }// end of logo

    public function scopeSelection($query){


        return $query->select('id','name','address','email','category_id','active','logo','mobile');

    }//end of selection 

    public function category(){


        return $this->belongsTo('App\Models\MainCategory' ,'category_id', 'id' );
    
    } // end of category    

    public function getActive(){

        return $this->active == 1 ?'مفعل':'غير مفعل ';
    
    }// end of active
    
   public function setpasswordAttribute($password){


            if(!empty($password)){

                $this->attributes['password'] = bcrypt($password) ;

            }

   }
}
