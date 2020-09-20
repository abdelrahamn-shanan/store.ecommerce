<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SubCategoryRequest;
use DB;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index(){
          $subcategories= Category::Child()->orderBy('id' ,'DESC')->paginate(PAGINATION_COUNT);
         return view('dashboard.subcategories.index', compact('subcategories'));
    }

    public function create(){
         $maincategories = Category::Parent()->Active()->get();
        return view('dashboard.subcategories.create' , compact('maincategories'));

    }
    public function store(SubCategoryRequest $request)
    {

       // return $request;
        try {

            //validation
            if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
           else
            $request->request->add(['is_active' => 1]);
            $filePath = "";
            if ($request->has('photo')) {

                $filePath = uploadImage('subcategories', $request->photo);
            }
            DB::beginTransaction();

            $subcategory = Category::create([
            'slug' =>  $request->slug,
            'is_active' => $request->is_active,
            'parent_id' => $request->parent_id,
            'photo' => $filePath
            ]);
            $subcategory->name = $request->name;
            $subcategory->save();
            DB::commit();
            return redirect()->route('index.subcategory')->with(['success'=>'تم الاضافة بنجاح']);

           }catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('index.subcategory')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
        }

    }

    public function edit($id){
         $subCategory=Category::orderBy('id' ,'DESC')->find($id); 
       if(! $subCategory)
           return redirect()->route('index.subcategory')->with(['error'=> 'هذا القسم غير موجود']);

         $maincategories = Category::Parent()->Active()->get();

        return view ('dashboard.subcategories.edit' ,compact('subCategory' ,'maincategories'));   
       
    }

    public function update($id , SubCategoryRequest $request){
      try{
        $subcategory = Category::find($id);
        if (!$subcategory)
          return redirect()->route('index.subcategory')->with(['error' => 'هذا القسم غير موجود']);

        if (!$request->has('is_active'))
        $request->request->add(['is_active' => 0]);
       else
      $request->request->add(['is_active' => 1]);

      $filePath = "";
      if ($request->has('photo')) {

          $filePath = uploadImage('subcategories', $request->photo);
      }

       $subcategory -> update([
           'slug' => $request->slug,
           'is_active' => $request->is_active,
           'photo' => $filePath

       ]);
       // save translation
       $subcategory->name = $request->name;
       $subcategory->save();
        return redirect()->route('index.subcategory')->with(['success'=>'تم التحديث بنجاح']);

      }catch(\Exception $ex)
      {
        return redirect()->route('index.subcategory')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
      }
    }

    public function delete($id){
        try{
          $subcategory = Category::find($id);
          if (!$subcategory)
          return redirect()->route('index.subcategory')->with(['error' => 'هذا القسم غير موجود']);
         /* $img = Str::after($subcategory->photo, 'assets/');
          $image = base_path('public\assets/' . $img);
          unlink($image);*/
          $subcategory->delete();
          return redirect()->route('index.subcategory')->with(['success'=>'تم الحذف بنجاح']);

        }catch(\Exception $ex)
        {
            return $ex;
         return redirect()->route('index.subcategory')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);

        }
    }
}
