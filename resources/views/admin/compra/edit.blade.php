@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-3 d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px; padding-top:5px; padding-bottom:5px;"><i class="fas fa-shopping-cart"></i> / Editar compra</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:70px; padding-right:70px;">

        <div class="pt-3 pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.compra.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form id="formCompra" action="{{ route('admin.compra.update', $compra->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-6" style="padding-right: 20px;">
                    <div class="form-group position-relative">
                        <label for="proveedor">Nombre del proveedor:</label>
                        
                        <div class="input-group"> <!-- Grupo del input -->
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="search-icon">
                                    <i class="fas fa-search"></i> <!-- Ícono de búsqueda -->
                                </span>
                            </div>
                    
                            <input id="proveedor" name="proveedor" class="form-control @error('proveedor_id') is-invalid @enderror mi-input" type="text" placeholder="Buscar Proveedor..." autocomplete="off" value="{{ old('proveedor', $compra->proveedor->persona->nombre . ' ' . $compra->proveedor->persona->apellido) }}" style="height: 30px !important;">
                            
                            <input type="hidden" name="proveedor_id" id="proveedor_id" value="{{ old('proveedor_id', $compra->proveedor->id) }}"> <!-- Para almacenar el ID del cliente seleccionado -->
                        </div>
                
                        <div id="proveedor-lista" class="list-group position-absolute w-100" style="z-index: 100;"></div> <!-- Donde se mostrarán los resultados -->
                        
                        @error('proveedor_id')
                            <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="col-6" style="padding-left: 20px;">
                    <div class="form-group">
                        <label for="monto_total">Monto total:</label>
                        <div class="input-group">
                            <div class="input-group-prepend" style="height: 30px;">
                                <span class="input-group-text borde-input">Bs.</span>
                            </div>
                            <input class="form-control @error('monto_total') is-invalid @enderror focus-input mi-input" type="number" step="0.01" min="0" name="monto_total" placeholder="Ingrese monto total..." autocomplete="off" value="{{ old('monto_total', $compra->monto_total ?? '') }}" style="height: 30px !important;">
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


    <div class="pb-2 pt-2 text-center mt-5 position-relative" style="background-color: #0821B9; color:#FFFFFF; margin-left: 30px; margin-right:30px;">
        <h5 style="font-weight: bold; margin: 0;">SECCIÓN INVENTARIO</h5>

        <button style="background-color: #00bcd4; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);" class="btn btn-sm text-white" data-toggle="modal" data-target="#crearModal">
            <i class="fas fa-solid fa-plus mr-2"></i>AGREGAR REGISTRO DE LA COMPRA.
        </button>
    </div>
    
    <div class="card" style="margin-left: 30px; margin-right:30px;">
        <div class="card-body">

            

            <table class="table table-sm custom-table table-bordered table-striped">
                <thead style="background-color: #007bff; color:#FFFFFF;">
                    <tr>
                        <th style="text-align: center;">NRO.</th>
                        <th style="text-align: center;">PRODUCTO_ID</th>
                        {{-- <th style="text-align: center;">COMPRA_ID</th> --}}
                        <th style="text-align: center;">PRECIO COMPRA</th>
                        <th style="text-align: center;">PRECIO VENTA</th>
                        <th style="text-align: center;">CANTIDAD COMPRA</th>
                        <th style="text-align: center;">STOCK</th>
                        <th style="text-align: center;">OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = count($inventarios);
                    @endphp

                    @foreach ($inventarios as $inventario)
                        <tr>
                            <td style="text-align: center;">{{ $counter }}</td>

                            <td style="text-align: center;">{{ $inventario->producto->nombre }}</td>
                            <td style="text-align: center;">{{ $inventario->precio_compra }}</td>
                            <td style="text-align: center;">{{ $inventario->precio_venta }}</td>
                            <td style="text-align: center;">{{ $inventario->cantidad_compra }}</td>
                            <td style="text-align: center;">{{ $inventario->stock }}</td>

                            <td style="display:flex; justify-content:center;">

                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editarModal{{ $inventario->id }}" title="Modificar" style="margin-right: 10px;">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>

                                @can('admin.inventario.destroy')
                                    <form action="{{ route('admin.inventario.destroy', $inventario) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                        @csrf
                                        @method('DELETE')

                                        <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-borde-negro btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                            </td>

                            <!-- Modal -->
                            {{-- EDITAR ARTÍCULO --}}
                            <div class="modal fade" id="editarModal{{ $inventario->id }}" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel{{ $inventario->id }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-custom" role="document">
                                    <div class="modal-content">

                                        <!-- Cabecera del modal -->
                                        <div class="modal-header text-white" style="background-color: #007bff; position: relative;">

                                            <h3 class="modal-title" id="editarModalLabel{{ $inventario->id }}" style="flex-grow: 1; text-align: center;">EDITAR REGISTRO DE INVENTARIO</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 10px; top: 10px;">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <!-- Contenido del modal -->
                                        <div class="modal-body pt-4 pb-4" style="padding-left: 40px; padding-right:40px; background-color:#e9ecef;">

                                            <form action="{{ route('admin.inventario.update',$inventario->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <hr>

                                                <input type="hidden" name="compra_id" value="{{ old('compra_id', $inventario->compra_id) }}">

                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="form-group">
                                                            <label for="producto_id">Producto:</label>
                                                            
                                                            <select class="form-control select2 @error('producto_id') is-invalid @enderror" name="producto_id" style="width: 100%;">
                                
                                                                <option value="" selected>Seleccione el producto...</option>
                                                                @foreach ($productos as $producto)
                                                                    <option value="{{ $producto->id }}" {{ $inventario->producto_id == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                                                                @endforeach
                                                            </select>
                                
                                                            @error('producto_id')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="cantidad_compra">Cantidad de producto:</label>
                                                            <input class="form-control @error('cantidad_compra') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="cantidad_compra" placeholder="Ingrese cantidad de compra del producto..." autocomplete="off" value="{{ old('cantidad_compra', $inventario->cantidad_compra) }}">
                                
                                                            @error('cantidad_compra')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row">   
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="precio_compra">Precio de compra:</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Bs.</span>
                                                                </div>

                                                                <input class="form-control @error('precio_compra') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="precio_compra" placeholder="Ingrese el precio de compra del producto..." autocomplete="off" value="{{ old('precio_compra', $inventario->precio_compra) }}">
                                                            </div>

                                                            @error('precio_compra')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="precio_venta">Precio de venta:</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Bs.</span>
                                                                </div>
                                                                <input class="form-control @error('precio_venta') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="precio_venta" placeholder="Ingrese el precio de venta del producto..." autocomplete="off" value="{{ old('precio_venta', $inventario->precio_venta) }}">
                                                            </div>
                                                            @error('precio_venta')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="stock">Stock:</label>
                                                            <input class="form-control @error('stock') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="stock" autocomplete="off" value="{{ old('stock', $inventario->stock) }}" readonly>
                                
                                                            @error('stock')
                                                                <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                                            @enderror
                                                        </div>
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

                        </tr>
                        @php
                            $counter--;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




<!-- Modal -->
    {{-- CREAR ARTÍCULO --}}
    <div class="modal fade" id="crearModal" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-custom" role="document">
            <div class="modal-content">

                <!-- Cabecera del modal -->
                <div class="modal-header text-white" style="background-color: #007bff; position: relative;">

                    <h3 class="modal-title" id="crearModalLabel" style="flex-grow: 1; text-align: center;">AGREGAR REGISTRO AL INVENTARIO</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 10px; top: 10px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="modal-body pt-4 pb-4" style="padding-left: 40px; padding-right:40px; background-color:#e9ecef;">

                    <form id="formInventario" action="{{ route('admin.inventario.store') }}" method="POST">
                        @csrf
                        <hr>

                        <input type="hidden" name="compra_id" value="{{ $compra->id }}">

                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="producto_id">Producto:</label>
                                    
                                    <select class="form-control select2 @error('producto_id') is-invalid @enderror" name="producto_id" style="width: 100%;">
        
                                        <option value="" selected>Seleccione el producto...</option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>{{ $producto->nombre }}</option>
                                        @endforeach
                                    </select>
        
                                    @error('producto_id')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="cantidad_compra">Cantidad de producto:</label>
                                    <input class="form-control @error('cantidad_compra') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="cantidad_compra" placeholder="Ingrese cantidad de compra del producto..." autocomplete="off" value="{{ old('cantidad_compra') }}">
        
                                    @error('cantidad_compra')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">   
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="precio_compra">Precio de compra:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Bs.</span>
                                        </div>

                                        <input class="form-control @error('precio_compra') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="precio_compra" placeholder="Ingrese el precio de compra del producto..." autocomplete="off" value="{{ old('precio_compra') }}">
                                    </div>

                                    @error('precio_compra')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="precio_venta">Precio de venta:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                        <input class="form-control @error('precio_venta') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="precio_venta" placeholder="Ingrese el precio de venta del producto..." autocomplete="off" value="{{ old('precio_venta') }}">
                                    </div>
                                    @error('precio_venta')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="stock">Stock:</label>
                                    <input class="form-control @error('stock') is-invalid @enderror mi-input focus-input" type="number" min="0" step="1" name="stock" autocomplete="off" value="{{ old('stock') }}" readonly>
        
                                    @error('stock')
                                        <p class="invalid-feedback" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                                    @enderror
                                </div>
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

<div class="pb-5"></div>    
@endsection

@section('css')
    @parent

    <style>
        
        .focus-input:focus {
            outline: none;
            border-color: #1e6a8d; /* Un tono más fuerte al enfocar */
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5); /* Sombra sutil en azul celeste */
            background-color: #ffffff; /* Cambia el fondo al blanco en foco */
        }

        /* Cuando el input está en foco */
        #proveedor:focus {
            outline: none;
            border-color: #1e6a8d; /* Un tono más fuerte al enfocar */
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5); /* Sombra sutil en azul celeste */
            background-color: #ffffff; /* Cambia el fondo al blanco en foco */
        }

       /* Lista de resultados */
        #proveedor-lista {
            background-color: #eef7fd; /* Fondo claro similar a Select2 */
            /* border: 1px solid #ddd; */
            border-bottom: 1px solid #1E6A8D;
            border-radius: 0.25rem;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* margin-top: 5px; Espacio entre el input y la lista */
        }

        /* Estilo de los elementos en la lista */
        #proveedor-lista .proveedor-item {
            padding: 6px 10px 6px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            background-color: #FFFFFF; /* Fondo claro */
            color: #2c7cb7; /* Texto celeste oscuro */

        }

        /* Al pasar el mouse sobre un elemento */
        #proveedor-lista .proveedor-item:hover {
            background-color: #bcdef8; /* Fondo más claro al hacer hover */
            color: #0d5b9a; /* Texto más oscuro en hover */
        }

        /* Hacer que la lista se mantenga en su lugar */
        .position-absolute {
            position: absolute;
        }

        /* ---------TABLE------------ */
        .custom-table {
            width: 100%; /* Ajusta el ancho de la tabla */
            margin: 0 auto; /* Centrar la tabla */
            font-size: 1.0em; /* Tamaño de fuente reducido */
            /*font-size: 0.85em;  Tamaño de fuente reducido */
        }

        /* Reducir padding en las celdas */
        .custom-table td, .custom-table th {
            padding: 5px;
        }
        /* ----------------- */
        .card-body {
            padding: 0; /* Cancelar el padding */
        }

        .mi-input {
            border-color: #AAAAAA; /* Cambia el color del borde */
            outline: none;
        }

        .form-control{
            /* height: 30px !important; */
        }

        .modal-dialog-custom {
        max-width: 70%; /* Ajusta el valor según tus necesidades */
        }

        
        /* -------INPUT SELECT2 PERSONALIZADO-------- */
        .select2-container .select2-selection--single {
            height: 38px; 
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px; /* Alinea la flecha verticalmente */
        }

        /* Estilos al hacer focus en select2 */
        .select2-container .select2-selection--single:focus,
        .select2-container--default .select2-selection--single.select2-selection__rendered:focus {
            border-color: #1e6a8d; /* Un tono más fuerte al enfocar */
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5); /* Sombra azul celeste */
            outline: none; /* Remueve cualquier borde por defecto de enfoque */
        }

        .input-group-prepend .input-group-text {
            border-color: #AAAAAA; /* Cambia el color del borde */
        }

    </style>

    
    
@stop
    
@section('js')
    @parent

        <script>
            $(document).ready(function() {
                // Evento keyup en el campo de búsqueda de cliente
                $('#proveedor').on('keyup', function() {
                    let query = $(this).val();
                    let dropdown = $('#proveedor-lista');
        
                    if (query.length > 2) { // Solo buscar cuando hay más de 2 caracteres
                        $.ajax({
                            url: "{{ route('proveedores.buscar') }}", // Ruta del controlador
                            type: "GET",
                            data: {'query': query}, // Enviar el query al servidor
                            success: function(data) {
                                let items = '<ul class="list-group">';
                                
                                // Verifica si hay resultados
                                if (data.length > 0) {
                                    data.forEach(function(proveedor) {
                                        items += '<li class="list-group-item proveedor-item" data-id="' + proveedor.id + '">' + proveedor.persona.nombre + ' ' + proveedor.persona.apellido + '</li>';
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
                $(document).on('click', '.proveedor-item', function() {
                    let nombre = $(this).text();
                    let proveedorId = $(this).data('id');
                    $('#proveedor').val(nombre); // Poner el nombre del cliente seleccionado en el input
                    $('#proveedor_id').val(proveedorId); // Guardar el ID en el campo oculto
                    $('#proveedor-lista').slideUp(); // Cerrar la lista de sugerencias con animación
                });
            });
        </script>
    

        <script>
            $(document).ready(function() {
                $('#formCompra').on('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            });
        </script>

    <script>
        function confirmarCancelar() {
            if (confirm("¿Estás seguro de que deseas cancelar la edición?")) {
                window.location.href = "{{ route('admin.compra.index') }}";
            }
        }
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

        {{----------- OBETENER EL STOCK DEL PRODUCTO--------}} 
        <script type="text/javascript">
            $(document).ready(function () {
                // Escuchar cambios en el select del producto
                $('select[name="producto_id"]').on('change', function () {
                    var productoId = $(this).val(); // Obtener el ID del producto seleccionado
        
                    // Si se ha seleccionado un producto
                    if (productoId) {
                        // Realizar una solicitud AJAX para obtener el stock del producto
                        $.ajax({
                            url: '/admin/obtener-stock/' + productoId,
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                // Actualizar el campo de stock con el valor devuelto
                                $('input[name="stock"]').val(data.stock);
                            },
                            error: function () {
                                alert('Error al obtener el stock del producto');
                            }
                        });
                    } else {
                        // Si no hay producto seleccionado, vaciar el campo de stock
                        $('input[name="stock"]').val('');
                    }
                });
            });
        </script>

        <script>
            $('#editarModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botón que activó el modal
                var id = button.data('id');
                var producto_id = button.data('producto_id');
                var precio_compra = button.data('precio_compra');
                var precio_venta = button.data('precio_venta');
                var cantidad_compra = button.data('cantidad_compra');
                var stock = button.data('stock');

                var modal = $(this);
                modal.find('.modal-body #inventario_id').val(id);
                modal.find('.modal-body #producto_id').val(producto_id).trigger('change'); // Para select2
                modal.find('.modal-body #precio_compra').val(precio_compra);
                modal.find('.modal-body #precio_venta').val(precio_venta);
                modal.find('.modal-body #cantidad_compra').val(cantidad_compra);
                modal.find('.modal-body #stock').val(stock);

                var form = modal.find('#formEditarInventario');
                form.attr('action', '/admin/inventario/update' + id); // Cambiar la acción del formulario
                
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#formInventario').on('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            });
        </script>
@stop