<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tipo_producto;
use Illuminate\Http\Request;

class Tipo_productoController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.tipo_producto.index')->only('index');
        $this->middleware('can:admin.tipo_producto.create')->only('create', 'store');
        $this->middleware('can:admin.tipo_producto.edit')->only('edit', 'update');
        $this->middleware('can:admin.tipo_producto.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalTipo_productos = Tipo_producto::where('estado', 1)->count();             
        $tipo_productos = Tipo_producto::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.tipo_producto.index', compact('tipo_productos', 'totalTipo_productos'));
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
            'nombre.required' => 'El campo tipo de producto es obligatorio.',
        ]);

        $existeTipoProductoActivo = Tipo_producto::where('nombre', $request->nombre)->where('estado', 1)->first();
        if ($existeTipoProductoActivo) 
        {
            return redirect()->route('admin.tipo_producto.index')->withErrors(['nombre' => 'El nombre del tipo de producto ya existe']);
        }

        $existeTipoProductoInactivo = Tipo_producto::where('nombre', $request->nombre)->where('estado', 0)->first();
        if ($existeTipoProductoInactivo) 
        {
            $existeTipoProductoInactivo->update(['estado' => 1]);

            return redirect()->route('admin.tipo_producto.index')->with('message', 'El tipopp de producto fue creado exitosamente');
        }

        Tipo_producto::create($request->only(['nombre']));
        return redirect()->route('admin.tipo_producto.index')->with('message', 'El tipo de producto fue creado exitosamente');
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
            'nombre' => 'required|unique:tipo_producto,nombre,' . $id
        ],
        [
            'nombre.required' => 'El campo Tipo de Producto es obligatorio.',
            'nombre.unique' => 'El nombre del Tipo de Producto ya existe.'
        ]);
    
        $tipo_producto = Tipo_producto::findOrFail($id);
        $tipo_producto->update($request->only(['nombre']));
    
        return redirect()->route('admin.tipo_producto.index')->with('message', 'El Tipo de Producto fue actualizado exitosamente...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_producto = Tipo_producto::findOrFail($id);
        $tipo_producto->estado = 0;
        $tipo_producto->save();
    
        return redirect()->route('admin.tipo_producto.index')
                        ->with('message', 'El Tipo de Producto fue eliminado exitosamente...!');
    }
}
