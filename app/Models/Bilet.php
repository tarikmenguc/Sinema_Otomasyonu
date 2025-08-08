<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bilet extends Model
{
  
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function seans(){
        return $this->belongsTo(Seans::class);
    }
    public function koltuk(){
        return $this->belongsTo(Koltuk::class);
    }
}
