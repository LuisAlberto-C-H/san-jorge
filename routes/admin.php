<?php

use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\CompraController;
use App\Http\Controllers\Admin\Detalle_ventaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\InventarioController;
use App\Http\Controllers\Admin\PersonaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\ProveedorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\Tipo_productoController;
use App\Http\Controllers\Admin\Tipo_servicioController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('', [HomeController::class, 'index'])->middleware('can:admin.home');

Route::resource('personas', PersonaController::class)->names('admin.persona');

Route::resource('clientes', ClienteController::class)->names('admin.cliente');
Route::get('/buscar-personas', [ClienteController::class, 'buscar'])->name('personas.buscar');

Route::resource('ventas', VentaController::class)->names('admin.venta');
Route::get('/buscar-clientes', [VentaController::class, 'buscar'])->name('clientes.buscar');

Route::resource('proveedores', ProveedorController::class)->names('admin.proveedor');

Route::resource('compras', CompraController::class)->names('admin.compra');
Route::get('/buscar-proveedores', [CompraController::class, 'buscar'])->name('proveedores.buscar');

Route::resource('tipo_productos', Tipo_productoController::class)->names('admin.tipo_producto');
Route::resource('tipo_servicios', Tipo_servicioController::class)->names('admin.tipo_servicio');

Route::resource('productos', ProductoController::class)->names('admin.producto');
Route::resource('servicios', ServicioController::class)->names('admin.servicio');

Route::resource('inventarios', InventarioController::class)->names('admin.inventario');
Route::get('/obtener-stock/{producto_id}', [InventarioController::class, 'obtenerStock']);

Route::resource('detalle_venta', Detalle_ventaController::class)->names('admin.detalle_venta');

Route::resource('users', UserController::class)->names('admin.users');

Route::resource('roles', RoleController::class)->names('admin.roles');











