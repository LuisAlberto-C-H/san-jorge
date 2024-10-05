@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-4  d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px; padding-top:5px; padding-bottom:5px;"><i class="fas fa-shopping-cart"></i> / Agregar compra</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:70px; padding-right:70px;">

        <div class="pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.compra.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form id="formCompra" action="{{ route('admin.compra.store') }}" method="POST">
            
            @csrf
            
            <div class="row">
                <div class="col-5">
                    <div class="form-group position-relative">
                        <label for="proveedor">Nombre del proveedor:</label>
                        
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text borde-input" id="search-icon">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                    
                            <input id="proveedor" name="proveedor" class="form-control @error('proveedor_id') is-invalid @enderror borde-input" type="text" placeholder="Buscar Proveedor..." autocomplete="off" style="height: 35px !important;>
                            
                            <input type="hidden" name="proveedor_id" id="proveedor_id">
                        </div>
                
                        <div id="proveedor-lista" class="list-group position-absolute w-100" style="z-index: 100;"></div>
                        
                        @error('proveedor_id')
                            <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="col-5 ml-3">
                    <div class="form-group">
                        <label for="monto_total">Monto total:</label>
                        <div class="input-group">
                            <div class="input-group-prepend" style="height: 35px;">
                                <span class="input-group-text borde-input">Bs.</span>
                            </div>
                            <input class="form-control @error('monto_total') is-invalid @enderror focus-input borde-input" type="number" step="0.01" min="0" name="monto_total" value="{{ old('monto_total') }}" placeholder="Ingrese monto total..." autocomplete="off" style="height: 35px !important;">
                        </div>
                        @error('monto_total')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
            </div>
            
            <div class="py-4"></div>

            <hr>
            <div class="form-group" >
                <button type="submit" class="btn btn-primary btn-md float-left">
                    <i class="fas fa-save fa-sm mr-2"></i>Guardar
                </button>
                <button type="button" class=" ml-3 btn btn-danger" onclick="confirmarCancelar()">Cancelar</button>
            </div>
        </form>
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

        .borde-input {
            border-color: #AAAAAA;
            outline: none;
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
            if (confirm("¿Estás seguro de que deseas cancelar el registro?")) {
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
@stop