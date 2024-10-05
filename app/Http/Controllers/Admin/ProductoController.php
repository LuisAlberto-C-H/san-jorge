<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Tipo_producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.producto.index')->only('index');
        $this->middleware('can:admin.producto.create')->only('create', 'store');
        $this->middleware('can:admin.producto.edit')->only('edit', 'update');
        $this->middleware('can:admin.producto.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalProductos = Producto::where('estado', 1)->count();             
        $productos = Producto::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.producto.index', compact('productos', 'totalProductos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $tipo_productos = Tipo_producto::where('estado', 1)->get();
        return view('admin.producto.create',compact('tipo_productos'));
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
            'tipo_producto_id' => 'required|integer|exists:tipo_producto,id',
            'nombre' => 'required',
            'descripcion' => 'required|string|min:10|max:350'
        ], [
            'tipo_producto_id.required' => 'El campo Tipo de producto es obligatorio',
            'nombre.required' => 'El campo nombre de producto es obligatorio.',
            'descripcion.required' => 'El campo descripción es obligatorio.'
        ]);

        // Verificar si ya existe un registro con el mismo nombre, tipo de producto y estado 1
        $existeProductoActivo = Producto::where('nombre', $request->nombre)
                                        ->where('tipo_producto_id', $request->tipo_producto_id)
                                        ->where('estado', 1)
                                        ->first();

        if ($existeProductoActivo) {
            return redirect()->route('admin.producto.index')->withErrors(['nombre' => 'El nombre del producto ya existe para el tipo de producto seleccionado.']);
        }

        // Verificar si ya existe un registro con el mismo nombre, tipo de producto y estado 0
        $existeProductoInactivo = Producto::where('nombre', $request->nombre)
                                        ->where('tipo_producto_id', $request->tipo_producto_id)
                                        ->where('estado', 0)
                                        ->first();
        if ($existeProductoInactivo) {
            $existeProductoInactivo->update(['estado' => 1, 'descripcion' => $request->descripcion]);

            return redirect()->route('admin.producto.index')->with('message', 'El Producto fue creado exitosamente.');
        }
        // Si no existe, crear un nuevo registro
        Producto::create($request->only(['nombre', 'descripcion', 'tipo_producto_id']));
        return redirect()->route('admin.producto.index')->with('message', 'El Producto fue creado exitosamente.');
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
        $producto = Producto::findOrFail($id);
        $tipo_productos = Tipo_producto::where('estado', 1)->get();
        return view('admin.producto.edit',compact('producto','tipo_productos'));
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
            'tipo_producto_id' => 'required|integer|exists:tipo_producto,id',
            'nombre' => 'required',
            'descripcion' => 'required|string|min:10|max:350'
        ], [
            'tipo_producto_id.required' => 'El campo Tipo de producto es obligatorio',
            'nombre.required' => 'El campo nombre de producto es obligatorio.',
            'descripcion.required' => 'El campo descripción es obligatorio.'
        ]);
    
        $producto = Producto::findOrFail($id);
        $producto->update($request->only(['tipo_producto_id', 'nombre', 'descripcion']));
    
        return redirect()->route('admin.producto.index')->with('message', 'El Producto fue actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = 0;
        $producto->save();
    
        return redirect()->route('admin.producto.index')
                        ->with('message', 'El producto fue eliminado exitosamente.!');
    }
}
