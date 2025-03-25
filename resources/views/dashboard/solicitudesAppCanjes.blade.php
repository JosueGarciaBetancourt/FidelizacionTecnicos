@extends('layouts.layoutDashboard')

@section('title', 'Solicitudes de Canje')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/solicitudesAppCanjes.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modalDetalleSolicitudCanje.css') }}">
@endpush

@section('main-content')
    @php
        $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
    @endphp

<div class="solicitudesAppCanjesContainer">
    <div class="firstRowSolicitudCanje">
        <h3>Solicitudes de Canje</h3>
		<h4>Las solicitudes de canje son registradas desde la aplicación móvil de los técnicos.</h4>
    </div>
    <div class="secondRowSolicitudCanje">
        <table id="tblSolicitudesAppCanje">
            <thead>
                <tr>
                    <th class="celda-centered">#</th>
                    <th class="celda-centered">Código</th>
                    <th class="celda-centered">Fecha y Hora</th>
                    <th class="celda-centered">Técnico</th>
                    <th class="ventaAsociada">Venta Asociada</th>
                    <th class="celda-centered">Estado</th>
                    <th class="celda-centered">Detalles</th>
                    @if (!$isAsisstantLogged)
                        <th class="celda-centered">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
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

    <x-modalSuccessAction 
        :idSuccesModal="'successModalSolicitudAprobada'"
        :message="'Solicitud aprobada correctamente'"
    />

    <x-modalSuccessAction 
        :idSuccesModal="'successModalSolicitudRechazada'"
        :message="'Solicitud rechazada correctamente'"
    />
</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/solicitudesAppCanjes.js') }}"></script>
	<script src="{{asset('js/modalDetalleSolicitudCanje.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (sessionStorage.getItem('solicitudAprobada') === 'true') {
                justOpenModal('successModalSolicitudAprobada');
                sessionStorage.removeItem('solicitudAprobada');
            }
            if (sessionStorage.getItem('solicitudRechazada') === 'true') {
                justOpenModal('successModalSolicitudRechazada');
                sessionStorage.removeItem('solicitudRechazada');
            }
        });
    </script>
@endpush
