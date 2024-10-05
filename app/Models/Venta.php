<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = "venta";

    protected $fillable = ['cliente_id', 'monto_total'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalle_venta()
    {
        return $this->hasMany(Detalle_venta::class);
    }

}
