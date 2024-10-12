@extends('layouts.layoutDashboard')

@section('title', 'Configuración')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/configuracionStyle.css') }}">
@endpush

@section('main-content')
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
			
			<button id="saveConfig" class="save-config">Guardar Configuración</button>
		</div>
	</div>

	<x-modalSuccessAction 
		:idSuccesModal="'successModalConfiguracionGuardada'"
		:message="'Configuración guardada correctamente'"
	/>
@endsection

@push('scripts')
    <script src="{{ asset('js/configuracion.js') }}"></script>
@endpush
