<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.inventario.index')->only('index');
        $this->middleware('can:admin.inventario.create')->only('create', 'store');
        $this->middleware('can:admin.inventario.edit')->only('edit', 'update');
        $this->middleware('can:admin.inventario.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener el último registro activo de cada producto
        $inventarios = Inventario::select('inventario.*')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('id'))
                    ->from('inventario as i')
                    ->where('i.estado', 1)
                    ->whereRaw('i.id = (SELECT id FROM inventario WHERE producto_id = i.producto_id AND estado = 1 ORDER BY id DESC LIMIT 1)');
            })
            ->with('producto')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.inventario.index', compact('inventarios'));
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
        // Obtener el producto y la cantidad comprada

        $productoId = $request->input('producto_id');
        $cantidadCompra = $request->input('cantidad_compra');

        // Obtener el stock actual para el producto, si existe
        $ultimoRegistro = Inventario::where('producto_id', $productoId)   //capturamos el registro si existe el producto //2
                            ->where('estado', 1)
                            ->orderBy('id', 'desc')
                            ->first();
        
        // Si existe el producto, sumar la cantidad comprada al stock actual, //¿le decimos hay un registro?

        $nuevoStock = $ultimoRegistro ? $ultimoRegistro->stock + $cantidadCompra : $cantidadCompra; //capturamos el stock ya sumado 

        // Crear un nuevo registro en el inventario
        Inventario::create([
            'producto_id' => $productoId,
            'compra_id' => $request->input('compra_id'),
            'precio_compra' => $request->input('precio_compra'),
            'precio_venta' => $request->input('precio_venta'),
            'stock' => $nuevoStock, // stock actualizado
            'cantidad_compra' => $cantidadCompra,
        ]);

        return redirect()->back()->with('message', 'Inventario registrado y stock actualizado exitosamente.!');

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
        // // Encuentra el registro por ID
        // $inventario = Inventario::find($id);

        // // Devuelve los datos del registro en formato JSON
        // return response()->json($inventario);
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
        // Validar los datos
        $request->validate([
            'producto_id' => 'required',
            'compra_id' => 'required',
            'cantidad_compra' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
        ]);

        // Obtener el inventario actual (antes de la edición)
        $inventario = Inventario::findOrFail($id);
        
        // Almacenar la cantidad anterior
        $cantidadAnterior = $inventario->cantidad_compra;

        // Obtener el stock actual
        $stockActual = $inventario->stock;

        // Obtener la nueva cantidad ingresada
        $nuevaCantidad = $request->input('cantidad_compra');

        // Calcular la diferencia entre la cantidad nueva y la anterior
        $diferencia = $nuevaCantidad - $cantidadAnterior;

        // Actualizar el stock en función de la diferencia
        $nuevoStock = $stockActual + $diferencia;

        // Asegurarse de que el stock nunca sea negativo
        if ($nuevoStock < 0) {
            return back()->withErrors(['stock' => 'No puedes reducir el stock por debajo de 0.']);
        }

        // Actualizar el inventario con los nuevos valores
        $inventario->producto_id = $request->input('producto_id');
        $inventario->compra_id = $request->input('compra_id');
        $inventario->cantidad_compra = $nuevaCantidad;
        $inventario->precio_compra = $request->input('precio_compra');
        $inventario->precio_venta = $request->input('precio_venta');
        $inventario->stock = $nuevoStock;

        // Guardar el inventario actualizado
        $inventario->save(); // Asegurarse de usar `save()` para evitar la creación de un nuevo registro

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('message', 'Compra registrada y stock actualizado correctamente.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->estado = 0;
        $inventario->save();
    
        return redirect()->back()->with('message', 'Registro de inventario eliminado y stock actualizado exitosamente.!');

    }


    public function obtenerStock($productoId)
    {
        // Obtener el último registro del inventario de ese producto
        $inventario = Inventario::where('producto_id', $productoId)
                                ->where('estado', 1)
                                ->orderBy('id', 'desc')
                                ->first();

        // Si hay registros previos, devolver el stock, de lo contrario, devolver stock 0
        $stockActual = $inventario ? $inventario->stock : 0;

        return response()->json(['stock' => $stockActual]);
    }
}
