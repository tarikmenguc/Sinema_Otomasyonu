<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordCodeMail;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
   public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|min:2',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6',
            'uye_tipi'   => 'required|in:ogrenci,tam',
        ]);

     $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'uye_tipi'  => $data['uye_tipi'],
        ]);

         $role = Role::firstOrCreate(['rol' => 'customer']);
        $user->roles()->syncWithoutDetaching([$role->id]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $user->load('roles'),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Bilgiler hatalı'], 401);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $user->load('roles'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Çıkış yapıldı']);
    }


    public function me(Request $request)
    {
        return response()->json($request->user()->load('roles'));
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email',$request->email)->first();
        if (! $user) {
            return response()->json(['message'=>'Böyle bir kullanıcı yok.'], 404);
        }

        
        $code = rand(100000, 999999);

        
        DB::table('password_resets')->where('email', $request->email)->delete();

       
        DB::table('password_resets')->insert([
            'email'      => $request->email,
            'token'      => $code,
            'created_at' => Carbon::now(),
        ]);

      
        Mail::to($request->email)->send(new ResetPasswordCodeMail($code));

        return response()->json(['message'=>'Şifre sıfırlama kodu e‑postana gönderildi.']);
    }

  
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required|string',  
            'password'              => 'required|string|min:6',
        ]);

        
        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (! $record) {
            return response()->json(['message'=>'Geçersiz kod veya e‑posta.'], 400);
        }

       
        $expires = Carbon::parse($record->created_at)->addMinutes(15);
        if (Carbon::now()->gt($expires)) {
            return response()->json(['message'=>'Kodun süresi dolmuş. Tekrar isteyebilirsiniz.'], 400);
        }

       
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

       
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message'=>'Şifre başarıyla değiştirildi.']);
    }
}
