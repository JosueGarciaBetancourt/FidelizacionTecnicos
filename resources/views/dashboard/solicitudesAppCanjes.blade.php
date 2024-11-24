@extends('layouts.layoutDashboard')

@section('title', 'Solicitudes de Canje')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/solicitudesAppCanjes.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalDetalleSolicitudCanje.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalConfirmSolicitudCanje.css') }}">
@endpush

@section('main-content')
<div class="solicitudesAppCanjesContainer">
    <div class="firstRowSolicitudCanje">
        <h3>Solicitudes de Canje</h3>
    </div>
    <div class="secondRowSolicitudCanje">
        <table id="tblSolicitudesAppCanje">
            <thead>
                <tr>
                    <th class="celda-centered">#</th>
                    <th class="celda-centered">Código</th>
                    <th class="celda-centered">Fecha y Hora</th>
                    <th class="celda-centered">Técnico</th>
                    <th class="celda-centered">Venta Asociada</th>
                    <th class="celda-centered">Estado</th>
                    <th class="celda-centered">Detalles</th>
                    <th class="celda-centered">Acciones</th>
                </tr>
            </thead>
            <tbody>
				@php
					$contador = 1;
				@endphp
				@foreach ($solicitudesCanje as $solicitud)
				<tr>
					<td class="celda-centered">{{ $contador++ }}</td>
					<td class="celda-centered idSolicitudCanje">{{ $solicitud->idSolicitudCanje }}</td>
					<td class="celda-centered">{{ $solicitud->fechaHora_SolicitudCanje }}</td>
					<td>{{ $solicitud->tecnicos->nombreTecnico }} <br>
						<small>DNI: {{ $solicitud->idTecnico }}</small>
					</td>
					<td class="celda-centered">{{ $solicitud->ventaIntermediada->idVentaIntermediada }} <br>
						<small>Puntos generados: {{ $solicitud->puntosComprobante_SolicitudCanje }}</small>
					</td>
					<td class="estado__celda">
						<span class="estado__span-{{strtolower(str_replace(' ', '-', $solicitud->idEstadoSolicitudCanje))}}">
							{{ $solicitud->estadosSolicitudCanje->nombre_EstadoSolicitudCanje }}
						</span>
					</td>
					<td class="celda-btnDetalle">
						<button class="btnDetalle" onclick="openModalSolicitudCanje(this, {{ json_encode($solicitudesCanje) }})">
							Ver Detalle <span class="material-symbols-outlined">visibility</span>
						</button>
					</td>
					<td class="celda-centered celda-btnAcciones" id="idCeldaAcciones">
						@if ($solicitud->idEstadoSolicitudCanje == 1)
							<button class="btnAprobar" onclick="aprobarSolicitudCanje('{{ $solicitud->idSolicitudCanje }}')">
								Aprobar
							</button>
							<button class="btnRechazar" onclick="rechazarSolicitudCanje('{{ $solicitud->idSolicitudCanje }}')">
								Rechazar
							</button>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>			
        </table>
    </div>
	@include('modals.canjes.modalDetalleSolicitudCanje')

	<x-modalConfirmSolicitudCanje 
		:idConfirmModal="'modalConfirmActionAprobarSolicitudCanje'"
		:message="'¿Está seguro de aprobar esta solicitud de canje?'"
		:title="'Aprobar solicitud'"
		:commentLabel="'Comentario de aprobación'"
		:placeholder="'Solicitud aprobada porque ...'"
	/>

	<x-modalConfirmSolicitudCanje 
		:idConfirmModal="'modalConfirmActionRechazarSolicitudCanje'"
		:message="'¿Está seguro de rechazar esta solicitud de canje?'"
		:title="'Rechazar solicitud'"
		:commentLabel="'Comentario de rechazo'"
		:placeholder="'Solicitud rechazada porque ...'"
	/>
</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/solicitudesAppCanjes.js') }}"></script>
	<script src="{{asset('js/modalDetalleSolicitudCanje.js')}}"></script>
@endpush
