@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

@section('content_header')
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
    @yield('cuerpo_contenido')
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

{{-- BORRE BOOTSTRAP Y JQUERY porque adminlte ya los tiene incluidos - y causó conflictos --}}
{{-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}

{{-- DATATABLE --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap4.js"></script>

    {{-- ------TOAST.JS------- --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{----------SELECT2--------}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        $(document).ready(function() {
            // Add your common script logic here...
        });

    </script>
@endpush

@push('css')
    {{--------- DATATABLE ----------}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap4.css">

    {{----------TOAST.MIN.CSS--------}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{----------SELECT2--------}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style type="text/css">

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
                background-color: #E5E5E5;
        }

        /*estilo header navbar  */
        .nav-header {
            border-top: 1px solid #bbbaba;
            border-bottom: 1px solid #bbbaba;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        /* en caso de texto largo header , se adapte */
        .sidebar-mini .nav-sidebar > .nav-header {
            white-space: normal !important;
        }

        /* color de seccion logo */
        .brand-link {
            background-color: #575757 !important;
            }
    </style>

@endpush