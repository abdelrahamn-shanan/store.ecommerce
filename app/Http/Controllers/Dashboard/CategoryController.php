<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MainCategoryRequest;
use DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(){
        $categories= Category::orderBy('id' ,'DESC')->get();
         return view('dashboard.categories.index', compact('categories'));
    }

    public function create(){
        $categories= Category::select('id' , 'parent_id')->orderBy('id' ,'DESC')->get();
        return view('dashboard.categories.create' , compact('categories'));

    }
    public function store(MainCategoryRequest $request)
    {

        try {

            //validation
            if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
           else
            $request->request->add(['is_active' => 1]);
            // upload image
            $filePath = "";
            if ($request->has('photo')) {

                $filePath = uploadImage('maincategories', $request->photo);
            }
            // if user choose maincategory remove parent_id
            if( $request->type == 1 )
            {
              $request->request->add(['parent_id' => null]);
            }
            //if user choose sub category we must add parent-id
            DB::beginTransaction();
            $category = Category::create($request->except('_token'));
            $category->photo = $filePath ;
            // save translations
            $category->name = $request->name;
            $category->save();
            DB::commit();
            return redirect()->route('index.category')->with(['success'=>'تم الاضافة بنجاح']);

           }catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('index.category')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
        }

    }

    public function edit($id){
         $category=Category::orderBy('id' ,'DESC')->find($id);
       if(! $category)
           return redirect()->route('index.category')->with(['error'=> 'هذا القسم غير موجود']);

        return view ('dashboard.categories.edit' ,compact('category'));   
       
    }

    public function update($id , MainCategoryRequest $request){
      
       //return $request;
      try{
        $category = Category::find($id);
        if (!$category)
          return redirect()->route('index.category')->with(['error' => 'هذا القسم غير موجود']);

        if (!$request->has('is_active'))
        $request->request->add(['is_active' => 0]);
       else
        $request->request->add(['is_active' => 1]);

      $filePath = "";
      if ($request->has('photo')) {

          $filePath = uploadImage('maincategories', $request->photo);
      }

       $category -> update([
           'slug' => $request->slug,
           'is_active' => $request->is_active,
           'photo' => $filePath

       ]);
       // save translation
       $category->name = $request->name;
       $category->save();
        return redirect()->route('index.category')->with(['success'=>'تم التحديث بنجاح']);

      }catch(\Exception $ex)
      {
        return redirect()->route('index.category')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
      }
    }

    public function delete($id){
        try{
          $category = Category::find($id);
          if (!$category)
          return redirect()->route('index.category')->with(['error' => 'هذا القسم غير موجود']);
          $img = Str::after($category->photo, 'assets/');
          $image = base_path('public\assets/' . $img);
          unlink($image);
          $category->delete();
          return redirect()->route('index.category')->with(['success'=>'تم الحذف بنجاح']);

        }catch(\Exception $ex)
        { 
          return $ex;
          return redirect()->route('index.category')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);

        }
    }
}
