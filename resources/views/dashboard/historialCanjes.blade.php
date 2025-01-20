@extends('layouts.layoutDashboard')

@section('title', 'Canjes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/historialCanjesStyle.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalDetalleHistorialCanje.css') }}">
@endpush

@section('main-content')
	<div class="historialCanjeContainer">
		<div class="firstRowHistorialCanje">
			<h3>Historial de canjes</h3>
		</div>

		<div class="secondRowHistorialCanje">
			<table id="tblHistorialCanjes">
				<thead>
					<tr>
						<th class="celda-centered">#</th>
						<th class="celda-centered">Código</th>
						<th class="celda-centered date">Fecha y Hora</th>
						<th class="celda-centered" id="celda-numComprobante">Venta Asociada</th>
						<th class="celda-centered date">Fecha y hora de emisión</th>
						<th class="celda-centered">Días transcurridos</th>
						<th class="celda-centered" id="celda-tecnico">Técnico</th>
						<th class="celda-centered">Puntos canjeados</th> 
						<th class="celda-centered">Puntos restantes</th> 
						<th class="celda-centered"></th> 
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

		@include('modals.canjes.modalDetalleHistorialCanje')
	</div>
@endsection	

@push('scripts')
	<script src="{{asset('js/historialCanjes.js')}}"></script>
	<script src="{{asset('js/modalDetalleHistorialCanje.js')}}"></script>
@endpush




