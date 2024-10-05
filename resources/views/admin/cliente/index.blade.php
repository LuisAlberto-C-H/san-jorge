@extends('layouts.app')

@section('cuerpo_contenido')
    
    <div class="pt-3 mb-3"></div>

    <div class="card titulo" style="background-color:#007bff;">
        <div class="d-flex justify-content-center py-1">
            <h4 style="color: #ffffff; font-weight:600; padding-top:7px;">SECCIÓN CLIENTE</h4>
        </div>
    </div>

    <div class="py-3 d-flex justify-content-end">
        <a href="{{ route('admin.cliente.create') }}">
            <button style="background-color: #00bcd4" class="btn btn-sm text-white"><i class="fas fa-solid fa-plus mr-2"></i>AGREGAR NUEVO CLIENTE.</button>
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="clientes" class="table table-striped table-bordered table-hover table-sm">
                <thead style="background-color:#007bff" class="text-white">
                    <tr>
                        <th style="text-align: center;">NRO.</th>
                        <th style="text-align: center;">NOMBRE</th>
                        <th style="text-align: center;">RAZON SOCIAL</th>
                        <th style="text-align: center;">NIT</th>
                        <th style="text-align: center;">OPCIONES</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($clientes as $index => $cliente)
                        <tr>
                            <td style="text-align: center;">{{ $totalClientes - $index }}</td>
                            <td style="text-align: center;">{{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}</td>
                            <td style="text-align: center;">
                                {!! $cliente->razon_social ?: '<span class="badge badge-warning" style="font-size: 0.8rem; padding: 0.5em 0.6em;">/Sin razón social</span>' !!}
                            </td>
                            <td style="text-align: center;">{{ $cliente->nit }}</td>

                            <td style="display:flex; justify-content:center;">
                                <a style="margin-right: 10px;" class="btn btn-sm btn-borde-negro btn-warning" href="{{ route('admin.cliente.edit', $cliente) }}" title="Modificar"><i class="fas fa-pencil-alt"></i></a>

                                @can('admin.cliente.destroy')
                                <form action="{{ route('admin.cliente.destroy', $cliente) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')

                                    <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-borde-negro btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
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
            $('#clientes').DataTable({
                "language": {
                    "search" : "Buscar:",
                    "lengthMenu" : "Mostrar _MENU_ registros por página",
                    "info" : "Mostrando página _PAGE_ de _PAGES_",
                    "zeroRecords": "No se encontraron resultados",
                    "infoEmpty": "Mostrando 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                },
                "order": [[0, "desc"]]
            });
        });
    </script>
@stop

