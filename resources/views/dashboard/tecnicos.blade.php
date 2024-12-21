@extends('layouts.layoutDashboard')

@section('title', 'Técnicos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tecnicosStyle.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalAgregarNuevoTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalEditarTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalEliminarTecnico.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalRecontratarTecnico.css') }}">
@endpush

@section('main-content')
	<div class="tecnicosContainer">
		<div class="firstRow">
			<x-btn-create-item onclick="openModal('modalAgregarNuevoTecnico')"> 
				Registrar nuevo técnico
			</x-btn-create-item>
			@include('modals.tecnicos.modalAgregarNuevoTecnico')

			<x-btn-edit-item onclick="openModal('modalEditarTecnico')"> Editar </x-btn-edit-item>
			@include('modals.tecnicos.modalEditarTecnico')

			<x-btn-delete-item onclick="openModal('modalEliminarTecnico')"> Inhabilitar </x-btn-delete-item>
			@include('modals.tecnicos.modalEliminarTecnico')

			<x-btn-recover-item onclick="openModal('modalRecontratarTecnico')">Habilitar</x-btn-delete-item>
			@include('modals.tecnicos.modalRecontratarTecnico')
		</div>

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
		
		{{--
		<div class="secondRow">
			<table id="tblTecnicoss">
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
					@php
						$contador = 1;
					@endphp
					@foreach ($tecnicos as $tecnico)
						<tr>
							<td class="celda-centered">{{ $contador++ }}</td> 
							<td class="celda-centered">{{ $tecnico->idTecnico }}</td>
							<td class="celda-centered">{{ $tecnico->nombreTecnico }}</td>
							<td class="celda-centered">{{ $tecnico->idNameOficioTecnico }}</td>
							<td class="celda-centered">{{ $tecnico->celularTecnico }}</td>
							<td class="celda-centered">{{ $tecnico->fechaNacimiento_Tecnico }}</td>
							<td class="celda-centered">{{ $tecnico->totalPuntosActuales_Tecnico }}</td>
							<td class="celda-centered">{{ $tecnico->historicoPuntos_Tecnico }}</td>
							<td class="celda-centered">{{ $tecnico->rangoTecnico }}</td>
						</tr>
                    @endforeach
                </tbody>
			</table>
		</div>
		--}}

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
	</div>
@endsection

@push('scripts')
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
		});
	</script>
@endpush