<?php
define('PAGINATION_COUNT' ,20);
 function getFolder(){
  return app()->getlocale()==='ar' ? 'css-rtl' : 'css';   
}

function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
 }

