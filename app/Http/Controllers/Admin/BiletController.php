<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bilet;
use App\Models\Koltuk;
use App\Models\Seans;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BiletController extends Controller
{
    public function index(){
        $now = Carbon::now();
        $seanslar=Seans::withCount("bilets")->where("is_active",true)->where("bitis_zamani",">",$now)->orderBy("baslama_zamani")->get();
        return view("admin.bilets.index",compact("seanslar"));
    }
    public function showbySeans(Seans $seans){
        $biletler = $seans->bilets()->with(['user','koltuk'])->get();
        return view('admin.bilets.show', compact('seans','biletler'));
    }
    public function toggleStatus(Bilet $bilet){
    $bilet->is_active =! $bilet->is_active;
    $bilet->save();
    return back()->with("mesaj","bilet aktifliği değiştirildi");
    }
}
