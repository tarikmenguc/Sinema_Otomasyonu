<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    public function index(){
        $salonlar = Salon::with(["seanslar","koltuks"])->get();
        return view("admin.salons.index",compact("salonlar"));

    }
    public function show(Salon $salon){
     $salon->load(["seanslar" => function($q){
        $q->where("is_active",true)->where("bitis_zamani",">",Carbon::now());
     },"koltuks"]);
     return view("admin.salons.edit",compact("salon"));
    }
    public function toggleStatus(Salon $salon){
         $salon->aktifmi =! $salon->aktifmi;
         $salon->save();
         return back()->with("mesaj","salon aktifliği değiştirildi");
    }
    public function generateSeats(Salon $salon){
       if($salon->koltuks()->count() >= 30){
      return back()->with("error","zaten koltuk var ekleme");
       }
       for($i=1;$i<=30;$i++){
    $salon ->koltuks()->firstOrCreate([ "koltuk_no" =>$i],["is_active" => true  ]);
       }
       return back()->with("success","başarıyla oluşturuldu");
    }
}
