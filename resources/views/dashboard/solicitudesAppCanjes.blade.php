@extends('layouts.layoutDashboard')

@section('title', 'Solicitudes de Canje')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/solicitudesAppCanjes.css') }}">
@endpush

@section('main-content')
<div class="solicitudesAppCanjesContainer">
    <div class="headerSolicitudes">
        <h3>Solicitudes de Canje</h3>
    </div>

    <div class="tableSolicitudes">
        <table id="tblSolicitudesCanje">
            <thead>
                <tr>
                    <th class="celda-centered">#</th>
                    <th class="celda-centered">ID Solicitud</th>
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
					<td class="celda-centered">{{ $solicitud->idSolicitudCanje }}</td>
					<td>{{ $solicitud->tecnicos->nombre }} <br>
						<small>DNI: {{ $solicitud->idTecnico }}</small>
					</td>
					<td class="celda-centered">{{ $solicitud->ventaIntermediada->idVentaIntermediada ?? 'N/A' }}</td>
					<td class="celda-centered">
						<span class="estado {{ strtolower($solicitud->estadosSolicitudCanje->nombre_EstadoSolicitudCanje) }}">
							{{ $solicitud->estadosSolicitudCanje->nombre_EstadoSolicitudCanje }}
						</span>
					</td>
					<td class="celda-centered">{{ $solicitud->fecha_SolicitudCanje }}</td>
					<td>
						@foreach ($solicitud->solicitudCanjeRecompensa as $recompensa)
						- {{ $recompensa->recompensas->descripcionRecompensa }} (x{{ $recompensa->cantidad }}) <br>
						@endforeach
					</td>
					<td class="celda-btnAcciones">
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
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/solicitudesAppCanjes.js') }}"></script>
@endpush
