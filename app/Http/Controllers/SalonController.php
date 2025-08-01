<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use Illuminate\Http\Request;
use PHPUnit\Event\TestSuite\Loaded;

class SalonController extends Controller
{
   
    public function index(){
  $salonlar = Salon::where("aktifmi",true);
  return response()->json([$salonlar]);
    }


    public function show(Salon $salon){
  $salon->load("koltuks");
  return response()->json([$salon]);
    }
}
