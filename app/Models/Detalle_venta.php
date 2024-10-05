<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_venta extends Model
{
    use HasFactory;

    protected $table = "detalle_venta";

    protected $fillable = ['venta_id', 'inventario_id', 'servicio_id', 'cantidad', 'subtotal'];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

}
