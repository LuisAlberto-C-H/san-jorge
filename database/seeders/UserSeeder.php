<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $persona = DB::table('persona')->insertGetId([
            'nombre' => 'Benancio',
            'apellido' => 'Paye Siñani',
            'ci' => '6528967',
            'correo_personal' => 'benancio@gmail.com',
            'celular' => '66489526',
        ]);

        
        User::create([
            'persona_id' => $persona,
            'rol_id' => 1,
            'name' => 'Benancio PS',
            'email' => 'usuario1@gmail.com',
            'password' => bcrypt('Cumbre2021*')
        ])->assignRole('Administrador del sistema');
    }
}
