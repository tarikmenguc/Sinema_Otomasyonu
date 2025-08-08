<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bilet;
use App\Models\BiletFiyati;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;



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

    try {
        $user = User::findOrFail($data['user_id']);
        $uyeTipi = $user->uye_tipi;
        $fiyat = BiletFiyati::where('uye_tipi', $uyeTipi)->value('fiyat');

        if (!$fiyat) {
            Log::warning("Fiyat bulunamadı. Üye tipi: {$uyeTipi}, user_id: {$user->id}");
            return response()->json(['message' => 'Fiyat tanımlı değil'], 500);
        }

        $seansId = $data['seans_id'];
        $transactionId = strtoupper(Str::random(40));
        $created = [];

        DB::beginTransaction();

        foreach ($data['koltuk_ids'] as $koltukId) {
            $exists = Bilet::where('seans_id', $seansId)
                ->where('koltuk_id', $koltukId)
                ->where(function ($q) {
                    $q->where('status', 'odendi')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'bekliyor')
                              ->where('created_at', '>=', now()->subMinutes(10));
                      });
                })->exists();

            if ($exists) {
                Log::info("Koltuk zaten dolu: {$koltukId} - seans: {$seansId}");
                DB::rollBack();
                return response()->json([
                    'message'   => "Koltuk #{$koltukId} zaten dolu.",
                    'koltuk_id' => $koltukId,
                ], 422);
            }

            $bilet = Bilet::create([
                'user_id'        => $user->id,
                'seans_id'       => $seansId,
                'koltuk_id'      => $koltukId,
                'fiyat'          => $fiyat,
                'bilet_tipi'     => $uyeTipi,
                'status'         => 'bekliyor',
                'is_active'      => false,
                'transaction_id' => $transactionId,
            ]);

            $created[] = $bilet;

            Log::info("Bilet oluşturuldu", [
                'bilet_id' => $bilet->id,
                'user_id' => $user->id,
                'seans_id' => $seansId,
                'koltuk_id' => $koltukId,
                'status' => 'bekliyor'
            ]);
        }

        DB::commit();

        return response()->json([
            'message'     => 'Bilet rezerve edildi. Ödemeye geçebilirsiniz.',
            'ticket_ids'  => collect($created)->pluck('id'),
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Bilet rezervasyon hatası', [
            'error_message' => $e->getMessage(),
            'user_id'       => $data['user_id'] ?? null,
            'seans_id'      => $data['seans_id'] ?? null,
            'koltuk_ids'    => $data['koltuk_ids'] ?? [],
        ]);

        return response()->json([
            'message' => 'Sunucu hatası oluştu. Lütfen tekrar deneyin.',
        ], 500);
    }
}



}