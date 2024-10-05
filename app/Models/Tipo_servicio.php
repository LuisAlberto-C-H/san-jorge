<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_servicio extends Model
{
    use HasFactory;

    protected $table = "tipo_servicio";

    protected $fillable = ['nombre', 'estado'];

    public function servicio()
    {
        return $this->hasMany(Servicio::class);
    }

}
