<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = "cliente";

    protected $fillable = ['persona_id', 'razon_social', 'nit'];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function venta()
    {
        return $this->hasMany(Venta::class);
    }

}
