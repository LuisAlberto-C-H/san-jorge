<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{   
    public function __construct()
    {
        $this->middleware('can:admin.users.index')->only('index');
        $this->middleware('can:admin.users.create')->only('create', 'store');
        $this->middleware('can:admin.users.edit')->only('edit', 'update');
        $this->middleware('can:admin.users.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::where('estado', 1)
                        ->orderBy('id', 'desc')->get();
        $totalUsuarios = User::where('estado', 1)->count();
        return view('admin.users.index', compact('usuarios','totalUsuarios'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $personas = Persona::where('estado',1)->get();
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
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
            'persona_id' => ['required', 'integer', 'exists:persona,id'],
            'rol_id' => ['required','integer'],
            'name' => ['required', 'string', 'max:255','unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $user = User::create([
            'persona_id' => $request->persona_id,
            'rol_id' => $request->rol_id,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,

        ]);

        $role = Role ::findById($request->rol_id);
        $user->assignRole($role);

        return redirect()->route('admin.users.index')->with('message', 'Usuario creado exitosamente!');
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
        $user = User::findOrFail($id);

        $roles = Role::all();
        return view('admin.users.edit',compact('user', 'roles'));
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
        $user = User::findOrFail($id);

        $request->validate([
            'persona_id' => ['required', 'integer', 'exists:persona,id'],
            'rol_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update([
            'persona_id' => $request->persona_id,
            'rol_id' => $request->rol_id,
            'name' => $request->name,
            'email' => $request->email
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $role = Role::findById($request->rol_id);
        $user->syncRoles($role);
        return redirect()->route('admin.users.index')->with('message', 'Usuario actualizado exitosamente..!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->estado = 0;
        $usuario->save();
    
        return redirect()->route('admin.users.index')
                        ->with('message', 'Usuario eliminado exitosamente..!');
    }
    
}
