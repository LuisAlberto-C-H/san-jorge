<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.roles.index')->only('index');
        $this->middleware('can:admin.roles.create')->only('create', 'store');
        $this->middleware('can:admin.roles.edit')->only('edit', 'update');
        $this->middleware('can:admin.roles.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
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
            'name' => 'required|string|max:60',
        ],[
            'name' => 'El campo nombre es obligatorio'
        ]);

        $role = Role::create($request->all());
        $role->permissions()->sync($request->permissions); //recuperamos relacion, y asignamos distintos permisos al nuevo rol
        return redirect()->route('admin.roles.index')->with('message', 'El rol fue creado exitosamente...!');
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
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $permisosAsignados = $role->permissions->pluck('id')->toArray(); // Permisos asignados al rol actual

        return view('admin.roles.edit', compact('role', 'permissions', 'permisosAsignados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:60',
        ],[
            'name' => 'El campo nombre de rol es obligatorio'
        ]);

        $role->update($request->all());
        $role->permissions()->sync($request->permissions);   //recuperamos esa relacion, y con esta linea asignamos distints permisos al nuevo rol  //SYNC ESTE ROL CON ESTOS PERMISOS MARCADOS
        return redirect()->route('admin.roles.edit', $role)->with('message', 'El rol fue actualizado exitosamente...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('message', 'El rol fue eliminado exitosamente...!');
        
    }
}
