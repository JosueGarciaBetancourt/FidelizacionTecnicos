@extends('layouts.layoutDashboard')

@section('title', 'Configuración')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/configuracionStyle.css') }}">
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
				<div class="config-section">
					<h2>Variables generales</h2>
					<div class="config-option">
						<label for="maxdaysCanjeSettingsInput">Días máximos de canje</label>
						<input type="number" id="maxdaysCanjeSettingsInput" value="{{ config('settings.maxdaysCanje') }}">
					</div>
				</div>
			@endif
		</div>
		<button id="saveConfig" class="save-config">Guardar Configuración</button>
	</div>

	<x-modalSuccessAction 
		:idSuccesModal="'successModalConfiguracionGuardada'"
		:message="'Configuración guardada correctamente'"
	/>
@endsection

@push('scripts')
    <script src="{{ asset('js/configuracion.js') }}"></script>
@endpush
