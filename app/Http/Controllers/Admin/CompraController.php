<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class CompraController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.compra.index')->only('index');
        $this->middleware('can:admin.compra.create')->only('create', 'store');
        $this->middleware('can:admin.compra.edit')->only('edit', 'update');
        $this->middleware('can:admin.compra.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalCompras = Compra::where('estado', 1)->count();             
        $compras = Compra::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.compra.index', compact('compras', 'totalCompras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.compra.create');
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
            'proveedor_id' => 'required|integer|exists:cliente,id',
            'monto_total' => 'required|numeric|min:0',
        ],
        [
            'proveedor_id.required' => 'El campo nombre de proveedor es obligatorio',
        ]);

        Compra::create($request->only(
            'proveedor_id','monto_total'
        ));
        return redirect()->route('admin.compra.index')->with('message', 'La Compra fue registrada exitosamente.!');
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
        $compra = Compra::findOrFail($id);

        $inventarios = Inventario::where('compra_id', $id)->where('estado', 1)->orderBy('id','desc')->get(); //aquí es para mostrar mi vista de la compra (EL INVENTARIO REALIZADO DE CADA COMPRA)

        $productos = Producto::where('estado', 1)->orderBy('id','desc')->get(); //Producto para mis input de inventario

        return view('admin.compra.edit',compact('compra', 'inventarios', 'productos'));
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
            'proveedor_id' => 'required|integer|exists:proveedor,id',
            'monto_total' => 'required|numeric|min:0',
        ],
        [
            'proveedor_id.required' => 'El campo nombre del proveedor es obligatorio',
        ]);

        $compra = Compra::findOrFail($id);
        $datos = $request->only(['proveedor_id','monto_total']);
        $compra->update($datos);
        return redirect()->route('admin.compra.index')->with('message', 'La Compra fue actualizada exitosamente.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $compra = Compra::findOrFail($id);
        $compra->estado = 0;
        $compra->save();
    
        return redirect()->route('admin.compra.index')
                        ->with('message', 'La compra fue eliminada exitosamente.!');
    }

    public function buscar(Request $request)
    {
        $query = $request->get('query');
        
        $proveedores = Proveedor::where('estado', 1)
            ->whereHas('persona', function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                    ->orWhere('apellido', 'LIKE', "%{$query}%");
            })
            ->with('persona')
            ->get();
        
        return response()->json($proveedores);
    }
}
