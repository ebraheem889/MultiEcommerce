<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use App\Http\Requests\MainCategoriesRequest;
use Illuminate\Support\Str;
use DB;
class MainCategoriesController extends Controller
{
   
    public function index(){

            $default_lang = getDefaultLange(); 

            $Categories = MainCategory::Where("translation_lang", $default_lang )->selection()->get();
            return view('admin.maincategories.index',compact('Categories'));

    }// end of index function


    public function create(){


        return view('admin.maincategories.create');
     

    }// end of create

    public function store(MainCategoriesRequest $request){

     try{
                
                                $main_categories = collect($request->category);
                            
                                $filter =  $main_categories->filter( function($value , $key){
                        
                        
                                    return  $value['abbr'] == getDefaultLange();
                        
                                });
                        
                                $default_category = array_values($filter->all())[0];
                                
                                $filepath="";
                        
                                if($request->has('photo')){
                        
                                    $filepath = uploadImage('maincategories',$request->photo);
                        
                                } // end of if

                        
                            
                        DB::beginTransaction();
                                $default_category_id = MainCategory::insertGetId([
                        
                                    'translation_lang' => $default_category['abbr'],
                                    'translation_of' => 0 ,
                                    'name' => $default_category['name'],
                                    'slug'=>$default_category['name'],
                                    'photo' => $filepath
                                ]);
                        
                        
                                $Categories = $main_categories->filter( function($value , $key){
                        
                        
                                    return  $value['abbr'] != getDefaultLange();
                        
                            });
                        
                        
                            if(isset($Categories) && $Categories ->count()){
                        
                        
                                $Categories_arr = [];
                        
                                foreach($Categories as $Categorie){
                        
                                    $Categories_arr[] = [
                        
                                                'translation_lang' => $Categorie['abbr'],
                                                'translation_of' => $default_category_id ,
                                                'name' => $Categorie['name'],
                                                'slug'=>$Categorie['name'],
                                                'photo' => $filepath
                                    ];
                        
                        
                                } // end of foreach
                            
                        
                            MainCategory::insert($Categories_arr);
                        
                        
                            }// en of if

                            DB::commit();

                            return redirect()->route('admin.maincategories')-with(['success' => 'تم الحفظ بنجاح']);
            }// end of try
            catch(\Exception $ex){

                    DB::rollback();

                    return redirect()->route('admin.maincategories')->with(['success' => 'تم الاضافة بنجاح']);

            } // end of catch


       

    }// end of store


    public function edit($id){

        $category = MainCategory::with('categories')->selection()->find($id);

        if(!$category){

            return redirect()->route('admin.maincategories')->with(['error' =>'هذا القسم غير موجود']);


        }

        else {

                return view('admin.maincategories.edit',compact('category'));

        }

    
 

    }//  end of edit function

    public function update($id,MainCategoriesRequest $request){

    
        try{


            $category = MainCategory::selection()->find($id);

            if(!$category){
    
                return redirect()->route('admin.maincategories')->with(['error' =>'هذا القسم غير موجود']);
    
    
            } // end of if 
    
            else {
    
                
                $category = array_values($request->category)[0];

                if(!$request->has('category.0.active')){

                    $request->request->add(['active' => 0]);

                } // end of inner if
                 else {

                        $request->request->add(['active' => 1]);
    
                    }// end of inner else

            
                MainCategory::where('id'  ,$id)->update([
                    'name' => $category['name'] , 
                    'active' => $request->active 
                ]);
                    if($request->has('photo')){

                        $filepath = uploadImage('maincategories',$request->photo);

                        MainCategory::where('id'  ,$id)->update([
                            'photo' =>$filepath , 
                            ]);
                    }// end of inner if


                    return redirect()->route('admin.maincategories')->with(['success' => 'تم التعديل بنجاح ']);
    
            } // end of else 
    

        } // end of try 

         catch(\Exception $ex){


                 return redirect()->route('admin.maincategories')->with(['errorr' => 'هناك خطأ ما ']);



        } // end of catch
       


    } // end of update function

    public function destroy($id){

            try{


                $maincategory =  MainCategory::find($id);

                if(!$maincategory){

                    return redirect()->route('admin.maincategories')->with(['error'=>'هذه المتجر غير موجود ']);

                }// end of if

                $vendorsinCategory = $maincategory->vendors();

                if(isset($vendorsinCategory)&& $vendorsinCategory->count() > 0)

                    {
                        return redirect()->route('admin.maincategories')->with(['error' => 'لا يمكن حذف هذا المتجر ']);
                        
                    } // end of if
                    
                else{

                        $image = Str::after($maincategory->photo , 'assets/');

                        $folder = base_path('assets/'.$image);

                       unlink($folder);

                        $maincategory->delete();
                        return redirect()->route('admin.maincategories')->with(['success' => 'تم الحذف بنجاح ']);
    
                }

            }// end of try

            catch(\Exception $ex){



                return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما']);

            } // end of catch 

    } // end of update function


public function changeStatus($id){

        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

           $status =  $maincategory -> active  == 0 ? 1 : 0;

           $maincategory -> update(['active' =>$status ]);

            return redirect()->route('admin.maincategories')->with(['success' => ' تم تغيير الحالة بنجاح ']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
