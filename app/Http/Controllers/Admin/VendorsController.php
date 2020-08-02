<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Vendors;
use  App\Models\MainCategory;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\VendorRequests ;
use  App\Notifications\VendorCreated;
use DB;

class VendorsController extends Controller
{

    public function index(){


        $vendors = Vendors::selection()->paginate(PAGINATION_COUNT);

        return view('admin.vendors.index' , compact('vendors'));

    }// end of index

    public function create(){


        $categories = MainCategory::where('translation_of' , 0 )->active()->get();
     
        return view('admin.vendors.create',compact('categories'));

    }// end of create 


    public function store(VendorRequests $request){

        try {

            $filepath = '' ;
            $filepath = uploadImage('vendors' ,$request->logo);
            // end of savong the image
    
            if(!$request->has('active')){
    
                $request->request->add(['active' => 0]);
    
            } // end of inner if
             else {
    
                    $request->request->add(['active' => 1]);
    
                }// end of  else
    
            $vendor = Vendors::create([
                'name' => $request->name ,
                'category_id' => $request->category_id ,
                'mobile' => $request->mobile ,
                'password' => $request->password ,
                'email' => $request->email ,
               'address' => $request->address ,
                'logo' => $filepath ,
                'active' => $request->active
                ]
            ); 

            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success'=>'تم الاضافة بنجاح']);
        } // end of try

        catch(\Exception $ex){

            return redirect()->route('admin.vendors')->with(['error'=>'تم الاضافة بنجاح']);
        }// end of catch 
    

    } // end of store

    public function edit($id){


        $vendor = Vendors::selection()->find($id);

     try{

        if(!$vendor){

            return redirect()->route('admin.vendors')->with(['error' => 'هذا التاجر غير موجود']);
        } // end of if   

        else {

            $categories = MainCategory::where('translation_of' , 0 )->active()->get();
            return view('admin.vendors.edit' , compact('vendor' , 'categories'));

        } // end of else 

     }// end of try 

     catch(\Exception $ex)

        {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما ']);

        }// end of catch 


    } //end of edit 


    public function update($id , VendorRequests $request){


     try{
        $vendor = Vendors::selection()->find($id);


       

        if(!$vendor){

            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما ']);

        }

        DB::beginTransaction();

        if($request->has('logo')){

            $filepath = uploadImage('vendors', $request->logo);

            Vendors::where('id'  , $id)->update([
                'logo' => $filepath , 
                ]);

        }// end of if

           if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


        $data = $request->except('_token' , 'password' , 'id' ,'logo');

        if($request->has('password') && !is_null($request->password)){

            Vendors::where('id' , $id )->update(
                [
                   'password' => bcrypt($request->password)
                
                ]
            );
        } // end  of if 

        Vendors::where('id' , $id)->update($data);
        DB::commit();

        return redirect()->route('admin.vendors') ->with(['success' => ' تم التعديل بنجاح']);
     } // end of try 

     catch(\Exception $ex){

        DB::rollback();
        
         return $ex;
        return redirect()->route('admin.vendors') ->with(['error' => ' حدث خطأ ما']);

     } // end of catch 


    } // end of update 


}
