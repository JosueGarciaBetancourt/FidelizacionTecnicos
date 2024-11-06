@extends('layouts.layoutDashboard')

@section('title', 'Canjes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/historialCanjesStyle.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalDetalleHistorialCanje.css') }}">
@endpush

@section('main-content')
	<div class="historialCanjeContainer">
		<div class="secondRow">
			<table id="tblHistorialCanjes">
				<thead>
					<tr>
						<th class="celda-centered">#</th>
						<th class="celda-centered">Código</th>
						<th class="celda-centered date">Fecha y hora</th>
						<th class="celda-centered">Número de comprobante</th>
						<th class="celda-centered date">Fecha y hora de emisión</th>
						<th class="celda-centered">Días transcurridos</th>
						<th class="celda-centered">Puntos de comprobante</th>
						<th class="celda-centered">Puntos canjeados</th> 
						<th class="celda-centered">Puntos restantes</th> 
						<th class="celda-centered"></th> 
					</tr>
				</thead>
				<tbody>
					@php
						$contador = 1;
					@endphp
					@foreach ($allCanjes as $canje)
					<tr>
						<td class="celda-centered">{{ $contador++ }}</td> 
						<td class="celda-centered">{{ $canje->idCanje }}</td>
						<td class="celda-centered">{{ $canje->fechaHora_Canje }}</td>
						<td class="celda-centered">{{ $canje->idVentaIntermediada }}</td>
						<td class="celda-centered">{{ $canje->fechaHoraEmision_VentaIntermediada }}</td>
						<td class="celda-centered">{{ $canje->diasTranscurridos_Canje }}</td>
						<td class="celda-centered">{{ $canje->puntosComprobante_Canje }}</td>
						<td class="celda-centered">{{ $canje->puntosCanjeados_Canje }}</td>
						<td class="celda-centered">{{ $canje->puntosRestantes_Canje }}</td>
						<td class="celda-btnDetalle">
							<button class="btnDetalle" onclick="openModalDetalleHistorialCanje(this, '{{ $allCanjes }}')">
								Ver Detalle <span class="material-symbols-outlined">visibility</span>
							</button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		@include('modals.canjes.modalDetalleHistorialCanje')
	</div>
@endsection	

@push('scripts')
	<script src="{{asset('js/historialCanjes.js')}}"></script>
@endpush