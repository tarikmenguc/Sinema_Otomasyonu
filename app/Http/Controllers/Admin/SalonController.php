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
   public function generateSeats(Request $request, Salon $salon)
{
    $request->validate([
        'adet' => 'required|integer|min:1|max:200'
    ]);

    $adet = $request->adet;
    $mevcut = $salon->koltuks()->count();
    $kapasite = $salon->kapasite;
    $eklenebilir = $kapasite - $mevcut;

    if ($eklenebilir <= 0) {
        return back()->with("error", "Bu salonun kapasitesi dolu. Yeni koltuk eklenemez.");
    }

    if ($adet > $eklenebilir) {
        return back()->with("error", "En fazla $eklenebilir koltuk daha ekleyebilirsin.");
    }
    $startNo = $mevcut + 1;
    $endNo = $startNo + $adet - 1;

    for ($i = $startNo; $i <= $endNo; $i++) {
        $salon->koltuks()->firstOrCreate(
            ["koltuk_no" => $i],
            ["is_active" => true]
        );
    }

    return back()->with("success", "$adet koltuk başarıyla oluşturuldu.");
}
public function updateCapacity(Request $request, Salon $salon)
{
    $mevcutKoltukSayisi = $salon->koltuks()->count();

    $request->validate([
        'kapasite' => 'required|integer|min:1|max:200'
    ]);

    $yeniKapasite = $request->kapasite;
    if ($yeniKapasite < $mevcutKoltukSayisi) {
        $fazlaKoltuklar = $salon->koltuks()
            ->orderBy('koltuk_no', 'desc')
            ->take($mevcutKoltukSayisi - $yeniKapasite)
            ->get();

        foreach ($fazlaKoltuklar as $koltuk) {
            $koltuk->is_active = false;
            $koltuk->save();
        }
    }

    $salon->kapasite = $yeniKapasite;
    $salon->save();

    return back()->with('success', 'Salon kapasitesi güncellendi. Fazla koltuklar devre dışı bırakıldı.');
}


}
