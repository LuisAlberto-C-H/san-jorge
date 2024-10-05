@extends('layouts.app')

@section('cuerpo_contenido')

<div class="py-4  d-flex justify-content-end"> 
</div>

<div class="mb-4" style="padding-left:30px; padding-right:30px; ">
    <div style="background-color: #ffffff; color:#434443; padding-left:15px; border-radius:3px; font-size:17px; padding-top:5px; padding-bottom:5px;"><i class="fas fa-fw fa-user-circle"></i> / Editar Usuario</div>
</div>

<div class="" style="padding-left:30px; padding-right:30px; ">
    <div class="card-body" style="background-color: #FFFFFF; padding-left:70px; padding-right:70px;">

        <div class="pb-3">
            <a class="flecha-atras" style="font-size: 20px;" href="{{ route('admin.users.index') }}">
                <span>&#8592;</span>Regresar
            </a>
        </div>

        <form id="formUsuarios" action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="persona_id" style="font-weight: 600;"><i>Buscar nombre de la persona:</i></label>
                        <div class="input-container">
                            <input type="text" id="persona" name="persona" class="form-control focus-input borde-input" autocomplete="off" placeholder="Buscar Nombre..." value="{{ $user->persona->nombre }} {{ $user->persona->apellido }}">

                            <i class="fas fa-search"></i>

                            <div id="persona-lista" class="list-group" style="position: absolute; z-index: 1000; background: white; width: 100%; display: none;"></div>
                        </div>
                        <input type="hidden" id="persona_id" name="persona_id" value="{{ $user->persona_id }}">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="password" style="font-weight: 600;"><i>Contraseña (opcional):</i></label>

                        <input id="password" type="password" name="password" class="form-control focus-input borde-input" autocomplete="new-password" placeholder="Contraseña...">
                        <span style="font-size:0.85em; background-color: rgb(255, 165, 0); color: #000000; padding-left:10px; padding-right:10px;">Contraseña, dejar vacio si no se modificará este dato..</span>

                        @if ($errors->has('password'))
                            <small class="text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>
                </div>

            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="name" style="font-weight: 600;"><i>Nombre de usuario:</i></label>
                        <input id="name" type="text" name="name" class="form-control focus-input borde-input capitalizar-primera-letra" value="{{ old('name', $user->name) }}" autocomplete="off" placeholder="Usuario..." >
                        @if ($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-6">
                    <!-- Confirmar Contraseña -->
                    <div class="form-group">
                        <label for="password_confirmation" style="font-weight: 600;"><i>Confirmar nueva contraseña:</i></label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control focus-input borde-input" autocomplete="new-password"  placeholder="Confirmar nueva contraseña">

                        <span style="font-size:0.85em; background-color: rgb(255, 165, 0); color: #000000; padding-left:10px; padding-right:10px;">Confirmar contraseña, dejar vacio si no se modificará este dato..</span>

                        @if ($errors->has('password_confirmation'))
                            <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="email" style="font-weight: 600;"><i>Correo de usuario:</i></label>
                        <input class="form-control @error('email') is-invalid @enderror focus-input borde-input" type="text" name="email" value="{{ old('email', $user->email) }}" placeholder="Ingrese el correo para el usuario..." autocomplete="off">
                        @error('email')
                                <p class="invalid-feedback" style="color: red; font-size: 0.8em;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="rol_id" style="font-weight: 600; width:100%;"><i>Rol:</i></label>

                        <select id="rol_id" name="rol_id" class="form-control select2-rol" style="width: 100%;" required>
                            <option value="">{{ __('Seleccione un rol') }}</option>
                            @foreach($roles as $rol)

                                <option value="{{ $rol->id }}" {{ $user->rol_id == $rol->id ? 'selected' : '' }}>
                                    {{ $rol->name }}
                                </option>
                            @endforeach
                        </select>
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

        /* --------------- */

        .input-container {
                position: relative;
                width: 100%;
            }
        
            .input-container input {
                width: 100%;
                padding-left: 40px; /* Espacio para el icono */
            }
        
            .input-container .fa-search {
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                color: #aaa;
            }
        
            .list-group {
                border: 1px solid #80BDFF;
                max-height: 200px;
                overflow-y: auto;
                box-shadow: inset 0 0 0 transparent;
            }
        
            .list-group-item {
                cursor: pointer;
                padding: 7px 10px 7px 15px;
            }
        
            .list-group-item:hover {
                background-color: #f0f0f0;
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
    </style>
    
@stop
    
@section('js')
    @parent
    {{-- <script>
        document.getElementById('persona').addEventListener('keyup', function() {
            let term = this.value;
        
            if (term.length > 2) {
                fetch(`/admin/buscar-personas?term=${term}`)
                    .then(response => response.json())
                    .then(data => {
                        let resultados = document.getElementById('resultados_persona');
                        resultados.innerHTML = '';
                        resultados.style.display = 'block';
        
                        if (data.length === 0) {
                            let div = document.createElement('div');
                            div.classList.add('list-group-item');
                            div.innerHTML = `No hay resultados para... <span class="text-red">${term}</span>`;
                            resultados.appendChild(div);
                        } else {
                            data.forEach(item => {
                                let div = document.createElement('div');
                                div.classList.add('list-group-item');
                                div.textContent = `${item.nombre} ${item.apellido}`;
                                div.setAttribute('data-id', item.id);
                                div.addEventListener('click', function() {
                                    document.getElementById('persona').value = this.textContent;
                                    document.getElementById('persona_id').value = this.getAttribute('data-id');
                                    resultados.innerHTML = '';
                                    resultados.style.display = 'none';
                                });
                                resultados.appendChild(div);
                            });
                        }
                    });
            } else {
                document.getElementById('resultados_persona').style.display = 'none';
            }
        }); 
        
        document.addEventListener('click', function(e) {
            if (!document.querySelector('.input-container').contains(e.target)) {
                document.getElementById('resultados_persona').style.display = 'none';
            }
        });
        </script> --}}
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
                $('#formUsuarios').on('keydown', function(event) {
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
                    window.location.href = "{{ route('admin.users.index') }}";
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

        <script>
            $(document).ready(function() {
                $('.select2-rol').select2({
                    placeholder: 'Seleccione el rol...',
                    widht: '100%',
                });
            });
        </script>


@stop