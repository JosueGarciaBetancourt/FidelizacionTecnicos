@extends('layouts.layoutApp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboardStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooltip.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalSuccess.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalError.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalConfirm.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalLoading.css') }}">
    <link rel="stylesheet" href="{{ asset('css/configuracionStyle.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalConfirmSolicitudCanje.css') }}">
    @stack('styles')
@endpush

@section('content')
    <div class="dashboard-container" data-routes='{"perfil": "{{ route('usuarios.create') }}", "logout": "{{ route('logout') }}"}'>
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="{{ asset('images/logo_DIMACOF.png') }}" alt="logo_Dimacof">
                </div>
            </div>

            <div class="sidebar">
                <a id="ventasIntermediadasLink" href="{{ route('ventasIntermediadas.create') }}"
                    class="{{ Request::routeIs('ventasIntermediadas.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">request_page</span>
                    <h5>Ventas Intermediadas</h5>
                </a>
                
                <div id="canjesLinkContainer">
                    <a id="canjesLink" href="#" class="{{ Request::routeIs('canjes.create', 'canjes.historial', 'solicitudescanjes.create') ? 'canjesActive' : '' }}">
                        <span class="material-symbols-outlined">currency_exchange</span>
                        <h5>Canjes</h5>
                        <span id="canjesArrowDownSpan" class="material-symbols-outlined">keyboard_arrow_down</span>
                    </a>
                    <div class="select-items-canjes hidden" id="canjesMenu">
                        <a href="{{ route('canjes.registrar') }}" class="{{ Request::routeIs('canjes.registrar') ? 'subLinkActive' : '' }}">• Registrar Canje</a>
                        <a href="{{ route('canjes.historial') }}" class="{{ Request::routeIs('canjes.historial') ? 'subLinkActive' : '' }}">• Ver Historial</a>
                        <a href="{{ route('solicitudescanjes.create') }}" class="{{ Request::routeIs('solicitudescanjes.create') ? 'subLinkActive' : '' }}">• Ver solicitudes desde APP</a>
                    </div>
                </div>
                
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

                <a href="{{ route('oficios.create') }}" 
                    class="{{ Request::routeIs('oficios.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">engineering</span>
                    <h5>Oficios</h5>
                </a>

                <a href="{{ route('configuracion') }}" 
                    class="{{ Request::routeIs('configuracion') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span>
                    <h5>Configuración</h5>
                </a>

                <!-- Formulario de Logout -->
                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" class="btnLogout" id="logoutLink" onclick="handleFormSubmission('logoutLink', 'logoutForm', 2000)">
                        <span class="material-symbols-outlined">logout</span>
                        <h5>Cerrar Sesión</h5>
                    </a>
                </form>
            </div>
        </aside>

        <div class="header">
            <div class="left_menu_close" id="menu_toggle_button">
                <span class="material-symbols-outlined noUserSelect">arrow_back_ios</span>
            </div>
            
            <div class="profile">
                <a href="#" class="notification_container">
                    <span class="material-symbols-outlined noUserSelect">notifications</span>
                    <span class="notification_count noUserSelect">14</span>
                </a>
                <div class="profile-photo">
                <img src="{{ asset('images/profile_picture.png') }} " alt="1_admin_picture">
                </div>
                <div class="user_options_List" id="user_options_List">
                    <div class="div-input-select" id="idUserDivList">
                        <div class="userDropdownContainer" onclick="toggleOptionsUser('userList')">
                            <div id="idUserDropdown">
                                {{ Auth::check() ? Auth::user()->name : 'Invitado' }}
                                <span>{{ Auth::check() ? Auth::user()->email : '' }}</span>
                            </div>
                            <span id="arrowDownUserList" class="material-symbols-outlined noUserSelect">keyboard_arrow_down</span>
                        </div>

                        <ul class="select-items-userList" id="userList">
                            <li onclick="linkOption('perfil'), closeUserList()">Perfil</li>
                            <li id="li-logout" onclick="linkOption('logout'), handleFormSubmission('li-logout', 'logoutForm'), closeUserList()">
                                Cerrar Sesión
                            </li>
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
    <x-modalLoading/>
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