@extends('layouts.layoutDashboard')

@section('title', 'Técnicos')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/tecnicosStyle.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalAgregarNuevoTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalEditarTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalEliminarTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalRecontratarTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/multiselectDropdown.css') }}">
@endpush

@section('main-content')
	@php
		$isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
	@endphp

	<div class="tecnicosContainer">
		@if (!$isAsisstantLogged)
			<div class="firstRow">
				<x-btn-create-item onclick="openModal('modalAgregarNuevoTecnico')"> 
					Registrar nuevo técnico
				</x-btn-create-item>
				@include('modals.tecnicos.modalAgregarNuevoTecnico')
				
				<x-btn-edit-item onclick="openModal('modalEditarTecnico')"> Editar </x-btn-edit-item>
				@include('modals.tecnicos.modalEditarTecnico')

				<x-btn-disable-item onclick="openModal('modalEliminarTecnico')"> Inhabilitar </x-btn-disable-item>
				@include('modals.tecnicos.modalEliminarTecnico')

				<x-btn-recover-item onclick="openModal('modalRecontratarTecnico')">Habilitar</x-btn-delete-item>
				@include('modals.tecnicos.modalRecontratarTecnico')
			</div>
		@endif
		
		<div class="secondRow">
			<table id="tblTecnicos">
				<thead>
					<tr>
						<th class="celda-centered">#</th>
						<th class="celda-centered">DNI</th>
						<th>Nombre</th>
						<th class="celda-centered celda-oficios">Oficio</th>
						<th class="celda-centered">Celular</th>
						<th class="celda-centered">Fecha de nacimiento</th>
						<th class="celda-centered">Puntos actuales</th>
						<th class="celda-centered">Histórico de puntos</th>
						<th class="celda-centered celda-rango">Rango</th>
						@if (!$isAsisstantLogged)
							<th class="celda-centered celda-acciones"></th>
						@endif
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
		<x-modalConfirmAction
			:idConfirmModal="'modalConfirmActionRestorePasswordTecnico'"
			:message="'La contraseña actual del técnico en la aplicación móvil será restaurada a su número de DNI, ¿está seguro de esta acción?'"
		/>

		<x-modalSuccessAction 
			:idSuccesModal="'successModalTecnicoGuardado'"
			:message="'Técnico guardado correctamente'"
		/>
		
		<x-modalSuccessAction 
			:idSuccesModal="'successModalTecnicoActualizado'"
			:message="'Técnico actualizado correctamente'"
		/>

		<x-modalSuccessAction 
			:idSuccesModal="'successModalTecnicoEliminado'"
			:message="'Técnico inhabilitado correctamente'"
		/>

		<x-modalSuccessAction 
			:idSuccesModal="'successModalTecnicoRecontratado'"
			:message="'Técnico habilitado correctamente'"
		/>

		<x-modalSuccessAction 
            :idSuccesModal="'successModalPasswordRestored'"
            :message="'La contraseña del técnico fue restaurada exitosamente'"
        />

		<x-modalFailedAction 
            :idErrorModal="'errorModalPasswordRestored'"
            :message="'La contraseña ya está restaurada'"
        />
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/multiSelectDropdown.js') }}"></script>
	<script src="{{ asset('js/modalAgregarNuevoTecnico.js') }}"></script>
	<script src="{{ asset('js/modalEditarTecnico.js') }}"></script>
	<script src="{{ asset('js/modalEliminarTecnico.js') }}"></script>
	<script src="{{ asset('js/modalRecontratarTecnico.js') }}"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			@if(session('successTecnicoStore'))
				openModal('successModalTecnicoGuardado');
			@endif
			@if(session('successTecnicoUpdate'))
				openModal('successModalTecnicoActualizado');
			@endif
			@if(session('successTecnicoDelete'))
				openModal('successModalTecnicoEliminado');
			@endif
			@if(session('successTecnicoRecontratadoStore'))
				openModal('successModalTecnicoRecontratado');
			@endif
			if (sessionStorage.getItem('passwordRestoredTecnico') === 'true') {
                openModal('successModalPasswordRestored');
                sessionStorage.removeItem('passwordRestoredTecnico');
            }
		});
	</script>
@endpush