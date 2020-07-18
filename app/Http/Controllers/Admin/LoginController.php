<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{




    public function getlogin1(){

        return view('admin.Auth.login');

    }//end of login1 function


    public function login(LoginRequest $request){

            $remember_me = $request->has('remember_me') ? true: false ;

            if (auth()->guard('admin')->attempt(['email'=>$request->input('email'),'password'=>$request->input('password')])){

                return redirect()->route('admin.dashboard');
            }//end of if
            else{

                return redirect()->back()->with(['error' =>'هناك خطأ ما']);
            }
    }//end of login
}
