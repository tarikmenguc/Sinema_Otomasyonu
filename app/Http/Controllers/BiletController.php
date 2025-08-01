<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bilet;
class BiletController extends Controller
{
     public function index(Request $request){
       $tickets = $request->user()
       ->bilets()->where("is_active",true)->with(["seans.film","seans.salon","koltuk"])
       ->get();
           return response()->json($tickets);
     }

     public function history(Request $request){
            $tickets = $request->user()
            ->bilets()->where("is_active",false)
            ->with(["seans.film","seans.salon","koltuk"])->get();
               return response()->json($tickets);
     }

     public function show(Request $request,$id){
            $ticket = $request ->user()->bilets()->with(["seans.film","seans.salon","koltuk"])
            ->findOrFail($id);
            return response()->json($ticket);
     }
     public function destroy(Request $request,$id){
         $ticket = $request->user()
         ->bilets()->findOrFail($id);
         if(! $ticket->is_active){
            return response()->json(["message"=>"bilet zaten iptal edilmiş"]);
         }

         $ticket -> update(["is_active"=>false]);
         return response()->json(["message"=>"bilet iptal edildi"]);
     }
     public function store(Request $request)
     {
    $data = $request->validate([
        'user_id'      => 'required|exists:users,id',
        'seans_id'     => 'required|exists:seans,id',
        'koltuk_ids'   => 'required|array|min:1',
        'koltuk_ids.*' => 'integer|exists:koltuks,id',
    ]);
    $userId   = $data['user_id'];
    $seansId  = $data['seans_id'];
    $fiyat    = 100;
    $created  = [];

    foreach ($data['koltuk_ids'] as $koltukId) {
        $exists = Bilet::where('seans_id', $seansId)
                       ->where('koltuk_id', $koltukId)
                       ->where('is_active', 1)
                       ->exists();

        if ($exists) {
            return response()->json([
                'message'    => "Koltuk #{$koltukId} zaten dolu.",
                'koltuk_id'  => $koltukId,
            ], 422);
        }
        $created[] = Bilet::create([
            'user_id'   => $userId,
            'seans_id'  => $seansId,
            'koltuk_id' => $koltukId,
            'fiyat'     => $fiyat,
            'is_active' => 1,
        ]);
    }
    return response()->json([
        'message' => 'Biletler başarıyla alındı.',
        'tickets' => $created,
    ], 201);
     }
}
