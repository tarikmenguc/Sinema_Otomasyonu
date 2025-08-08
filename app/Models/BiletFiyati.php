<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiletFiyati extends Model
{
    use HasFactory;
    protected $table = 'bilet_fiyatlari'; 
    protected $guarded = [];
     protected $fillable = ['uye_tipi', 'fiyat'];
      public $timestamps = true;
}
