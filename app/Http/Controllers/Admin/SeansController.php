<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Salon;
use App\Models\Seans;
use App\Models\Bilet;
use App\Models\Koltuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Types\Relations\Car;

class SeansController extends Controller
{

    public function index(){
     $now = Carbon::now();

     $seanslar = Seans::with(["film","salon"])->where("is_active",true)
     ->where("bitis_zamani",">",$now)->orderBy("baslama_zamani")
     ->get();
     return view("admin.seans.index",compact("seanslar"));
    }
    public function create(){
        $filmler=Film::orderBy("title")->pluck("title","id");//frontend için dropdown sağla
        $salonlar=Salon::orderBy("Salon_adi")->pluck("Salon_adi","id");
        return view("admin.seans.create",compact("filmler","salonlar"));
    }
    public function store(Request $request){
      $data = $request->validate([
     "film_id"=>"required|exists:films,id",
     "salon_id"=>"required|exists:salons,id",
     "baslama_zamani"=>"required|date", 
    ]);

    $film = Film::findOrFail($data["film_id"]);
    preg_match("/(\d+)\s*min/",$film->runtime,$m);
    $sure= isset($m[1]) ? intval($m[1]) : 0;

    $bas = Carbon::parse($data["baslama_zamani"]);
    $bit = $bas->copy()->addMinutes($sure);

    Seans::create([
       'film_id'        => $data['film_id'],
            'Salon_id'       => $data['salon_id'],
            'baslama_zamani' => $bas,
            'bitis_zamani'   => $bit,
            'is_active'      => true,
  
    ]);
    return redirect()->route("admin.seans.index")->with("status","seans başarıyla oluştu");
    }

    public function show(Seans $seans){
   $seans->load([
       'film',
       'salon.koltuks.bilets' 
    ]);
   return view("admin.seans.show",compact("seans"));
    }
    public function destroy(Seans $seans){
 $seans ->update(["is_active"=>false]);
return redirect()->route("admin.seans.index")->with("status","seans iptal");
    }
   
    public function toggleSeat(Seans $seans, Koltuk $seat)
{
    $ticket = $seans->bilets()
                    ->where('koltuk_id', $seat->id)
                    ->first();

    if (! $ticket) {
        return back()->withErrors('Bu koltuğa ait aktif bir bilet yok.');
    }
    $ticket->update([
       'is_active' => ! $ticket->is_active,
    ]);
     return back()->with('success', 'Koltuk durumu güncellendi.');
}
public function showBySeans(Seans $seans)
{
    // Seans’a ait aktif ve pasif tüm biletler
    $biletler = Bilet::with(['user','koltuk'])
                     ->where('seans_id', $seans->id)
                     ->orderBy('koltuk_id')
                     ->get();

    return view('admin.bilets.show', compact('seans','biletler'));
}
    
}
