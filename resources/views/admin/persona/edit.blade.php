@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-4 d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px;padding-top:5px; padding-bottom:5px;"><i class="fas fa-user"></i> / Editar persona</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:65px; padding-right:65px;">

        <div class="pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.persona.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form id="formPersonas" action="{{ route('admin.persona.update', $persona->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-2">
                <div class="col-4">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input class="form-control @error('nombre') is-invalid @enderror focus-input borde-input capitalizar-primera-letra" type="text" name="nombre" value="{{ $persona->nombre }}" placeholder="Ingrese el nombre de la persona..." autocomplete="off">
                        @error('nombre')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input class="form-control @error('apellido') is-invalid @enderror focus-input borde-input capitalizar-primera-letra" type="text" name="apellido" value="{{ $persona->apellido }}" placeholder="Ingrese el apellido de la persona..." autocomplete="off">
                        @error('apellido')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="ci">Cédula de Identidad:</label>
                        <input class="form-control @error('ci') is-invalid @enderror focus-input borde-input" type="text" name="ci" value="{{ $persona->ci }}" placeholder="Ingrese cédula de identidad..." autocomplete="off">
                        @error('ci')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- <hr> --}}
            <div class="row mt-1">
                <div class="col-4">
                    <div class="form-group">
                        <label for="correo_personal">Correo:</label>
                        <input class="form-control @error('correo_personal') is-invalid @enderror focus-input borde-input" type="text" name="correo_personal" value="{{ $persona->correo_personal }}" placeholder="Ingrese correo..." autocomplete="off">
                        @error('correo_personal')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input class="form-control @error('celular') is-invalid @enderror focus-input borde-input" type="text" name="celular" value="{{ $persona->celular }}" placeholder="Ingrese número de celular..." autocomplete="off">
                        @error('celular')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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

        .borde-input {
            border-color: #AAAAAA;
            outline: none;
        }
    </style>
    
@stop
    
@section('js')
    @parent

    <script>
        $(document).ready(function() {
            $('#formPersonas').on('keydown', function(event) {
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
                window.location.href = "{{ route('admin.persona.index') }}";
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