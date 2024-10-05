<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.persona.index')->only('index');
        $this->middleware('can:admin.persona.create')->only('create', 'store');
        $this->middleware('can:admin.persona.edit')->only('edit', 'update');
        $this->middleware('can:admin.persona.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $totalPersonas = Persona::where('estado', 1)->count();             
        $personas = Persona::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.persona.index', compact('personas', 'totalPersonas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.persona.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'ci' => 'required',
            'correo_personal' => 'required',
            'celular' => 'required'
        ],
        [
            'correo_personal.required' => 'El campo correo es obligatorio',
        ]);

        Persona::create($request->only(
            'nombre','apellido','ci','correo_personal','celular'
        ));
        return redirect()->route('admin.persona.index')->with('message', 'Persona registrada exitosamente.!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        return view('admin.persona.edit',compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'ci' => 'required',
            'correo_personal' => 'required',
            'celular' => 'required'
        ],
        [
            'correo_personal.required' => 'El campo correo es obligatorio',
        ]);

        $persona = Persona::findOrFail($id);
        $datos = $request->only(['nombre','apellido','ci','correo_personal','celular']);
        $persona->update($datos);
        return redirect()->route('admin.persona.index')->with('message', 'Persona actualizada exitosamente.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->estado = 0;
        $persona->save();
    
        return redirect()->route('admin.persona.index')
                        ->with('message', 'La Persona fue eliminada exitosamente.!');
    }

    public function search(Request $request)
    {
        $search = $request->input('q');
        $personas = Persona::where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('apellido', 'LIKE', "%{$search}%")
                    ->where('estado', 1)
                    ->get();

        return response()->json($personas);
    }

}
