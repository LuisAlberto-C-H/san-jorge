<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Administrador del sistema']);
        $role2 = Role::create(['name' => 'Encargado de ventas']);
        $role3 = Role::create(['name' => 'Encargado de administración']);

        Permission::create(['name' => 'admin.home', 'description' => 'Ver panel de funciones'])->syncRoles([$role1, $role2, $role3]);

        // usuarios
        Permission::create(['name' => 'admin.users.index', 'description' => 'Ver lista de usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.create', 'description' => 'Crear usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.edit', 'description' => 'Editar usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.destroy', 'description' => 'Eliminar usuarios'])->syncRoles([$role1]);

        // roles
        Permission::create(['name' => 'admin.roles.index', 'description' => 'Ver lista de roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.roles.create', 'description' => 'Crear roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.roles.edit', 'description' => 'Editar roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.roles.destroy', 'description' => 'Eliminar roles'])->syncRoles([$role1]);

        //personas
        Permission::create(['name' => 'admin.persona.index', 'description' => 'Ver lista de personas'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.persona.create', 'description' => 'Crear personas'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.persona.edit', 'description' => 'Editar personas'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.persona.destroy', 'description' => 'Eliminar Personas'])->syncRoles([$role1]);

        //clientes
        Permission::create(['name' => 'admin.cliente.index', 'description' => 'Ver lista de clientes'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.cliente.create', 'description' => 'Crear clientes'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.cliente.edit', 'description' => 'Editar clientes'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.cliente.destroy', 'description' => 'Eliminar clientes'])->syncRoles([$role1]);

        //ventas
        Permission::create(['name' => 'admin.venta.index', 'description' => 'Ver lista de ventas'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.venta.create', 'description' => 'Crear ventas'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.venta.edit', 'description' => 'Editar ventas'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.venta.destroy', 'description' => 'Eliminar ventas'])->syncRoles([$role1]);

        //detalle de ventas
        Permission::create(['name' => 'admin.detalle_venta.index', 'description' => 'Ver lista de detalle de ventas'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.detalle_venta.create', 'description' => 'Crear detalle de ventas'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.detalle_venta.edit', 'description' => 'Editar editar detalle de ventas'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.detalle_venta.destroy', 'description' => 'Eliminar detalle de ventas'])->syncRoles([$role1]);

        //servicios
        Permission::create(['name' => 'admin.servicio.index', 'description' => 'Ver lista de servicios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.servicio.create', 'description' => 'Crear servicios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.servicio.edit', 'description' => 'Editar servicios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.servicio.destroy', 'description' => 'Eliminar servicios'])->syncRoles([$role1]);

        //tipo de servicios
        Permission::create(['name' => 'admin.tipo_servicio.index', 'description' => 'Ver lista de tipo de  servicios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.tipo_servicio.create', 'description' => 'Crear tipo de servicios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.tipo_servicio.edit', 'description' => 'Editar tipo de servicios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.tipo_servicio.destroy', 'description' => 'Eliminar tipo de servicios'])->syncRoles([$role1]);

        //proveedores
        Permission::create(['name' => 'admin.proveedor.index', 'description' => 'Ver lista de proveedores'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.proveedor.create', 'description' => 'Crear proveedores'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.proveedor.edit', 'description' => 'Editar proveedores'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.proveedor.destroy', 'description' => 'Eliminar proveedores'])->syncRoles([$role1]);

        //compras
        Permission::create(['name' => 'admin.compra.index', 'description' => 'Ver lista de compras'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.compra.create', 'description' => 'Crear compras'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.compra.edit', 'description' => 'Editar compras'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.compra.destroy', 'description' => 'Eliminar compras'])->syncRoles([$role1]);

        //inventario
        Permission::create(['name' => 'admin.inventario.index', 'description' => 'Ver lista de inventario'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'admin.inventario.create', 'description' => 'Crear registros de inventario'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.inventario.edit', 'description' => 'Editar registros de inventario'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.inventario.destroy', 'description' => 'Eliminar registros de inventario'])->syncRoles([$role1]);

        //productos
        Permission::create(['name' => 'admin.producto.index', 'description' => 'Ver lista de productos'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.producto.create', 'description' => 'Crear productos'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.producto.edit', 'description' => 'Editar productos'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.producto.destroy', 'description' => 'Eliminar productos'])->syncRoles([$role1]);

        //tipo de productos
        Permission::create(['name' => 'admin.tipo_producto.index', 'description' => 'Ver lista de tipo de productos'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.tipo_producto.create', 'description' => 'Crear tipo de productos'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.tipo_producto.edit', 'description' => 'Editar tipo de productos'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.tipo_producto.destroy', 'description' => 'Eliminar tipo de productos'])->syncRoles([$role1]);

    }
}
