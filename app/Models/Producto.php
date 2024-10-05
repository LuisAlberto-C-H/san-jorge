<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = "producto";

    protected $fillable = ['tipo_producto_id', 'nombre', 'descripcion', 'estado'];

    public function tipo_producto()
    {
        return $this->belongsTo(Tipo_producto::class);
    }

    public function inventario()
    {
        return $this->hasMany(Inventario::class);
    }
}
