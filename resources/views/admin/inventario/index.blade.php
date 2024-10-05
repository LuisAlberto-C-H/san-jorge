@extends('layouts.app')

@section('cuerpo_contenido')
    
    <div class="pt-3 mb-3"></div>

    <div class="card titulo" style="background-color:#007bff;">
        <div class="d-flex justify-content-center py-1">
            <h4 style="color: #ffffff; font-weight:600; padding-top:7px;">INVENTARIO</h4>
        </div>
    </div>

    <div class="card my-5">
        <div class="card-body">
            <table id="ventas" class="table table-striped table-bordered table-hover table-sm">
                <thead style="background-color:#007bff" class="text-white">
                    <tr>
                        <th style="text-align: center;">NRO.</th>
                        <th style="text-align: center;">TIPO DE PRODUCTO</th>
                        <th style="text-align: center;">PRODUCTO</th>
                        <th style="text-align: center;">CANTIDAD</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $counter = count($inventarios);
                    @endphp
                    @foreach ($inventarios as $index => $inventario)
                        <tr>
                            <td style="text-align: center;">{{ $counter }}</td>
                            <td style="text-align: center;">{{ $inventario->producto->tipo_producto->nombre }}</td>
                            <td style="text-align: center;">{{ $inventario->producto->nombre }}</td>
                            <td style="text-align: center;">{{ $inventario->stock }}</td>
                        </tr>
                        @php $counter--; @endphp
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

    <script>
        $(document).ready(function(){
            $('#ventas').DataTable({
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

