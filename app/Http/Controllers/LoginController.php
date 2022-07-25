<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\LoginController;
use Auth;

use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller{

  public function redirectToFacebook(){
   return Socialite::driver('facebook')->redirect();
}
  public function handleFacebookCallback() {
    $user = Socialite::driver('facebook')->user();

      $this->_registerOrLoginUser($user);

      return redirect('dashboard');

}
  protected function _registerOrLoginUser($data){
    $user = User::where('email', '=', $data->email)->first();
    if (!$user){
      $user = new User();
      $user->name = $data->name;
      $user->email = $data->email;
      $user->provider_id = $data->id;
      $user->avatar = $data->avatar;
      $user->save();
    }
    Auth::login($user);
  }
}
