@extends('layouts.layoutApp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboardStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooltip.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalSuccess.css') }}">
    <link rel="stylesheet" href="{{ asset('css/configuracionStyle.css') }}">
    @stack('styles')
@endpush

@section('content')
    <div class="dashboard-container" 
         data-routes='{
             "ventasIntermediadas": "{{ route('ventasIntermediadas.create') }}",
             "canjes": "{{ route('canjes.create') }}",
             "recompensas": "{{ route('recompensas.create') }}",
             "tecnicos": "{{ route('tecnicos.create') }}",
             "configuracion": "{{ route('configuracion') }}",
             "perfil": "{{ route('profile.edit') }}"
         }'>

        <!-- aside section-->
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="{{ asset('images/logo_DIMACOF.png') }}" alt="logo_Dimacof">
                </div>
            </div>

            <div class="sidebar">
                <a href="{{ route('ventasIntermediadas.create') }}" 
                    class="{{ Request::routeIs('ventasIntermediadas.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">request_page</span>
                    <h5>Ventas <br>Intermediadas</h5>
                </a>
                
                <a href="{{ route('canjes.create') }}" 
                    class="{{ Request::routeIs('canjes.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">currency_exchange</span>
                    <h5>Canjes</h5>
                </a>
                
                <a href="{{ route('recompensas.create') }}" 
                    class="{{ Request::routeIs('recompensas.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">handyman</span>
                    <h5>Recompensas</h5>
                </a>

                <a href="{{ route('tecnicos.create') }}" 
                    class="{{ Request::routeIs('tecnicos.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">groups</span>
                    <h5>Técnicos</h5>
                </a>

                <a href="{{ route('configuracion') }}" 
                    class="{{ Request::routeIs('configuracion') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span>
                    <h5>Configuración</h5>
                </a>

                <!-- Formulario de Logout -->
                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" class="btnLogout" id="logoutLink">
                        <span class="material-symbols-outlined">logout</span>
                        <h5>Cerrar Sesión</h5>
                    </a>
                </form>
            </div>
        </aside>

        <!-- header section-->
        <div class="header">
            <div class="left_menu_close" id="menu_toggle_button">
                <span class="material-symbols-outlined">arrow_back_ios</span>
            </div>
            
            <div class="profile">
                <a href="#" class="notification_container">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="notification_count">14</span>
                </a>
                <div class="profile-photo">
                  <img src="{{ asset('images/profile_picture.png') }} " alt="1_admin_picture">
                </div>
                <div class="user_options_List" id="user_options_List">
                    <div class="div-input-select" id="idUserDivList">
                        <label id="labelDesplegable" type="text-autocomplete" placeholder="Admin" onclick="toggleOptionsUser('userList')">
                            Administador
                            <span class="material-symbols-outlined">keyboard_arrow_down</span>
                        </label>
                        <ul class="select-items-userList" id="userList">
                            <li onclick="linkOption('perfil')">Perfil</li>
                            <li onclick="linkOption('#')">Opción 2</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- main section -->
        <main class="main">
            @yield('main-content')
        </main>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/dashboardScript.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"> </script>
    <script src="{{ asset('js/datatables.js') }}"> </script>
    <script src="{{ asset('js/datatableConfig.js') }}"> </script>
    <script src="{{ asset('js/modals.js') }}"> </script>
    <script>
        // Aplicar configuración al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark-mode');
            }
            document.documentElement.classList.add(`font-${localStorage.getItem('fontSize') || 'medium'}`);
            document.documentElement.style.setProperty('--button-color', localStorage.getItem('accentColor') || '#007bff');
        });
    </script>
@endpush