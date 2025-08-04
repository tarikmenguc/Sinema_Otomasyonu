<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Koltuk extends Model
{
      protected $guarded = [];
    public function salon(){
        return $this->belongsTo(Salon::class);
    }
  public function bilets()
{
    return $this->hasMany(Bilet::class);
}

public function biletForSeans(int $seansId)
{
    return $this->bilets()->where('seans_id', $seansId)->where('is_active', 1)->first();
}
}
