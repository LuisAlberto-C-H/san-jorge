<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = "servicio";

    protected $fillable = ['tipo_servicio_id', 'nombre', 'descripcion', 'precio', 'estado'];

    public function tipo_servicio()
    {
        return $this->belongsTo(Tipo_servicio::class);
    }

    public function detalle_venta()
    {
        return $this->hasMany(Detalle_venta::class);
    }

}
