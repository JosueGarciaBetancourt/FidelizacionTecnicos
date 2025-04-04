@extends('layouts.layoutApp')

@section('title', 'Inicio de sesión')

@push('styles')
    @vite(['resources/css/loginStyle.css'])
@endpush

@section('content')
    <div class="main_container">
        <div class="left_content">
            <div class="logo_DIMACOF_container">
                <img src="{{ asset('images/logo_DIMACOF.png') }}" alt="logoDIMACOF">
            </div>

            <div class="title_container">    
                <h1>Club de técnicos</h1>
            </div>    

            <div class="otherText_container">    
                <h4>Ingresa a tu cuenta</h4>
            </div>    

            @if (session('status'))
                <div class="alert">
                    {{ session('status') }}
                </div>
            @endif

             <!-- Erase this part in production, just for developing -->
            @php
                $adminEmailBD = $adminEmail;
                $adminPassword = "12345678";
            @endphp
            <!-- -->
            
            <form id="formLogin" method="POST" action="{{ route('loginPost') }}">
                @csrf
                <!-- Email Address -->
                <div class="form-group">
                    <div class="subtitle_container">    
                        <h3>Correo electrónico</h3>
                    </div>   
                    <input id="email" class="credential-box-input" type="email" name="email" 
                            value="{{ $adminEmailBD }}" placeholder="Ingrese email" autofocus autocomplete="username" required>
                </div>
                {{--{{ old('email') }}--}}
                @error('email')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Password -->
                <div class="form-group">
                    <div class="subtitle_container">    
                        <h3>Contraseña</h3>
                    </div>   
                
                    <!-- Contenedor para alinear el input y el checkbox -->
                    <div class="input-password-container">
                        <input id="password" class="credential-box-input" type="password" 
                               name="password" value="{{ $adminPassword }}" required placeholder="Ingrese contraseña" 
                               required autocomplete="current-password">
                
                        <!-- Checkbox para mostrar/ocultar contraseña -->
                        <div class="show-password-container">
                            <input type="checkbox" id="show_password_checkbox" onclick="togglePassword()"> 
                            <span class="material-symbols-outlined" id="loginEyeIcon">visibility_off</span>
                        </div>
                    </div>
                </div>

                @error('password')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Remember Me -->
                <div class="remember_check">
                    <label>
                        <input type="checkbox" class="form-check-input" name="remember" id="remember_me">
                        <span>Mantener sesión iniciada</span>
                    </label>
                </div>

                <div class="recover-password">
                    @if (Route::has('password.request'))
                        <a class="recover-password-link" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                
                @auth
                    <div class="button_container">
                        <a href="{{ route('ventasIntermediadas.create') }}" class="login_button">
                            Ir al dashboard
                        </a>
                    </div>
                @else
                    <div class="button_container">
                        <button type="submit" class="login_button" id="idLoginButton"
                                onclick="handleFormSubmission('idLoginButton', 'formLogin')">Iniciar Sesión</button>
                    </div>
                @endauth
            </form>
        </div>

        <div class="right_content">
            <div class="enchapadorImg_container">
                <img src="{{ asset('images/enchapador.png') }}" alt="enchapador">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Limpiar el localStorage
            localStorage.removeItem('openModals');
        });
    </script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const checkbox = document.getElementById('show_password_checkbox');

            // Si el checkbox está marcado, mostramos la contraseña
            if (checkbox.checked) {
                passwordInput.type = 'text';
                document.getElementById("loginEyeIcon").textContent = "visibility";
            } else {
                passwordInput.type = 'password';
                document.getElementById("loginEyeIcon").textContent = "visibility_off";
            }
        }
    </script>
@endsection

@push('scripts')
	<script src="{{ asset('js/login.js') }}"></script>
@endpush