@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-3  d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px; padding-top:5px; padding-bottom:5px;"><i class="fas fa-receipt"></i> / Editar Venta</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:70px; padding-right:70px;">

        <div class="pt-3 pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.venta.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form id="formVenta" action="{{ route('admin.venta.update',$venta->id ) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-6" style="padding-right: 20px;">
                    <div class="form-group position-relative">
                        <label for="cliente">Nombre de cliente:</label>
                        
                        <div class="input-group"> <!-- Grupo del input -->
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="search-icon">
                                    <i class="fas fa-search"></i> <!-- Ícono de búsqueda -->
                                </span>
                            </div>
                    
                            <input id="cliente" name="cliente" class="form-control @error('cliente_id') is-invalid @enderror mi-input" type="text" placeholder="Buscar Cliente..." autocomplete="off" value="{{ old('cliente', $venta->cliente->persona->nombre . ' ' . $venta->cliente->persona->apellido) }}" style="height: 30px !important;">
                            
                            <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id', $venta->cliente->id) }}"> <!-- Para almacenar el ID del cliente seleccionado -->
                        </div>
                
                        <div id="cliente-lista" class="list-group position-absolute w-100" style="z-index: 100;"></div> <!-- Donde se mostrarán los resultados -->
                        
                        @error('cliente_id')
                            <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="col-6" style="padding-left: 20px;">
                    <div class="form-group">
                        <label for="monto_total">Monto total:</label>

                        <div class="input-group">
                            <div class="input-group-prepend" style="height: 30px;">
                                <span class="input-group-text">Bs.</span>
                            </div>

                            <input class="form-control @error('monto_total') is-invalid @enderror focus-input mi-input" type="number" step="0.01" min="0" name="monto_total" value="{{ old('monto_total', $venta->monto_total ?? '') }}" placeholder="Ingrese monto total..." autocomplete="off" style="height: 30px !important;">
                        </div>

                        @error('monto_total')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="pb-1"></div>

            <hr>
            <div class="form-group" >
                <button type="submit" class="btn btn-primary btn-sm float-left">
                    <i class="fas fa-save fa-sm mr-2"></i>Actualizar
                </button>
                <button type="button" class=" ml-3 btn btn-sm btn-danger" onclick="confirmarCancelar()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

{{------------SECCION DETALLE DE VENTA------------}}

<div class="pb-2 pt-2 text-center mt-5 position-relative" style="background-color: #0821B9; color:#FFFFFF; margin-left: 30px; margin-right:30px;">
    <h5 style="font-weight: bold; margin: 0;">SECCIÓN DETALLE DE VENTA</h5>
    <button style="background-color: #00bcd4; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);" class="btn btn-sm text-white" data-toggle="modal" data-target="#crearModal">
        <i class="fas fa-solid fa-plus mr-2"></i>AGREGAR REGISTRO DE LA VENTA.
    </button>
</div>

<div class="card" style="margin-left: 30px; margin-right:30px;">
    <div class="card-body">

        <table class="table table-sm custom-table table-bordered table-striped">
            <thead style="background-color: #007bff; color:#FFFFFF;">
                <tr>
                    <th style="text-align: center;">NRO.</th>
                    <th style="text-align: center;">SERVICIO</th>
                    <th style="text-align: center;">PRODUCTO</th>
                    <th style="text-align: center;">CANTIDAD</th>
                    <th style="text-align: center;">SUBTOTAL</th>
                    <th style="text-align: center;">OPCIONES</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $counter = 1;
                @endphp

                @foreach ($detalle_ventas as $detalle_venta)
                        <tr>
                            <td style="text-align: center;">{{ $counter }}</td>
                            <td style="text-align: center;">{{ $detalle_venta->servicio->nombre }}</td>
                            <td style="text-align: center;">{{ $detalle_venta->inventario->producto->nombre }}</td>
                            <td style="text-align: center;">{{ $detalle_venta->cantidad }}</td>
                            <td style="text-align: center;">{{ $detalle_venta->subtotal }}</td>

                            <td style="display:flex; justify-content:center;">

                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarModal{{ $detalle_venta->id }}" title="Modificar" style="margin-right: 10px;">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>

                                @can('admin.detalle_venta.destroy')
                                <form action="{{ route('admin.detalle_venta.destroy', $detalle_venta) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')

                                    <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-borde-negro btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                                @endcan
                            </td>

                            {{---------------MODAL EDITAR DETALLE DE VENTA ---------------}}
                            <div class="modal fade" id="editarModal{{ $detalle_venta->id }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel{{ $detalle_venta->id }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-custom" role="document">
                                    <div class="modal-content">

                                        <!-- Cabecera del modal -->
                                        <div class="modal-header text-white" style="background-color: #007bff; position: relative;">

                                            <h3 class="modal-title" id="editarModalLabel{{ $detalle_venta->id }}" style="flex-grow: 1; text-align: center;">EDITAR REGISTRO DE DETALLE DE VENTA</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 10px; top: 10px;">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <!-- Contenido del modal -->
                                        <div class="modal-body pt-4 pb-4" style="padding-left: 40px; padding-right:40px; background-color:#e9ecef;">

                                            <form action="{{ route('admin.detalle_venta.update',$detalle_venta->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <hr>

                                                    <input type="hidden" name="venta_id" value="{{ old('venta_id', $detalle_venta->venta_id) }}">

                                                    <div class="">
                                                        <div class="form-group">
                                                            <label for="servicio_id">Servicio:</label>
                                                            
                                                            <select class="form-control select2 @error('servicio_id') is-invalid @enderror" name="servicio_id" style="width: 100%;">
                                
                                                                <option value="" selected>Seleccione el servicio...</option>

                                                                @foreach ($servicios as $servicio)

                                                                    <option value="{{ $servicio->id }}" {{ $detalle_venta->servicio_id == $servicio->id ? 'selected' : '' }}>{{ $servicio->nombre }} / {{ $servicio->tipo_servicio->nombre }}</option>

                                                                @endforeach
                                                            </select>
                                
                                                            @error('servicio_id')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                        
                                                    <div class="">
                                                        {{-- <div class="form-group">
                                                            <label for="inventario_id">Producto:</label>
                                                            
                                                            <select class="form-control select2 @error('inventario_id') is-invalid @enderror" name="inventario_id" style="width: 100%;">
                                
                                                                <option value="" selected>Seleccione el servicio...</option>

                                                                @foreach ($inventarios as $inventario)

                                                                    <option value="{{ $inventario->id }}" {{ $detalle_venta->inventario_id == $inventario->id ? 'selected' : '' }}>{{ $inventario->producto->nombre }}</option>

                                                                @endforeach
                                                            </select>
                                
                                                            @error('servicio_id')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div> --}}
                                                        <div class="form-group">
                                                            <label for="inventario_id">Producto:</label>
                                                            
                                                            <select class="form-control select2 @error('inventario_id') is-invalid @enderror" name="inventario_id" style="width: 100%;">
                                                                <option value="" selected>Seleccione el producto...</option>
                                                                @foreach ($inventarios as $inventario)
                                                                    <option value="{{ $inventario->id }}" {{ $detalle_venta->inventario_id == $inventario->id ? 'selected' : '' }}>
                                                                        {{ $inventario->producto->nombre }} / {{ $inventario->producto->tipo_producto->nombre }}
                                                                    </option>
                                                                    
                                                                @endforeach
                                                            </select>

                                                            {{-- <select class="form-control select2 @error('inventario_id') is-invalid @enderror" name="inventario_id" style="width: 100%;">
                                                                <option value="" selected>Seleccione el inventario...</option>
                                                            
                                                                @foreach ($inventarios as $inventario)
                                                                    <option value="{{ $inventario->id }}" {{ (int)$detalle_venta->inventario_id === (int)$inventario->id ? 'selected' : '' }}>
                                                                        {{ $inventario->producto->nombre }} -- {{ $inventario->created_at }}
                                                                    </option>
                                                                @endforeach
                                                            </select> --}}
                                                        
                                                            @error('inventario_id')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                        
                                                    <div class="">
                                                        <div class="form-group">
                                                            <label for="cantidad">Cantidad de producto:</label>
                                                            <input class="form-control @error('cantidad') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="cantidad" placeholder="Ingrese cantidad de producto..." autocomplete="off" value="{{ old('cantidad',$detalle_venta->cantidad) }}">
                                
                                                            @error('cantidad')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="">
                                                        <div class="form-group">
                                                            <label for="subtotal">Subtotal:</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Bs.</span>
                                                                </div>
                        
                                                                <input class="form-control @error('subtotal') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="subtotal" placeholder="Ingrese el subtotal de la venta..." autocomplete="off" value="{{ old('subtotal', $detalle_venta->subtotal) }}">
                                                            </div>
                        
                                                            @error('subtotal')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                <hr>
                                                <!-- Pie del modal -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary w-25"><i class="fas fa-save fa-sm mr-2"></i>Actualizar </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- @can('admin.persona.destroy')
                                <td>
                                    
                                </td>
                            @endcan --}}
                            
                        </tr>
                        @php $counter++; @endphp
                    @endforeach
                
            </tbody>
        </table>
    </div>
</div>


<!-- Modal -->
    {{-- CREAR DETALLE DE VENTA --}}
    <div class="modal fade" id="crearModal" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-custom" role="document">
            <div class="modal-content">

                <!-- Cabecera del modal -->
                <div class="modal-header text-white" style="background-color: #007bff; position: relative;">

                    <h3 class="modal-title" id="crearModalLabel" style="flex-grow: 1; text-align: center;">AGREGAR DETALLE DE VENTA</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 10px; top: 10px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="modal-body pt-4 pb-4" style="padding-left: 40px; padding-right:40px; background-color:#e9ecef;">

                    <form action="{{ route('admin.detalle_venta.store') }}" method="POST" style="margin-left:40px; margin-right:40px;">
                        @csrf
                        <hr>

                            <input type="hidden" name="venta_id" value="{{ $venta->id }}">

                            <div class="">
                                <div class="form-group">
                                    <label for="servicio_id">Servicio:</label>
                                    
                                    <select class="form-control select2 @error('servicio_id') is-invalid @enderror" name="servicio_id" style="width: 100%;">
        
                                        <option value="" selected>Seleccione el servicio...</option>
                                        @foreach ($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>{{ $servicio->nombre }} / {{ $servicio->tipo_servicio->nombre }}</option>
                                        @endforeach
                                    </select>
        
                                    @error('servicio_id')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label for="inventario_id">Producto:</label>
                                    
                                    <select class="form-control select2 @error('inventario_id') is-invalid @enderror" name="inventario_id" style="width: 100%;">
        
                                        <option value="" selected>Seleccione el producto...</option>

                                        @foreach ($inventarios as $inventario)
                                            <option value="{{ $inventario->id }}" {{ old('inventario_id') == $inventario->id ? 'selected' : '' }}>{{ $inventario->producto->nombre }} / {{ $inventario->producto->tipo_producto->nombre }}</option>
                                        @endforeach
                                    </select>

                                    
        
                                    @error('servicio_id')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label for="cantidad">Cantidad de producto:</label>
                                    <input class="form-control @error('cantidad') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="cantidad" placeholder="Ingrese cantidad de producto..." autocomplete="off" value="{{ old('cantidad') }}">
        
                                    @error('cantidad')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="">
                                <div class="form-group">
                                    <label for="subtotal">Subtotal:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Bs.</span>
                                        </div>

                                        <input class="form-control @error('subtotal') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="subtotal" placeholder="Ingrese el subtotal de la venta..." autocomplete="off" value="{{ old('subtotal') }}">
                                    </div>

                                    @error('subtotal')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        <hr>
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

@endsection

@section('css')
    @parent

    <style>

        .focus-input:focus {
            outline: none;
            border-color: #1e6a8d;
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5);
            background-color: #ffffff;
        }

        #cliente:focus {
            outline: none;
            border-color: #1e6a8d;
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5);
            background-color: #ffffff;
        }

        /* Lista de resultados */
        #cliente-lista {
            background-color: #eef7fd;
            /* border: 1px solid #ddd; */
            border-left: 1px solid #1E6A8D;
            border-right: 1px solid #1E6A8D;
            border-bottom: 1px solid #1E6A8D;
            border-radius: 0.25rem;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* margin-top: 5px; Espacio entre el input y la lista */
        }

        /* Estilo de los elementos en la lista */
        #cliente-lista .cliente-item {
            padding: 6px 10px 6px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            background-color: #FFFFFF;
            color: #2c7cb7;
        }

        #cliente-lista .cliente-item:hover {
            background-color: #bcdef8;
            color: #0d5b9a;
        }

        /* Hacer que la lista se mantenga en su lugar */
        .position-absolute {
            position: absolute;
        }

        /* ---------TABLE------------ */
        .custom-table {
            width: 100%;
            margin: 0 auto; /* Centrar la tabla */
            font-size: 1.0em; /* Tamaño de fuente */
            /*font-size: 0.85em; */
        }

        /* Reducir padding en las celdas */
        .custom-table td, .custom-table th {
            padding: 5px;
        }
        /* ----------------- */
        .card-body {
            padding: 0; /* Cancelar padding */
        }

        .mi-input {
            border-color: #AAAAAA;
            outline: none;
        }

        .modal-dialog-custom {
        max-width: 60%;
        }

        /* --------borde input complemento--------*/
        .input-group-prepend .input-group-text {
            border-color: #AAAAAA;
        }

        /* -------INPUT SELECT2 PERSONALIZADO-------- */
        .select2-container .select2-selection--single {
            height: 38px; 
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px; /* Alinear flecha verticalmente */
        }

        /* Estilos al hacer focus en select2 */
        .select2-container .select2-selection--single:focus,
        .select2-container--default .select2-selection--single.select2-selection__rendered:focus {
            border-color: #1e6a8d;
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5);
            outline: none; /* Remover cualquier borde por defecto de enfoque */
        }

    </style>

@stop
    
@section('js')
    @parent

        <script>
            $(document).ready(function() {
                // Evento keyup en el campo de búsqueda de cliente
                $('#cliente').on('keyup', function() {
                    let query = $(this).val();
                    let dropdown = $('#cliente-lista');
        
                    if (query.length > 2) { // Solo buscar cuando hay más de 2 caracteres
                        $.ajax({
                            url: "{{ route('clientes.buscar') }}", // Ruta del controlador
                            type: "GET",
                            data: {'query': query}, // Enviar el query al servidor
                            success: function(data) {
                                let items = '<ul class="list-group">';
                                
                                // Verifica si hay resultados
                                if (data.length > 0) {
                                    data.forEach(function(cliente) {
                                        items += '<li class="list-group-item cliente-item" data-id="' + cliente.id + '">' + cliente.persona.nombre + ' ' + cliente.persona.apellido + '</li>';
                                    });
                                } else {
                                    // Mostrar mensaje cuando no se encuentran resultados
                                    items += '<li class="list-group-item text-danger">No se encontraron resultados para "' + query + '"</li>';
                                }
                                
                                items += '</ul>';
                                dropdown.html(items).slideDown(); // Desplegar con animación
                            },
                            error: function() {
                                dropdown.html('<li class="list-group-item text-danger">Error al realizar la búsqueda</li>').slideDown();
                            }
                        });
                    } else {
                        dropdown.slideUp(); // Desaparecer con animación si la búsqueda es muy corta
                    }
                });
        
                // Seleccionar el cliente de la lista
                $(document).on('click', '.cliente-item', function() {
                    let nombre = $(this).text();
                    let clienteId = $(this).data('id');
                    $('#cliente').val(nombre);
                    $('#cliente_id').val(clienteId);
                    $('#cliente-lista').slideUp();
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#formVenta').on('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            });
        </script>

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
            function confirmarCancelar() {
                if (confirm("¿Estás seguro de que deseas cancelar la edición?")) {
                    window.location.href = "{{ route('admin.venta.index') }}";
                }
            }
        </script>

        <script>
            $('body').on('shown.bs.modal', '.modal', function() {
                $(this).find('.select2').each(function() {
                    var placeholder = $(this).attr('placeholder');
                    
                    $(this).select2({
                        dropdownParent: $(this).closest('.modal'),
                        width: '100%',
                        placeholder: placeholder,
                    });
                });
            });
        </script>
@stop