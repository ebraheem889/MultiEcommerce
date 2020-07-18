<?php

use Illuminate\Support\Facades\Config;
use App\Models\Languages;


function getLang(){


  return  Languages::active()->Selection()->get();
  


}// end of getlang

function getDefaultLange(){


return Config::get('app.locale');


} // end of getlang

function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
} // end of upload image
