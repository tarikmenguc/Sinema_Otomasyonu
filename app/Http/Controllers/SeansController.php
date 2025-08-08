<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Carbon\Carbon;
use App\Models\Seans;
use Illuminate\Http\Request;

class SeansController extends Controller
{
    public function index(Request $request){
        
     $seanslar = Seans::with(['salon', 'film']) 
    ->where('is_active', true)
    ->when($request->film_title, function($q, $filmTitle) {
        $q->whereHas('film', function($q) use ($filmTitle) {
            $q->where('title', 'like', "%{$filmTitle}%");
        });
    })
    ->when($request->salon_adi, function($q, $salonAdi) {
        $q->whereHas('salon', function($q) use ($salonAdi) {
            $q->where('salon_adi', 'like', "%{$salonAdi}%");
        });
    })
    ->get();

        return response()->json($seanslar);
             
        }

        public function byFilm(Film $film)
    {
        $now = Carbon::now();

       $seanslar = $film
    ->seanslar()
    ->with(['salon']) 
    ->where('is_active', true)
    ->where('bitis_zamani', '>', Carbon::now())
    ->orderBy('baslama_zamani', 'asc')
    ->get();

        return response()->json($seanslar);
    }
 
        public function show(Seans $seans){
      $seans->load("salon.koltuks.bilets", "film");

$koltuks = $seans->salon->koltuks->map(function($koltuk) use ($seans){
    $bilet = $koltuk->bilets->where('seans_id', $seans->id)->first();
    return [
        "id"=>$koltuk->id, // sonradan ekledim
        'koltuk_no' => $koltuk->koltuk_no,
        'is_taken'  => (bool) $bilet,
    ];
});
       return response()->json(["seans"=>$seans,"seats"=>$koltuks]);
        } 
    }

