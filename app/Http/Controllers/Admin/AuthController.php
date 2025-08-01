<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
      $request ->validate([ "email" => "required|email","password" =>"required"]);
        if(Auth::guard("web")->attempt([ 'email'    => $request->email,
            'password' => $request->password,] )){
     return redirect()->route("admin.dashboard");
        }
        return redirect()
        ->back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => 'E‑posta veya şifre hatalı.']);
    }

    public function logout(){
Auth::guard("web")->logout();
  return redirect()->route("admin.login");
    }
    public function showlogin(){
return view("admin.Auth.Login");
    }
}
