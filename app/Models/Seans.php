<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class Seans extends Model
{
      protected $guarded = [];
        protected $casts = [
        'baslama_zamani' => 'datetime',
        'bitis_zamani'   => 'datetime',
    ];
    public function film(){
        return $this->belongsTo(Film::class);
    }
    public function salon(){
        return $this->belongsTo(Salon::class,'Salon_id');
    }
    public function bilets(){
        return $this->hasMany(Bilet::class);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d.m.Y H:i');
    }
}
