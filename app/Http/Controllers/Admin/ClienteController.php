<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Persona;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalClientes = Cliente::where('estado', 1)->count();             
        $clientes = Cliente::where('estado', 1)->orderBy('id','desc')->get();
        return view('admin.cliente.index', compact('clientes', 'totalClientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cliente.create');
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
            'razon_social' => 'nullable|string|max:255',
            'nit' => 'required|string|max:255',
        ],
        [
            'persona_id.required' => 'El campo nombre de cliente es obligatorio',
        ]);

        Cliente::create($request->only(
            'persona_id','razon_social','nit'
        ));
        return redirect()->route('admin.cliente.index')->with('message', 'El Cliente fue registrado exitosamente.!');
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
        $cliente = Cliente::findOrFail($id);
        return view('admin.cliente.edit',compact('cliente'));
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
            'razon_social' => 'nullable|string|max:255',
            'nit' => 'required|string|max:255',
        ],
        [
            'persona_id.required' => 'El campo nombre de cliente es obligatorio',
        ]);

        $cliente = Cliente::findOrFail($id);
        $datos = $request->only(['persona_id','razon_social','nit']);
        $cliente->update($datos);
        return redirect()->route('admin.cliente.index')->with('message', 'El cliente fue actualizado exitosamente.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->estado = 0;
        $cliente->save();
    
        return redirect()->route('admin.cliente.index')
                        ->with('message', 'El cliente fue eliminado exitosamente.!');
    }

    public function buscar(Request $request)
    {
        $query = $request->get('query');
    
        $personas = Persona::where('estado', 1)
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                ->orWhere('apellido', 'LIKE', "%{$query}%");
            })
            ->get();
        
        return response()->json($personas);
    }

    
}
