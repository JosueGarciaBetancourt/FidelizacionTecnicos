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
    @php
        $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
    @endphp

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
                        @if (!$isAsisstantLogged)
                            <a href="{{ route('canjes.registrar') }}" class="{{ Request::routeIs('canjes.registrar') ? 'subLinkActive' : '' }}">• Registrar Canje</a>
                        @endif
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

                <a href="{{ route('configuracion.create') }}" 
                    class="{{ Request::routeIs('configuracion.create') ? 'active' : '' }}">
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
                <div class="notification_wrapper">
                    <a href="#" class="notification_container" onclick="toggleNotificationMessage(this)">
                        <span class="material-symbols-outlined noUserSelect bell" title="Notificaciones">notifications</span>
                        <span class="notification_count noUserSelect">3</span>
                    </a>
                    
                    <div class="notification_panel">
                        <div class="notification_header">
                            <h3>Notificaciones</h3>
                            <a href="#" class="mark_all_read">Marcar todo como leído</a>
                        </div>
                        
                        <ul class="notification_list">
                            <li class="notification_item unread">
                                <div class="notification_icon request_page">
                                    <span class="material-symbols-outlined">request_page</span>
                                </div>
                                <div class="notification_content">
                                    <p class="notification_title">Nueva solicitud de canje</p>
                                    <p class="notification_desc">SOLICANJ-00003 recibida desde app móvil</p>
                                    <p class="notification_time">Hace 5 minutos</p>
                                </div>
                                <div class="notification_actions">
                                    {{-- <button class="approve_btn">Aprobar</button> --}}
                                    <a href="{{ route('solicitudescanjes.create') }}" class="review_btn">Revisar</a>
                                </div>
                            </li>
                            
                            <li class="notification_item unread">
                                <div class="notification_icon timer">
                                    <span class="material-symbols-outlined">timer</span>
                                </div>
                                <div class="notification_content">
                                    <p class="notification_title">Venta cerca de agotarse</p>
                                    <p class="notification_desc">F001-00000072 está a 7 días de agotarse</p>
                                    <p class="notification_time">Hace 2 horas</p>
                                </div>
                                <div class="notification_actions">
                                    <a href="{{ route('ventasIntermediadas.create') }}" class="review_btn">Revisar</a>
                                </div>
                            </li>
                            
                            <li class="notification_item read">
                                <div class="notification_icon workspace_premium">
                                    <span class="material-symbols-outlined">workspace_premium</span>
                                </div>
                                <div class="notification_content">
                                    <p class="notification_title">Cambio de rango</p>
                                    <p class="notification_desc">77043114|Josué Daniel García Betancourt subió a rango Oro</p>
                                    <p class="notification_time">Hace 3 horas</p>
                                </div>
                                <div class="notification_actions">
                                    <a href="{{ route('tecnicos.create') }}" class="review_btn">Revisar</a>
                                </div>
                            </li>
                        </ul>
                        
                        {{--
                        <div class="notification_footer">
                            <a href="/notifications" class="view_all">Ver todas las notificaciones</a>
                        </div>
                        --}}
                    </div>
                </div>

                <div class="profile-photo" title="Foto de perfil">
                <img src="{{ asset('images/profile_picture.png') }}" alt="1_admin_picture">
                </div>

                <div class="user_options_List" title="Opciones de usuario" id="user_options_List">
                    <div class="div-input-select" id="idUserDivList">
                        <div class="userDropdownContainer" onclick="toggleOptionsUser('userList', 'arrowDownUserList')">
                            <div id="idUserDropdown">{{ Auth::check() ? Auth::user()->name : 'Invitado' }}
                                <span>{{ Auth::check() ? Auth::user()->email : '' }}</span>
                            </div>
                            <span id="arrowDownUserList" class="material-symbols-outlined noUserSelect">keyboard_arrow_down</span>
                        </div>

                        <ul class="select-items-userList" id="userList">
                            <li onclick="linkOption('perfil'), closeUserList()">
                                Perfil de usuario
                            </li>
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
    <script src="{{ asset('js/userUtils.js') }}"></script>
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