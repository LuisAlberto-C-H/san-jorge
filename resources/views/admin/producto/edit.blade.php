@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-4  d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px; padding-top:5px; padding-bottom:5px;"><i class="fas fa-cube"></i> / Editar producto</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:70px; padding-right:70px;">

        <div class="pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.producto.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form class="mt-2"  id="formProductos" action="{{ route('admin.producto.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-6">
                    <label for="tipo_producto_id">Tipo de Producto:</label>

                    <select class="form-control tipo_productoSelect2" name="tipo_producto_id" id="tipo_producto_id" style="width: 100%;">
                        <option value="" selected>Seleccione al tipo de producto perteneciente...</option>
                        
                        @foreach ($tipo_productos as $tipo_producto)
                            <option value="{{ $tipo_producto->id }}" {{ $producto->tipo_producto_id == $tipo_producto->id ? 'selected' : '' }}>{{ $tipo_producto->nombre }}</option>
                        @endforeach
                    </select>

                    @error('tipo_producto_id')
                        <p style="color: red; font-size: 1.0em;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="col-6">
                    <div class="form-group">
                        <label for="nombre">Nombre del producto:</label>
                        <input class="form-control @error('nombre') is-invalid @enderror focus-input mi-input capitalizar-primera-letra" type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" placeholder="ingrese nombre del producto..." autocomplete="off">
                        @error('nombre')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror focus-input mi-input capitalizar-primera-letra" name="descripcion" placeholder="Ingrese descripción del producto..." autocomplete="off">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion')
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
                <button type="button" class=" ml-3 btn btn-danger" onclick="confirmarCancelar()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('css')
    @parent

    <style>
        /* hr {
            border: none;
            border-top: 2px dotted #888;
            height: 0px;
            margin-top: 5px;
            scroll-margin-block: 5px;
        } */

        
        .focus-input:focus {
            outline: none;
            border-color: #1e6a8d; /* Un tono más fuerte al enfocar */
            box-shadow: 0px 0px 5px rgba(31, 120, 180, 0.5); /* Sombra sutil en azul celeste */
            background-color: #ffffff; /* Cambia el fondo al blanco en foco */
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

        .mi-input {
            border-color: #AAAAAA; /* Cambia el color del borde */
            outline: none;
        }
    </style>

    
    
@stop
    
@section('js')
    @parent

        <script>
            $(document).ready(function() {
                $('#formProductos').on('keydown', function(event) {
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
            $(document).ready(function() {
                $('.tipo_productoSelect2').select2({
                    placeholder: 'Seleccione al tipo de producto perteneciente...',
                    widht: '100%',

                });
            });
        </script>

        <script>
            function confirmarCancelar() {
                if (confirm("¿Estás seguro de que deseas cancelar la edición?")) {
                    window.location.href = "{{ route('admin.producto.index') }}";
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
@stop