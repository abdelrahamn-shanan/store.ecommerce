<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use DB;
use Illuminate\Support\Str;


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
            $filePath = "";
            if ($request->has('photo')) {

                $filePath = uploadImage('brands', $request->photo);
            }
            DB::beginTransaction();

            $brand = Brand::create([
            'is_active' => $request->is_active,
            'photo' => $filePath
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
        $brand=Brand::orderBy('id' ,'DESC')->find($id);
      if(!$brand)
          return redirect()->route('index.brand')->with(['error'=> 'هذا القسم غير موجود']);

       return view ('dashboard.brands.edit' ,compact('brand'));   
      
   }

   public function update($id , BrandRequest $request){
     try{
       $brand = Brand::find($id);
       if (!$brand)
         return redirect()->route('index.brand')->with(['error' => 'هذا القسم غير موجود']);

       if (!$request->has('is_active'))
       $request->request->add(['is_active' => 0]);
      else
     $request->request->add(['is_active' => 1]);

     $filePath = "";
     if ($request->has('photo')) {

         $filePath = uploadImage('brands', $request->photo);
     }

      $brand -> update([
          'is_active' => $request->is_active,
          'photo' => $filePath

      ]);
      // save translation
      $brand->name = $request->name;
      $brand->save();
       return redirect()->route('index.brand')->with(['success'=>'تم التحديث بنجاح']);

     }catch(\Exception $ex)
     {
         return $ex;
       return redirect()->route('index.brand')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);
     }
   }

   public function delete($id){
       try{
         $brand = Brand::find($id);
         if (!$brand)
         return redirect()->route('index.brand')->with(['error' => 'هذا القسم غير موجود']);
         $img = Str::after($brand->photo, 'assets/');
         $image = base_path('public\assets/' . $img);
         unlink($image);
         $brand->delete();
         return redirect()->route('index.brand')->with(['success'=>'تم الحذف بنجاح']);

       }catch(\Exception $ex)
       {
         return redirect()->route('index.brand')->with(['error'=>' حدث خطأ ما يرجي المحاولة فيما بعد']);

       }
   }
}
