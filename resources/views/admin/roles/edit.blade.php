@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-4  d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px; padding-top:5px; padding-bottom:5px;"><i class="fas fa-user-cog"></i>/ Editar Rol</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:70px; padding-right:70px;">

        <div class="pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.roles.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="text-center">
                <div class="form-group">
                    <h5 class="text-center mb-3"><u>Nombre de Rol</u></h5>
                    <input type="text" name="name" class="form-control borde-input focus-input capitalizar-primera-letra" placeholder="Ingrese nombre del nuevo rol..." value="{{ old('name', $role->name) }}">
                    @error('name')
                        <p style="color: red; font-size: 0.9em;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <hr>
            
            @php
                $columnas = 3; // Número de columnas
                $permisosDivididos = array_chunk($permissions->toArray(), ceil(count($permissions) / $columnas)); // Dividir los permisos en 3 columnas
            @endphp
            
            <div class="form-group">
                <h5 class="text-center mt-4"><u>Lista de Permisos</u></h5>

                <h6><u>Asignar al nuevo Rol</u></h6>
                <div class="row mt-4">
                    @foreach ($permisosDivididos as $columnaPermisos)
                        <div class="col-md-4 mb-1">
                            @foreach ($columnaPermisos as $permiso)
                                <div class="custom-control custom-checkbox"  style="padding-bottom: .45rem;">
                                    <input type="checkbox" class="custom-control-input" id="permiso{{ $permiso['id'] }}" name="permissions[]" value="{{ $permiso['id'] }}"
                                    @if(in_array($permiso['id'], $permisosAsignados)) checked @endif>
                                    <label class="custom-control-label" for="permiso{{ $permiso['id'] }}" style="padding-left: 20px;">{{ $permiso['description'] }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <hr>

            <div class="row">
                
            </div>

            <div class="form-group" style="margin-top:30px;" >
                <button type="submit" class="btn btn-primary w-25">
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
        hr {
            border: none;
            border-top: 2px dotted #888;
            height: 0px;
            margin-top: 5px;
            scroll-margin-block: 5px;
        }

         /* Agrandar checkbox */
        .custom-control-input {
            transform: scale(1.5);
        }

        /* Ajustar tamaño de la caja */
        .custom-control-label::before, 
        .custom-control-label::after {
            width: 1.5rem; 
            height: 1.5rem;
            top: 0; /* Centrar caja */
        }

        /* Ajustar tamaño del icono de verif*/
        .custom-control-input:checked ~ .custom-control-label::before {
            background-size: 1.2rem 1.2rem;
        }

        .custom-control-label {
            padding-left: 2rem;
        }

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
                    window.location.href = "{{ route('admin.roles.index') }}";
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