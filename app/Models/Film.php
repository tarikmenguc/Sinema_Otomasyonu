<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
      protected $guarded = [];
      protected $casts = [
    'ratings'     => 'array',     // DB’de JSON tutulur, PHP’de otomatik array olur
    'imdb_rating' => 'decimal:1', 
];
    public function seanslar(){
        return $this->hasMany(Seans::class);
    }
}
