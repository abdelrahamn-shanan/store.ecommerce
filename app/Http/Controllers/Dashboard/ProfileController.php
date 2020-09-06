<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use DB;
class ProfileController extends Controller
{
    public function edit(){
          $admin = Admin::find( auth('admin')->user()->id) ;
          return view ('dashboard.profile.edit' , compact('admin'));
    }

    public function update(ProfileRequest $request ){
          // validation
       
       try{  // saving
        $admin= Admin::findorfail(auth('admin')->user()->id);
        DB::beginTransaction();
        if($request->filled('password'))
        {
          $request->merge(['password'=>bcrypt($request->password)]);
        }
        unset ($request['id']);
        unset ($request['password_confirmation']);
        $admin->update($request->all());
          $admin->save();
          DB::commit();
          return redirect()->back()->with(['success' => ' تم التحديث بنجاح']);
        }catch(\Exception $ex)
        {
            return redirect()->back()->with(['error' => ' حدث خطا ما يرجي المحاولة لاحقا ']);
            DB:rollback();
        }
    }
}
