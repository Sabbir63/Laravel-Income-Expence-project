<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use app\Models\User;
use Carbon\Carbon;
use Session;
use Image;

class UserController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
       $allUser=User::orderBy('id','DESC')->get();
       return view('admin.user.all',compact('allUser'));
     }
     public function add(){
        return view('admin.user.add');
    }
     public function edit(){

    }
     public function view(){
        return view('admin.user.view');
    }
     public function insert(Request $request){
          $this->validate($request,[
     'name' => ['required', 'string', 'max:255'],
     'phone' => ['required'],
     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
     'password' => ['required', 'confirmed', Rules\Password::defaults()],
     'role' => ['required'],
   ],[
     'name.required'=>'Please enter name!',
     'email.required'=>'Please enter email address!',
     'phone.required'=>'Please enter phone Number!',
     'password.required'=>'Please enter password!',
     'role.required'=>'Please select role!',
   ]);

        $insert=User::insertGetId([
               'name'=>$request['name'],
               'phone'=>$request['phone'],
               'email'=>$request['email'],
               'password'=>Hash::make($request['password']),
               'role'=>$request['role'],
               'created_at'=>Carbon::now()->toDateTimeString(),
             ]);

             if($request->hasFile('pic')){
               $image=$request->file('pic');
               $imageName=$insert.time().'.'.$image->getClientOriginalExtension();
               Image::make($image)->resize(300,300)->save('uploads/users/'.$imageName);

               User::where('id',$insert)->update([
                 'photo'=>$imageName,
               ]);
             }

             if($insert){
                Session::flash('success','Successfully registered user.');
                return redirect('dashboard/user/add');
              }else{
                Session::flash('error','Opps! please try again.');
                return redirect('dashboard/user/add');
              }
    }
     public function update(){

    }
     public function softdelete(){

    }
     public function restore(){

    }
     public function delete(){

    }
}
