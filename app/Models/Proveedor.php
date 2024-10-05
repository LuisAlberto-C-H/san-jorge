<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = "proveedor";

    protected $fillable = ['persona_id', 'razon_social', 'nit'];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function compra()
    {
        return $this->hasMany(Compra::class);
    }


}
