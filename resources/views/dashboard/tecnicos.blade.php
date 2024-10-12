@extends('layouts.layoutDashboard')

@section('title', 'Técnicos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tecnicosStyle.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalAgregarNuevoTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalEditarTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalEliminarTecnico.css') }}">
@endpush

@section('main-content')
	<div class="tecnicosContainer">
		<div class="firstRow">
			<x-btn-create-item onclick="openModal('modalAgregarNuevoTecnico')"> 
				Registrar nuevo técnico
			</x-btn-create-item>
			@include('modals.tecnicos.modalAgregarNuevoTecnico')

			{{--<x-btn-edit-item onclick="openModal('modalEditarTecnico')"> Editar </x-btn-edit-item>
			@include('modals.tecnicos.modalEditarTecnico')

			<x-btn-delete-item onclick="openModal('modalEliminarTecnico')"> Eliminar </x-btn-delete-item>
			@include('modals.tecnicos.modalEliminarTecnico')--}}
		</div>
		
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
			:message="'Técnico eliminado correctamente'"
		/>

		<!--Tabla de ventas intermediadas-->
		<div class="secondRow">
			<table id="tblTecnicos">
				<thead>
					<tr>
						<th class="celda-centered">#</th>
						<th class="celda-centered">DNI</th>
						<th class="celda-centered">Nombre</th>
						<th class="celda-centered">Oficio</th>
						<th class="celda-centered">Celular</th>
						<th class="celda-centered">Fecha de nacimiento</th>
						<th class="celda-centered">Puntos actuales</th>
						<th class="celda-centered">Histórico de puntos</th>
						<th class="celda-centered">Rango</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/modalAgregarNuevoTecnico.js') }}"></script>
	<script src="{{ asset('js/modalEditarTecnico.js') }}"></script>
	<script src="{{ asset('js/modalEliminarTecnico.js') }}"></script>
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
		});
	</script>
@endpush