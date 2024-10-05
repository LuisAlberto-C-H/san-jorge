<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Detalle_venta;
use App\Models\Inventario;
use App\Models\Servicio;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.venta.index')->only('index');
        $this->middleware('can:admin.venta.create')->only('create', 'store');
        $this->middleware('can:admin.venta.edit')->only('edit', 'update');
        $this->middleware('can:admin.venta.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalVentas = Venta::where('estado', 1)->count();             
        $ventas = Venta::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.venta.index', compact('ventas', 'totalVentas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.venta.create');
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
            'cliente_id' => 'required|integer|exists:cliente,id',
            'monto_total' => 'required|numeric|min:0',
        ],
        [
            'cliente_id.required' => 'El campo nombre de cliente es obligatorio',
        ]);

        Venta::create($request->only(
            'cliente_id','monto_total'
        ));
        return redirect()->route('admin.venta.index')->with('message', 'La Venta fue registrada exitosamente.!');
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
        $venta = Venta::findOrFail($id);
        $servicios = Servicio::where('estado', 1)->orderBy('id','desc')->get();

        //obtener con eloquent los inventarios y cargar la relación con Producto
        $inventarios = Inventario::where('estado', 1)
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('inventario')
                    ->groupBy('producto_id');
            })
            ->with('producto') // Cargar la relación con Producto
            ->orderBy('created_at', 'desc')
            ->get();

        $detalle_ventas = Detalle_venta::where('venta_id', $venta->id)
        ->where('estado', 1)
        ->orderBy('id', 'desc')
        ->get();
        
        return view('admin.venta.edit',compact('venta', 'servicios', 'inventarios','detalle_ventas'));

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
            'cliente_id' => 'required|integer|exists:cliente,id',
            'monto_total' => 'required|numeric|min:0',
        ],
        [
            'cliente_id.required' => 'El campo nombre de cliente es obligatorio',
        ]);

        $venta = Venta::findOrFail($id);
        $datos = $request->only(['cliente_id','monto_total']);
        $venta->update($datos);
        return redirect()->route('admin.venta.index')->with('message', 'La venta fue actualizada exitosamente.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);
        $venta->estado = 0;
        $venta->save();
    
        return redirect()->route('admin.venta.index')
                        ->with('message', 'La venta fue eliminada exitosamente.!');
    }

    public function buscar(Request $request)
    {
        $query = $request->get('query');
        
        $clientes = Cliente::where('estado', 1)
            ->whereHas('persona', function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                    ->orWhere('apellido', 'LIKE', "%{$query}%");
            })
            ->with('persona')
            ->get();
        
        return response()->json($clientes);
    }

    
}
