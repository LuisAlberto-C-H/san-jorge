<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detalle_venta;
use App\Models\Inventario;
use Illuminate\Http\Request;

class Detalle_ventaController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.detalle_venta.index')->only('index');
        $this->middleware('can:admin.detalle_venta.create')->only('create', 'store');
        $this->middleware('can:admin.detalle_venta.edit')->only('edit', 'update');
        $this->middleware('can:admin.detalle_venta.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'venta_id' => 'required|exists:venta,id',
            'inventario_id' => 'required|exists:inventario,id',
            'servicio_id' => 'required|exists:servicio,id',
            'cantidad' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        // Buscar el inventario seleccionado
        $inventario = Inventario::findOrFail($request->inventario_id);

        // Verificar si hay suficiente stock
        if ($inventario->stock < $request->cantidad) {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock para este producto.']);
        }

        // Crear un nuevo detalle de venta
        $detalleVenta = new Detalle_venta();
        $detalleVenta->venta_id = $request->venta_id;
        $detalleVenta->inventario_id = $request->inventario_id;
        $detalleVenta->servicio_id = $request->servicio_id;
        $detalleVenta->cantidad = $request->cantidad;
        $detalleVenta->subtotal = $request->subtotal;

        // Guardar el detalle de venta
        $detalleVenta->save();

        // Restar la cantidad comprada del stock del inventario
        $inventario->stock -= $request->cantidad;
        $inventario->save();

        return redirect()->back()->with('message', 'El detalla de venta registrado exitosamente y stock actualizado.');
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
            'venta_id' => 'required|exists:venta,id',
            'inventario_id' => 'required|exists:inventario,id',
            'servicio_id' => 'required|exists:servicio,id',
            'cantidad' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $detalleVenta = Detalle_venta::findOrFail($id);

        // Buscar el inventario actual del producto seleccionado
        $inventario = Inventario::findOrFail($request->inventario_id);

        // Calcular la diferencia entre la cantidad anterior y la nueva
        $diferenciaCantidad = $request->cantidad - $detalleVenta->cantidad;

        // Verificar si hay suficiente stock en caso de que la nueva cantidad sea mayor
        if ($diferenciaCantidad > 0 && $inventario->stock < $diferenciaCantidad) {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock para este producto.']);
        }

        // Ajustar el stock del inventario
        $inventario->stock -= $diferenciaCantidad;
        $inventario->save();

        // Actualizar los campos del detalle de venta
        $detalleVenta->venta_id = $request->venta_id;
        $detalleVenta->inventario_id = $request->inventario_id;
        $detalleVenta->servicio_id = $request->servicio_id;
        $detalleVenta->cantidad = $request->cantidad;
        $detalleVenta->subtotal = $request->subtotal; 
        $detalleVenta->save();

        return redirect()->back()->with('message', 'El detalle de venta ha sido actualizado correctamente y el stock ajustado.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detalle_venta = Detalle_venta::findOrFail($id);
        
        $inventario = Inventario::findOrFail($detalle_venta->inventario_id);
        
        $inventario->stock += $detalle_venta->cantidad;
        $inventario->save();
        
        $detalle_venta->estado = 0;
        $detalle_venta->save();
        
        return redirect()->back()->with('message', 'Registro de Detalle de venta eliminado y stock ajustado exitosamente.');

    }
}
