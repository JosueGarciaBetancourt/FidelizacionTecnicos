@extends('layouts.layoutDashboard')

@section('title', 'Configuración')

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('css/configuracionStyle.css') }}"> --}}
	{{-- @vite(['resources/css/configuracionStyle.css']) --}}
@endpush

@section('main-content')
	@php
		$isAdminLogged = Auth::check() && Auth::user()->idPerfilUsuario == 1;
	@endphp
		
	<div class="configuracionContainer">
		<div class="firstRow">
			<h1>Configuración del Sistema</h1>
		</div>
		
		<div class="secondRow">
			<div class="config-section">
				<h2>Apariencia</h2>
				<div class="config-option">
					<label for="darkMode">Modo Oscuro</label>
					<input type="checkbox" id="darkMode" class="toggle-switch">
				</div>
				<div class="config-option">
					<label for="fontSize">Tamaño de Fuente</label>
					<select id="fontSize">
						<option value="small">Pequeño</option>
						<option value="medium" selected>Mediano</option>
						<option value="large">Grande</option>
					</select>
				</div>
			</div>
			
			<div class="config-section">
				<h2>Personalización</h2>
				<div class="config-option">
					<label for="sidebarColor">Color de la Barra Lateral</label>
					<input type="color" id="sidebarColor" value="#007bff">
				</div>
			</div>

			@if ($isAdminLogged) 
				<form id="formEditaVariablesConfiguracion" action="{{ route('configuracion.update') }}" method="POST">
					@method('PUT')
					@csrf

					@php
						$maxdaysCanjeInput = "maxdaysCanjeSettingsInput";
						$emailDomainInput = "emailDomainSettingsInput";
						$adminUsernameInput = "adminUsernameSettingsInput";
						$puntosMinRangoPlataInput = "puntosMinRangoPlataSettingsInput";
						$puntosMinRangoOroInput = "puntosMinRangoOroSettingsInput";
						$puntosMinRangoBlackInput = "puntosMinRangoBlackSettingsInput";
						$unidadesRestantesRecompensasNotificacionInput = "unidadesRestantesRecompensasNotificacionInput";
						$diasAgotarVentaIntermediadaNotificacionInput = "diasAgotarVentaIntermediadaNotificacionInput";
					@endphp

					<div class="config-section">
						<h2>Variables generales</h2>
						
						<h3> Usuarios del sistema</h3>

						<div class="config-option">
							<label for="{{ $emailDomainInput }}">Dominio de correo:</label>
							<input type="hidden" value="emailDomain" name="keys[]" readonly>
							<input type="text" class="input-item" id="{{ $emailDomainInput}}" name="values[]" value="{{ config('settings.emailDomain') }}">
						</div>

						{{--
						<div class="config-option">
							<label for="{{ $adminUsernameInput }}">Nombre de usuario del correo del Administrador:</label>
							<input type="hidden" value="adminUsername" name="keys[]" readonly>
							<input type="text" class="input-item" id="{{ $adminUsernameInput}}" name="values[]" value="{{ config('settings.adminUsername') }}">
						</div>
 						--}}

						<h3> Ventas intermediadas y Canjes</h3>
						
						<div class="config-option">
							<label for="{{ $maxdaysCanjeInput }}">Días máximos de registro de venta/Días máximos para canjear una venta:</label>
							<input type="hidden" value="maxdaysCanje" name="keys[]" readonly>
							<input type="number" class="input-item" id="{{ $maxdaysCanjeInput}}" name="values[]" 
								oninput="validateRealTimeInputLength(this, 3), validateNumberWithMaxLimitRealTime(this, 90)" value="{{ config('settings.maxdaysCanje') }}">
						</div>

						<input type="hidden" id="{{ $maxdaysCanjeInput}}" value="{{ config('settings.maxdaysCanje') }}">
						
						<h3> Notificaciones</h3>

						<div class="config-option">
							<label for="{{ $unidadesRestantesRecompensasNotificacionInput }}">Unidades mínimas restantes de recompensas para notificar sobre agotamiento de stock:</label>
							<input type="hidden" value="unidadesRestantesRecompensasNotificacion" name="keys[]" readonly>
							<input type="number" class="input-item" id="{{ $unidadesRestantesRecompensasNotificacionInput }}" name="values[]" 
								oninput="validateRealTimeInputLength(this, 3)" value="{{ config('settings.unidadesRestantesRecompensasNotificacion') }}">
						</div>

						<div class="config-option">
							<label for="{{ $diasAgotarVentaIntermediadaNotificacionInput }}">Días antes para notificar a un técnico sobre agotamiento de canje de una venta intermediada:</label>
							<input type="hidden" value="diasAgotarVentaIntermediadaNotificacion" name="keys[]" readonly>
							<input type="number" class="input-item" id="{{ $diasAgotarVentaIntermediadaNotificacionInput }}" name="values[]" 
								oninput="validateRealTimeInputLength(this, 2), validateNumberWithMaxLimitRealTime(this, 90)" value="{{ config('settings.diasAgotarVentaIntermediadaNotificacion') }}">
						</div>

						<input type="hidden" name="originConfig" value="default" readonly>
					</div>
				</form>
			@endif
		</div>
		<button type="submit" id="saveConfig" class="save-config">Guardar Configuración</button>
	</div>

	<x-modalSuccessAction 
		:idSuccesModal="'successModalConfiguracionGuardada'"
		:message="'Configuración guardada correctamente'"
	/>

	<x-modalFailedAction 
		:idErrorModal="'errorModalConfiguracion'"
		:message="'Algunos valores de configuración son incorrectos. Revísalos y vuelve a intentarlo.'"
	/>
@endsection

@push('scripts')
    <script src="{{ asset('js/configuracion.js') }}"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			@if(session('successConfig'))
				openModal('successModalConfiguracionGuardada');
			@endif
		});
	</script>
@endpush
