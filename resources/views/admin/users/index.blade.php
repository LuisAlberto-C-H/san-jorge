@extends('layouts.app')

@section('cuerpo_contenido')
    
    <div class="pt-3 mb-3"></div>

    <div class="card titulo" style="background-color:#007bff;">
        <div class="d-flex justify-content-center py-1">
            <h4 style="color: #ffffff; font-weight:600; padding-top:7px;">SECCIÓN USUARIOS</h4>
        </div>
    </div>

    <div class="py-3 d-flex justify-content-end">
        <a href="{{ route('admin.users.create') }}">
            <button style="background-color: #00bcd4" class="btn btn-sm text-white"><i class="fas fa-solid fa-plus mr-2"></i>AGREGAR NUEVA USUARIO.</button>
        </a>
    </div>

    <div class="card table-responsive">
        <div class="card-body">
            <table id="personas" class="table table-striped table-bordered table-hover table-sm">
                <thead style="background-color:#007bff" class="text-white">
                    <tr>
                        <th style="text-align: center;">NRO.</th>
                        <th style="text-align: center;">ROL</th>
                        <th style="text-align: center;">PERSONA</th>
                        <th style="text-align: center;">NOMBRE DE USUARIO</th>
                        <th style="text-align: center;">EMAIL</th>
                        <th style="text-align: center;">CONTRASEÑA</th>
                        <th style="text-align: center;">OPCIONES</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($usuarios as $index => $usuario)
                        <tr>
                            <td style="text-align: center;">{{ $totalUsuarios - $index }}</td>
                            <td style="text-align: center;">{{ $usuario->getRoleNames()->first() ?? 'Sin rol' }}</td>
                            <td style="text-align: center;">{{ $usuario->persona->nombre }} {{ $usuario->persona->apellido }}</td>
                            <td style="text-align: center;">{{ $usuario->name }}</td>
                            <td style="text-align: center;">{{ $usuario->email }}</td>
                            <td style="text-align: center;">{{ $usuario->password }}</td>

                            <td style="text-align: center; vertical-align: middle;">
                                <a style="margin-right: 5px;" class="btn btn-sm btn-warning" href="{{ route('admin.users.edit', $usuario) }}" title="Modificar"><i class="fas fa-pencil-alt"></i></a>

                                @can('admin.users.destroy')
                                    <form action="{{ route('admin.users.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');" style="display: inline-block; margin: 0;">
                                        @csrf
                                        @method('DELETE')

                                        <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pb-5"></div>
@endsection

@section('css')
    @parent

    <style>
        .table-responsive {
        overflow-x: auto;
    }

    table {
        table-layout: fixed;
        width: 100%;
    }
    </style>
@stop

@section('js')
    @parent

    @if(session()->has('message'))
        <script>
            $(document).ready(function() {
                toastr.success("{{ session('message') }}");
            });
        </script>
    @endif
    
    @if($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        </script>
    @endif

    <script>

        $(document).ready(function(){
            $('#personas').DataTable({
                "language": {
                    "search" : "Buscar:",
                    "lengthMenu" : "Mostrar _MENU_ registros por página",
                    "info" : "Mostrando página _PAGE_ de _PAGES_",
                    "zeroRecords": "No se encontraron resultados",
                    "infoEmpty": "Mostrando 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                },
                "order": [[0, "desc"]],
                "autoWidth": false,
                "columns": [
                    { "width": "7%" },
                    { "width": "15%" },
                    { "width": "15%" },
                    { "width": "13%" },
                    { "width": "15%" },
                    { "width": "25%" },
                    { "width": "10%" }
                ]
            });
        });
    </script>
@stop

