@extends('layouts.layoutApp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboardStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooltip.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalSuccess.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalError.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalConfirm.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalLoadingFarewell.css') }}">
    <link rel="stylesheet" href="{{ asset('css/configuracionStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalConfirmSolicitudCanje.css') }}">
    @stack('styles')
@endpush

@section('content')
    @php
        $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
    @endphp

    <div class="dashboard-container"
        data-routes='{"perfil": "{{ route('usuarios.create') }}", "logout": "{{ route('logout') }}"}'>
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="{{ asset('images/logo_DIMACOF.png') }}" alt="logo_Dimacof">
                </div>
            </div>

            <div class="sidebar">
                <a id="ventasIntermediadasLink" href="{{ route('ventasIntermediadas.create') }}"
                    onclick="openLoadingModal()"
                    class="{{ Request::routeIs('ventasIntermediadas.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">request_page</span>
                    <h5>Ventas Intermediadas</h5>
                </a>

                <div id="canjesLinkContainer">
                    <a id="canjesLink" href="#"
                        class="{{ Request::routeIs('canjes.create', 'canjes.historial', 'solicitudescanjes.create') ? 'canjesActive' : '' }}">
                        <span class="material-symbols-outlined">currency_exchange</span>
                        <h5>Canjes</h5>
                        <span id="canjesArrowDownSpan" class="material-symbols-outlined">keyboard_arrow_down</span>
                    </a>
                    <div class="select-items-canjes hidden" id="canjesMenu">
                        @if (!$isAsisstantLogged)
                            <a href="{{ route('canjes.registrar') }}" onclick="openLoadingModal()"
                                class="{{ Request::routeIs('canjes.registrar') ? 'subLinkActive' : '' }}">• Registrar
                                Canje</a>
                        @endif
                        <a href="{{ route('canjes.historial') }}" onclick="openLoadingModal()"
                            class="{{ Request::routeIs('canjes.historial') ? 'subLinkActive' : '' }}">• Ver Historial</a>
                        <a href="{{ route('solicitudescanjes.create') }}" onclick="openLoadingModal()"
                            class="{{ Request::routeIs('solicitudescanjes.create') ? 'subLinkActive' : '' }}">• Ver
                            solicitudes desde APP</a>
                    </div>
                </div>

                <a href="{{ route('recompensas.create') }}" onclick="openLoadingModal()"
                    class="{{ Request::routeIs('recompensas.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">handyman</span>
                    <h5>Recompensas</h5>
                </a>

                <a href="{{ route('tecnicos.create') }}" onclick="openLoadingModal()"
                    class="{{ Request::routeIs('tecnicos.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">groups</span>
                    <h5>Técnicos</h5>
                </a>

                <a href="{{ route('oficios.create') }}" onclick="openLoadingModal()"
                    class="{{ Request::routeIs('oficios.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">engineering</span>
                    <h5>Oficios</h5>
                </a>

                <a href="{{ route('configuracion.create') }}" onclick="openLoadingModal()"
                    class="{{ Request::routeIs('configuracion.create') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span>
                    <h5>Configuración</h5>
                </a>

                <!-- Formulario de Logout -->
                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" class="btnLogout" id="logoutLink"
                        onclick="handleFormSubmission('logoutLink', 'logoutForm', 2000), openFarewellModal()">
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
                    <a href="#" class="notification_container" id="notification-toggle">
                        <span class="material-symbols-outlined noUserSelect bell"
                            title="Notificaciones">notifications</span>
                        <span class="notification_count noUserSelect"
                            id="notification-count">{{ $notifications->count() }}</span>
                    </a>

                    <div class="notification_panel" id="notification-panel">
                        <div class="notification_header">
                            <h3>Notificaciones</h3>
                        </div>

                        <ul class="notification_list" id="notification-list">
                            @foreach ($notifications as $notification)
                                <li @class([
                                    'notification_item',
                                    $notification->icon,
                                    'unread' => $notification->active,
                                    'read' => !$notification->active,
                                ])>
                                    <div class="notification_icon {{ $notification->icon }}">
                                        <span class="material-symbols-outlined">{{ $notification->icon }}</span>
                                    </div>
                                    <div class="notification_content">
                                        <p class="notification_title">{{ $notification->title }}</p>
                                        <p class="notification_desc">{{ $notification->item }}
                                            {{ $notification->description }}</p>
                                        <p class="notification_time">{{ $notification->time_ago }}</p>
                                    </div>
                                    <div class="notification_actions">
                                        @if ($notification->routeToReview)
                                            <a onclick="reviewNotification('{{ $notification->id }}', '{{ $notification->routeToReview }}',
                                                '{{ $notification->tblToFilter }}', '{{ $notification->item }}')"
                                                class="review_btn">Revisar</a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
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
                            <span id="arrowDownUserList"
                                class="material-symbols-outlined noUserSelect">keyboard_arrow_down</span>
                        </div>

                        <ul class="select-items-userList" id="userList">
                            <li onclick="linkOption('perfil'), closeUserList(), openLoadingModal()">
                                Perfil de usuario
                            </li>
                            <li id="li-logout"
                                onclick="linkOption('logout'), handleFormSubmission('li-logout', 'logoutForm'), closeUserList(), openFarewellModal()">
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

    <x-modalLoading />

    @php
        $messages = [
            'Gracias por tu trabajo',
            '¡Hasta la próxima!',
            'Nos vemos pronto',
            'Sigamos construyendo juntos',
            'Tu trabajo es clave',
            'Tu labor marca la diferencia',
            '¡Éxitos en tu jornada!',
            'Hasta la próxima gestión',
            'Hasta luego',
            'Hasta pronto', 
            'Adiós', 
            null
        ];

        $randomFarewellMessage = $messages[array_rand($messages)];
    @endphp

    <x-modalFarewell :message="$randomFarewellMessage" />
@endsection

@push('scripts')
    <script src="{{ asset('js/userUtils.js') }}"></script>
    <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
    <script src="{{ asset('js/dashboardScript.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/datatableConfig.js') }}"></script>
    <script src="{{ asset('js/modals.js') }}"></script>
    <script src="{{ asset('js/systemNotifications.js') }}"></script>
    <script>
        // Aplicar configuración al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark-mode');
            }
            document.documentElement.classList.add(`font-${localStorage.getItem('fontSize') || 'medium'}`);
            document.documentElement.style.setProperty('--button-color', localStorage.getItem('accentColor') ||
                '#007bff');
        });

        function openLoadingModal() {
            document.getElementById('loadingModal').classList.add("show");
        }

        function openFarewellModal() {
            const farewellModal = document.getElementById('farewellModal');
            if (farewellModal) {
                farewellModal.classList.add("show");
            }
        }
    </script>
@endpush
