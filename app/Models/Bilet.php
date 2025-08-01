<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bilet extends Model
{
    protected $fillable = [
        'user_id',
        'seans_id',
        'koltuk_id',
        'fiyat',
        'is_active',
    ];
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
