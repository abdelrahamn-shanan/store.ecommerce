<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class BrandController extends Controller
{
    public function index(){
         $brands= Brand::orderBy('id' ,'DESC')->paginate(PAGINATION_COUNT);
         return view('dashboard.brands.index', compact('brands'));
    }

    public function create(){
        return view('dashboard.brands.create');

    }
    public function store(BrandRequest $request)
    {

        try {

            //validation
            if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
           else
            $request->request->add(['is_active' => 1]);
            $fileName = "";
            if ($request->has('photo')) {

              $fileName = uploadImage('brands', $request->photo);
            }
            DB::beginTransaction();

            $brand = Brand::create([
            'is_active' => $request->is_active,
            'photo' => $fileName
            ]);
            $brand->name = $request->name;
            $brand->save();
            DB::commit();
            return redirect()->route('index.brand')->with(['success'=>'تم الاضافة بنجاح']);

           }catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('index.brand')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
        }

    }
    public function edit($id){
        $brand=Brand::find($id);
      if(!$brand)
          return redirect()->route('index.brand')->with(['error'=> 'هذا العنصر غير موجود']);

       return view ('dashboard.brands.edit' ,compact('brand'));   
      
   }

   public function update($id , BrandRequest $request){
     try{
       $brand = Brand::find($id);
       if (!$brand)
         return redirect()->route('index.brand')->with(['error' => 'هذا العنصر غير موجود']);

      if (!$request->has('is_active'))
       $request->request->add(['is_active' => 0]);
      else
     $request->request->add(['is_active' => 1]);

     DB::beginTransaction();
     $fileName = "";
     if ($request->has('photo')) {

         $fileName = uploadImage('brands', $request->photo);
     }
     $brand -> update([
      'is_active' => $request->is_active,
      'photo' => $fileName

  ]);
      // save translation
      $brand->name = $request->name;
      $brand->save();
      DB::commit();
       return redirect()->route('index.brand')->with(['success'=>'تم التحديث بنجاح']);

     }catch(\Exception $ex)
     {
      DB::rollback();
      return redirect()->route('index.brand')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
     }
   }

   public function delete($id){
       try{
         $brand = Brand::find($id);
         if (!$brand)
         return redirect()->route('index.brand')->with(['error' => 'هذا القسم غير موجود']);
         
         if ($brand->photo) {
         $img = Str::after($brand->photo, 'assets/');
         $image = base_path('public\assets/' . $img);
         unlink($image);
         }
         $brand->delete();
         return redirect()->route('index.brand')->with(['success'=>'تم الحذف بنجاح']);

       }catch(\Exception $ex)
       {
         return redirect()->route('index.brand')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);

       }
   }
}
