@extends('layouts.app')

@section('cuerpo_contenido')
    
    <div class="pt-3 mb-3"></div>

    <div class="card titulo" style="background-color:#007bff;">
        <div class="d-flex justify-content-center py-1">
            <h4 style="color: #ffffff; font-weight:600; padding-top:7px;">SECCIÓN - TIPO DE SERVICIO</h4>
        </div>
    </div>

    <div class="py-3 d-flex justify-content-end">
            <button style="background-color: #00bcd4" class="btn btn-sm text-white" data-toggle="modal" data-target="#crearModal"><i class="fas fa-solid fa-plus mr-2"></i>AGREGAR NUEVO TIPO DE SERVICIO.</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tipo_servicios" class="table table-striped table-bordered table-hover table-sm">
                <thead style="background-color:#007bff" class="text-white">
                    <tr>
                        <th style="text-align: center;">NRO.</th>
                        <th style="text-align: center;">NOMBRE</th>
                        <th style="text-align: center;">OPCIONES</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tipo_servicios as $index => $tipo_servicio)
                        <tr>
                            <td style="text-align: center;">{{ $totalTipo_servicios - $index }}</td>
                            <td style="text-align: center;">{{ $tipo_servicio->nombre }}</td>

                            <td style="display:flex; justify-content:center;">

                                <button style="margin-right: 10px;" type="button" class="btn btn-sm btn-borde-negro btn-warning" data-toggle="modal" data-target="#editarModal{{ $tipo_servicio->id }}" title="Modificar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>

                                @can('admin.tipo_servicio.destroy')
                                <form action="{{ route('admin.tipo_servicio.destroy', $tipo_servicio) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')

                                    <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-borde-negro btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                                @endcan
                            </td>
                        </tr>

                        <!-- MODAL -->
                        {{-- EDITAR TIPO ANALISIS --}}
                        <div class="modal fade" id="editarModal{{ $tipo_servicio->id }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel{{ $tipo_servicio->id }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-dialog-custom" role="document">

                                <div class="modal-content">
                                    <div class="modal-header text-white" style="background-color:#007bff; position: relative;">

                                        <h3 class="modal-title" id="editarModalLabel{{ $tipo_servicio->id }}" style="flex-grow: 1; text-align: center;">EDITAR TIPO DE SERVICIO</h3>
                                        
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 10px; top: 10px;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pt-4 pb-4" style="padding-left: 35px; padding-right:35px; background-color:#e9ecef;">
                                        <!-- Formulario para Editar el Registro -->
                                        <form action="{{ route('admin.tipo_servicio.update', $tipo_servicio->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                            <div class="form-group">
                                                <label for="nombre">Tipo de servicio:</label>
                                                <input class="form-control @error('nombre') is-invalid @enderror focus-input capitalizar-primera-letra" type="text" name="nombre" value="{{ $tipo_servicio->nombre }}" placeholder="Ingrese el tipo de servicio..." autocomplete="off" style="border: solid 1px #1e6a8d;">

                                                @error('nombre')
                                                    <p class="invalid-feedback" style="color: red; font-size: 1.0em;">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            
                                            <!-- Pie del modal -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                                                <button type="submit" class="btn btn-primary" style="padding-left:30px; padding-right:30px;">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    {{-- CREAR TIPO DE SERVICIO--}}
    <div class="modal fade" id="crearModal" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-custom" role="document">
            <div class="modal-content">

                <!-- Cabecera del modal -->
                <div class="modal-header text-white" style="background-color:#007bff; position: relative;">

                <h3 class="modal-title" id="crearModalLabel" style="flex-grow: 1; text-align: center;">AGREGAR TIPO DE SERVICIO</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 10px; top: 10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <!-- Contenido del modal -->
                <div class="modal-body pt-4 pb-4" style="padding-left: 35px; padding-right:35px; background-color:#e9ecef;">

                    <form class="formTipo_servicio" action="{{ route('admin.tipo_servicio.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="nombre">Tipo de servicio:</label>
                            <input class="form-control @error('nombre') is-invalid @enderror focus-input capitalizar-primera-letra" type="text" name="nombre" placeholder="Ingrese el tipo de servicio..." autocomplete="off" style="border: solid 1px #1e6a8d;">
                            {{-- 558b2f --}}

                            @error('nombre')
                                <p class="invalid-feedback" style="color: red; font-size: 1.0em;">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Pie del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary w-25"><i class="fas fa-save fa-sm mr-2"></i>Guardar </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-5"></div>
@endsection

@section('css')
    @parent
    
    <style>
        .modal-dialog-custom {
            max-width: 60%;
        }

        .focus-input:focus {
            outline: none;
            border-color: #1e6a8d;
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5);
            background-color: #ffffff;
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
            $('#tipo_servicios').DataTable({
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

    <script>
        $(document).ready(function() {
            $('.capitalizar-primera-letra').on('input', function() {
                let value = $(this).val();
                $(this).val(value.charAt(0).toUpperCase() + value.slice(1));
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.formTipo_servicio').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });
        });
    </script>

@stop

