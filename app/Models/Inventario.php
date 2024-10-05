<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = "inventario";

    protected $fillable = ['producto_id', 'compra_id', 'precio_compra', 'precio_venta', 'stock', 'cantidad_compra'];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function detalle_venta()
    {
        return $this->hasMany(Detalle_venta::class);
    }

}
