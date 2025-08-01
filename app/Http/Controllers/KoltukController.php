<?php

namespace App\Http\Controllers;

use App\Models\Seans;
use Illuminate\Http\Request;

class KoltukController extends Controller
{
   public function show(Seans $seans)
{
    $seans->load('salon', 'film');
    $koltuklar = $seans->salon->first()->koltuks;

    $seats = [];
    foreach ($koltuklar as $k) {
        $bilet = $k->biletForSeans($seans->id);
        $seats[] = [
            'id'       => $k->id,
            'no'       => $k->koltuk_no,
            'is_free'  => $bilet === null, // null ise boş
        ];
    }

    return response()->json([
        'seans' => $seans,
        'seats' => $seats,
    ]);
}

}
