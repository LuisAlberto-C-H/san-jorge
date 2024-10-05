<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.proveedor.index')->only('index');
        $this->middleware('can:admin.proveedor.create')->only('create', 'store');
        $this->middleware('can:admin.proveedor.edit')->only('edit', 'update');
        $this->middleware('can:admin.proveedor.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalProveedores = Proveedor::where('estado', 1)->count();             
        $proveedores = Proveedor::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.proveedor.index', compact('proveedores', 'totalProveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proveedor.create');
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
            'persona_id' => 'required|integer|exists:persona,id',
            'razon_social' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
        ],
        [
            'persona_id.required' => 'El campo nombre de proveedor es obligatorio',
        ]);

        Proveedor::create($request->only(
            'persona_id','razon_social','nit'
        ));
        return redirect()->route('admin.proveedor.index')->with('message', 'El Proveedor fue registrado exitosamente.!');
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
        $proveedor = Proveedor::findOrFail($id);
        return view('admin.proveedor.edit',compact('proveedor'));
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
            'persona_id' => 'required|integer|exists:persona,id',
            'razon_social' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
        ],
        [
            'persona_id.required' => 'El campo nombre de cliente es obligatorio',
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $datos = $request->only(['persona_id','razon_social','nit']);
        $proveedor->update($datos);
        return redirect()->route('admin.proveedor.index')->with('message', 'El Proveedor fue actualizado exitosamente.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->estado = 0;
        $proveedor->save();
    
        return redirect()->route('admin.proveedor.index')
                        ->with('message', 'El Proveedor fue eliminado exitosamente.!');
    }
}
