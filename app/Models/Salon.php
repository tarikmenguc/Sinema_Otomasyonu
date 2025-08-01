<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
      protected $guarded = [];
    public function seanslar(){
        return $this->hasMany(Seans::class);
    }
    public function koltuks(){
        return $this->hasMany(Koltuk::class);
    }
}
