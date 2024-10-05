<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = "persona";

    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'correo_personal',
        'celular'
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function proveedor()
    {
        return $this->hasOne(Proveedor::class);
    }

    public function user(){
        return $this->hasOne(User::class);
    }

}
