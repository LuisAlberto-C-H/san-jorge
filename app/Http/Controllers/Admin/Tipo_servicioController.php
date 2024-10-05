<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tipo_servicio;
use Illuminate\Http\Request;

class Tipo_servicioController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.tipo_servicio.index')->only('index');
        $this->middleware('can:admin.tipo_servicio.create')->only('create', 'store');
        $this->middleware('can:admin.tipo_servicio.edit')->only('edit', 'update');
        $this->middleware('can:admin.tipo_servicio.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalTipo_servicios = Tipo_servicio::where('estado', 1)->count();             
        $tipo_servicios = Tipo_servicio::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.tipo_servicio.index', compact('tipo_servicios', 'totalTipo_servicios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'nombre' => 'required'
        ], [
            'nombre.required' => 'El campo tipo de servicio es obligatorio.',
        ]);

        $existeTipoServicioActivo = Tipo_servicio::where('nombre', $request->nombre)->where('estado', 1)->first();
        if ($existeTipoServicioActivo) 
        {
            return redirect()->route('admin.tipo_servicio.index')->withErrors(['nombre' => 'El nombre del tipo de servicio ya existe']);
        }

        $existeTipoServicioInactivo = Tipo_servicio::where('nombre', $request->nombre)->where('estado', 0)->first();
        if ($existeTipoServicioInactivo) 
        {
            $existeTipoServicioInactivo->update(['estado' => 1]);

            return redirect()->route('admin.tipo_servicio.index')->with('message', 'El tipo de servicio fue creado exitosamente');
        }

        Tipo_servicio::create($request->only(['nombre']));
        return redirect()->route('admin.tipo_servicio.index')->with('message', 'El tipo de servicio fue creado exitosamente');
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
        //
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
            'nombre' => 'required|unique:tipo_servicio,nombre,' . $id
        ],
        [
            'nombre.required' => 'El campo Tipo de Producto es obligatorio.',
            'nombre.unique' => 'El nombre del Tipo de Producto ya existe.'
        ]);
    
        $tipo_servicio = Tipo_servicio::findOrFail($id);
        $tipo_servicio->update($request->only(['nombre']));
    
        return redirect()->route('admin.tipo_servicio.index')->with('message', 'El Tipo de Servicio fue actualizado exitosamente...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_servicio = Tipo_servicio::findOrFail($id);
        $tipo_servicio->estado = 0;
        $tipo_servicio->save();
    
        return redirect()->route('admin.tipo_servicio.index')
                        ->with('message', 'El Tipo de Servicio fue eliminado exitosamente...!');
    }
}
