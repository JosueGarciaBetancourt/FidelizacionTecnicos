@extends('layouts.layoutDashboard')

@section('title', 'Solicitudes de Canje')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/solicitudesAppCanjes.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalDetalleSolicitudCanje.css') }}">
@endpush

@section('main-content')
<div class="solicitudesAppCanjesContainer">
    <div class="headerSolicitudes">
        <h3>Solicitudes de Canje</h3>
    </div>

    <div class="tableSolicitudes">
        <table id="tblSolicitudesAppCanje">
            <thead>
                <tr>
                    <th class="celda-centered">#</th>
                    <th class="celda-centered">Código</th>
                    <th class="celda-centered">Técnico</th>
                    <th class="celda-centered">Venta Asociada</th>
                    <th class="celda-centered">Estado</th>
                    <th class="celda-centered">Fecha</th>
                    <th class="celda-centered">Recompensas</th>
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
					<td>{{ $solicitud->tecnicos->nombreTecnico }} <br>
						<small>DNI: {{ $solicitud->idTecnico }}</small>
					</td>
					<td class="celda-centered">{{ $solicitud->ventaIntermediada->idVentaIntermediada ?? 'N/A' }}</td>
					<td class="celda-centered">
						<span class="estado {{ strtolower($solicitud->estadosSolicitudCanje->nombre_EstadoSolicitudCanje) }}">
							{{ $solicitud->estadosSolicitudCanje->nombre_EstadoSolicitudCanje }}
						</span>
					</td>
					<td class="celda-centered">{{ $solicitud->fecha_SolicitudCanje }}</td>
					<td class="celda-btnDetalle">
						<button class="btnDetalle" onclick="openModalSolicitudCanje(this, {{ json_encode($solicitudesCanje) }})">
							Ver Detalle <span class="material-symbols-outlined">visibility</span>
						</button>
					</td>
					<td class="celda-centered celda-btnAcciones" id="idCeldaAcciones">
						<button class="btnAprobar" onclick="aprobarSolicitud('{{ $solicitud->idSolicitudCanje }}')">
							Aprobar
						</button>
						<button class="btnRechazar" onclick="rechazarSolicitud('{{ $solicitud->idSolicitudCanje }}')">
							Rechazar
						</button>
					</td>
				</tr>
				@endforeach
			</tbody>			
        </table>
    </div>
	@include('modals.canjes.modalDetalleSolicitudCanje')
</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/solicitudesAppCanjes.js') }}"></script>
	<script src="{{asset('js/modalDetalleSolicitudCanje.js')}}"></script>
@endpush
