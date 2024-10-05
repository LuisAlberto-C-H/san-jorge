<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Tipo_servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.servicio.index')->only('index');
        $this->middleware('can:admin.servicio.create')->only('create', 'store');
        $this->middleware('can:admin.servicio.edit')->only('edit', 'update');
        $this->middleware('can:admin.servicio.destroy')->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalServicios = Servicio::where('estado', 1)->count();             
        $servicios = Servicio::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.servicio.index', compact('servicios', 'totalServicios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo_servicios = Tipo_servicio::where('estado', 1)->get();
        return view('admin.servicio.create',compact('tipo_servicios'));
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
            'tipo_servicio_id' => 'required|integer|exists:tipo_servicio,id',
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
        ], [
            'tipo_producto_id.required' => 'El campo Tipo de servicio es obligatorio',
            'nombre.required' => 'El campo nombre de servicio es obligatorio.',
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'precio.regex' => 'El precio debe contener solo 2 decimales.',
        ]);

        // Verificar si ya existe un registro con el mismo nombre, tipo de servicio y estado 1
        $existeServicioActivo = Servicio::where('nombre', $request->nombre)
                                        ->where('tipo_servicio_id', $request->tipo_servicio_id)
                                        ->where('estado', 1)
                                        ->first();

        if ($existeServicioActivo) {
            return redirect()->route('admin.servicio.index')->withErrors(['nombre' => 'El nombre del servicio ya existe para el tipo de servicio seleccionado.']);
        }

        // Verificar si ya existe un registro con el mismo nombre, tipo de servicio y estado 0
        $existeServicioInactivo = Servicio::where('nombre', $request->nombre)
                                        ->where('tipo_servicio_id', $request->tipo_servicio_id)
                                        ->where('estado', 0)
                                        ->first();
        if ($existeServicioInactivo) {
            $existeServicioInactivo->update(['estado' => 1, 'descripcion' => $request->descripcion]);

            return redirect()->route('admin.servicio.index')->with('message', 'El Servicio fue creado exitosamente.');
        }
        // Si no existe, crear un nuevo registro
        Servicio::create($request->only(['nombre', 'descripcion', 'precio', 'tipo_servicio_id']));
        return redirect()->route('admin.servicio.index')->with('message', 'El Servicio fue creado exitosamente.');
    
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
        $servicio = Servicio::findOrFail($id);
        $tipo_servicios = Tipo_servicio::where('estado', 1)->get();
        return view('admin.servicio.edit',compact('servicio','tipo_servicios'));
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
            'tipo_servicio_id' => 'required|integer|exists:tipo_servicio,id',
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
        ], [
            'tipo_producto_id.required' => 'El campo Tipo de servicio es obligatorio',
            'nombre.required' => 'El campo nombre de servicio es obligatorio.',
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'precio.regex' => 'El precio debe contener solo 2 decimales.',
        ]);
    
        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->only(['tipo_servicio_id', 'nombre', 'descripcion', 'precio']));
    
        return redirect()->route('admin.servicio.index')->with('message', 'El Servicio fue actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->estado = 0;
        $servicio->save();
    
        return redirect()->route('admin.servicio.index')
                        ->with('message', 'El servicio fue eliminado exitosamente.!');
    }
}
