@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-4  d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px; padding-top:5px; padding-bottom:5px;"><i class="fas fa-user-tie"></i> / Editar proveedor</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:60px; padding-right:60px;">

        <div class="pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.proveedor.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form id="formProveedor" action="{{ route('admin.proveedor.update', $proveedor->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-4">
                    <div class="form-group position-relative">
                        <label for="persona">Nombre del proveedor:</label>
                        
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text borde-input" id="search-icon">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                
                            <input id="persona" name="persona" class="form-control @error('persona_id') is-invalid @enderror borde-input" type="text" placeholder="Buscar Persona..." autocomplete="off" value="{{ old('persona', ($proveedor->persona->nombre ?? '') . ' ' . ($proveedor->persona->apellido ?? '')) }}">
                            
                            <input type="hidden" name="persona_id" id="persona_id" value="{{ old('persona_id', $proveedor->persona_id ?? '') }}">
                        </div>
                
                        <div id="persona-lista" class="list-group position-absolute w-100" style="z-index: 100;"></div>
                        
                        @error('persona_id')
                            <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="col-4">
                    <div class="form-group">
                        <label for="razon_social">Razón social:</label>
                        <input class="form-control @error('razon_social') is-invalid @enderror focus-input borde-input capitalizar-primera-letra" type="text" name="razon_social" value="{{ $proveedor->razon_social }}" placeholder="razón social..." autocomplete="off">
                        @error('razon_social')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="nit">NIT:</label>
                        <input class="form-control @error('nit') is-invalid @enderror focus-input borde-input" type="text" name="nit" value="{{ $proveedor->nit }}" placeholder="Ingrese NIT..." autocomplete="off">
                        @error('nit')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="py-4"></div>

            <hr>
            <div class="form-group" >
                <button type="submit" class="btn btn-primary btn-md float-left">
                    <i class="fas fa-save fa-sm mr-2"></i>Actualizar
                </button>
                <button type="button" class="ml-3 btn btn-danger" onclick="confirmarCancelar()">Cancelar</button>
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
            border-color: #1e6a8d; /* Un tono más fuerte al enfocar */
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5); /* Sombra sutil en azul celeste */
            background-color: #ffffff; /* Cambia el fondo al blanco en foco */
        }

        .borde-input {
            border-color: #AAAAAA;
            outline: none;
        }

        /* Cuando el input está en foco */
        #persona:focus {
            outline: none;
            border-color: #1e6a8d;
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5);
            background-color: #ffffff;
        }

        /* Lista de resultados */
        #persona-lista {
            background-color: #eef7fd;
            border-bottom: 1px solid #1E6A8D;
            border-left: 1px solid #1E6A8D;
            border-right: 1px solid #1E6A8D;
            border-radius: 0.25rem;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* margin-top: 5px; Espacio entre el input y la lista */
        }

        /* Estilo de los elementos en la lista */
        #persona-lista .persona-item {
            padding: 6px 10px 6px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            background-color: #FFFFFF;
            color: #2c7cb7;

        }

        /* pasar mouse por encima */
        #persona-lista .persona-item:hover {
            background-color: #bcdef8;
            color: #0d5b9a;
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
            $('#persona').on('keyup', function() {
                let query = $(this).val();
                let dropdown = $('#persona-lista');

                if (query.length > 2) { // Solo buscar cuando hay más de 2 caracteres
                    $.ajax({
                        url: "{{ route('personas.buscar') }}", // Ruta del controlador
                        type: "GET",
                        data: {'query': query}, // Enviar el query al servidor
                        success: function(data) {
                            let items = '<ul class="list-group">';
                            
                            // Verifica si hay resultados
                            if (data.length > 0) {
                                data.forEach(function(persona) {
                                    items += '<li class="list-group-item persona-item" data-id="' + persona.id + '">' + persona.nombre + ' ' + persona.apellido + '</li>';
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

            // Seleccionar el elemento de la lista
            $(document).on('click', '.persona-item', function() {
                let nombre = $(this).text();
                let personaId = $(this).data('id');
                $('#persona').val(nombre); // Poner el nombre seleccionado en el input
                $('#persona_id').val(personaId); // Guardar el ID en el campo oculto
                $('#persona-lista').slideUp(); // Cerrar la lista de sugerencias con animación
            });
        });
        </script>

        <script>
            $(document).ready(function() {
                $('#formProveedor').on('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            });
        </script>

        <script>
            function confirmarCancelar() {
                if (confirm("¿Estás seguro de que deseas cancelar la edición?")) {
                    window.location.href = "{{ route('admin.proveedor.index') }}";
                }
            }
        </script>

        <script>
            $(document).ready(function() {
                $('.capitalizar-primera-letra').on('input', function() {
                    let value = $(this).val();
                    $(this).val(value.charAt(0).toUpperCase() + value.slice(1));
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
@stop