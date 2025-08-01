<?php

namespace Database\Seeders;

use App\Models\Koltuk;
use App\Models\Salon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalonKoltukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
     

        // 5 salon oluştur
        for ($i = 1; $i <= 5; $i++) {
            $salon = Salon::create([
                'Salon_adi' => "Salon {$i}",
                'kapasite'  => 30,
                'aktifmi'   => true,
            ]);

            // Her salon için 30 koltuk oluştur
            for ($j = 1; $j <= 30; $j++) {
                Koltuk::create([
                    'koltuk_no' => $j,
                    'salon_id'  => $salon->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}