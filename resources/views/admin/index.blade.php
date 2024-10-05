@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    <h1>PANEL ADMINISTRATIVO</h1>

    @hasSection('content_header_title')
        <h1 class="text-muted">
            
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif

        </h1>
    @endif
@stop


@section('content')
    
    <p>Bienvenido al panel Administrativo...</p>

    @yield('content_body')
@stop

{{-- @section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop --}}

@push('js')
<script>

    $(document).ready(function() {
        // Add your common script logic here...
    });

</script>
@endpush

@push('css')
    <style type="text/css">

        /*
        .card-header {
            border-bottom: none;
        }
        .card-title {
            font-weight: 600;
        }
        */

        /* Personalizar ancho del toast */
        #toast-container > .toast {
                width: 400px;
            }

            /* Personalizar el tamaño de la fuente */
            #toast-container > .toast-success {
                font-size: 18px;
            }

            /* Personalizar la duración del toast */
            #toast-container > .toast-success .toast-message {
                font-size: 18px;
                word-wrap: break-word;
                white-space: normal;
            }

            /*
            .card-header {
                border-bottom: none;
            }
            .card-title {
                font-weight: 600;
            }
            */
            .navbar-primary {
            background-color: #0821B9 !important;
            }

            .navbar-primary .nav-link i {
                color: white !important;
            }

            .nav-link.dropdown-toggle {
                color: white !important; /* Cambia el color del texto a blanco */
            }

            .content-wrapper {
                    background-color: #E5E5E5 ;
            }

            /*estilo header navbar  */
            .nav-header {
                border-top: 1px solid #bbbaba;
                border-bottom: 1px solid #bbbaba;
                padding-bottom: 5px;
                margin-bottom: 5px;
                font-weight: 600;
            }

            .sidebar-mini .nav-sidebar > .nav-header {
                white-space: normal !important;
            }

            /* color de seccion logo */
            .brand-link {
            background-color: #575757 !important;
            }
    </style>
@endpush