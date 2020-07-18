<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Languages;
use Carbon\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{



    public function index(){


        $languages = Languages::selection()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index',compact('languages'));

    }// end of index function


    public function create(){

        return view('admin.languages.create');

    }// end of create

    public function store(LanguageRequest $request){

        try {
            Languages::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم الاضافة بنجاح']);
        }
        catch (\Exception $exception){

            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة أخري']);


        }

    }// end of store


    public function edit($id){

        $language =Languages::selection()->find($id);

        if (!$language){

            return redirect()->route('admin.languages')->with(['error'=>'هذه الصفحة غير موجودة']);
        } // end of if

        else {

            return view('admin.languages.edit',compact('language'));

        }// end of else

    }//  end of edit function
    public function update($id,LanguageRequest $request){

        $language =Languages::find($id);

        if (!$language){

            return redirect()->route('admin.languages',$id)->with(['error'=>'غير موجود']);
        } // end of if
        else{

            $language->update($request->except('_token'));
            return redirect()->route('admin.languages')->with(['success'=>'تم التعديل بنجاح']);
        }// end of else

    } // end of update function

    public function destroy($id){


        $language =Languages::find($id);

        if (!$language){

            return redirect()->route('admin.languages',$id)->with(['error'=>'غير موجود']);
        } // end of if
        else{

            $language->delete($id);
            return redirect()->route('admin.languages')->with(['success'=>'تم الحزف بنجاح']);
        }// end of else

    } // end of update function


}
