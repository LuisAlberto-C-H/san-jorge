<x-guest-layout>

    {{-- jquery --}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    


    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="relative mt-4 mb-4">
            <x-input-label for="persona_search" :value="__('Buscar Persona')" />
        
            <!-- Contenedor para el input y el ícono -->
            <div class="relative">
                <input id="persona_search" name="persona_search" type="text" placeholder="{{ __('Buscar nombre...') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500" required autofocus autocomplete="off" />
        
                <!-- Ícono de búsqueda dentro del campo -->
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-500"></i>
                </div>
            </div>
        
            <x-input-error :messages="$errors->get('persona_search')" class="mt-2" />
        
            <!-- Campo oculto para el ID de la persona -->
            <input type="hidden" id="persona_id" name="persona_id" required />
            <x-input-error :messages="$errors->get('persona_id')" class="mt-2" />
        
            <!-- Mensaje de "No se encontraron resultados" -->
            <div id="no-results" class="text-red-500 mt-2"></div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre de usuario')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(document).ready(function() {
            $('#persona_search').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '{{ route("personas.search") }}', // Ruta donde se buscarán las personas
                        dataType: 'json',
                        data: {
                            q: request.term // Término de búsqueda
                        },
                        success: function(data) {
                            if (data.length === 0) {
                                // Si no hay resultados, mostramos el mensaje en rojo con el término buscado
                                $('#no-results').show().html(`No se encontraron resultados para "<strong>${request.term}</strong>"`).css('color', 'red');
                            } else {
                                // Si hay resultados, ocultamos el mensaje
                                $('#no-results').hide();
                            }
                            response($.map(data, function(item) {
                                return {
                                    label: item.nombre + ' ' + item.apellido,
                                    value: item.nombre + ' ' + item.apellido,
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $('#persona_id').val(ui.item.id); // Guardar el ID en el input oculto
                    $('#no-results').hide(); // Ocultar el mensaje al seleccionar una opción
                }
            });
    
            // Ocultamos el mensaje de "No se encontraron resultados" al iniciar
            $('#no-results').hide();
        });
    </script>

</x-guest-layout>
