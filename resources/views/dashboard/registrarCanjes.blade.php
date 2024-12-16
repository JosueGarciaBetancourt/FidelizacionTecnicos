@extends('layouts.layoutDashboard')

@section('title', 'Canjes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/registrarCanjesStyle.css') }}">
@endpush

@section('main-content')
	<div class="registrarCanjesContainer">
		 <!-- Variables globales -->
		 @php
			$idInput = 'tecnicoCanjesInput';
			$idTecnicoOptions = 'tecnicoCanjesOptions';
			$idTecnicoMessageError = "messageErrorTecnicoCanjes";
			$tecnicosDB = $tecnicos;
			$idForm = "formRegistrarCanje";
			$idComentario = "comentarioInputRegistrarCanje";
		@endphp

		<form id='{{ $idForm }}' action="{{ route('canjes.store') }}" method="POST">
			@csrf
			<div class="firstCanjesRow">
				<h3>Registrar nuevo canje | {{ $nuevoIdCanje }}</h3>
				<div class="fechaContainer">
					<label class="secondary-label" id="idFechaHoyLabel"> Fecha de hoy: </label>
					<input class="input-item" id ="idFechaCanjeInput" type="date" disabled>
				</div>
			</div>

			<h4 class="messageConditionCanje">Solo se pueden canjear las ventas intermediadas que tengan el estado <span>En espera</span> o <span>Redimido (parcial)</span></h4>

			<div class="secondCanjesRow">
				<div class="verticalPairGroup tooltipInside">
					<label class="primary-label"> Técnico </label>
					<div class="tooltip-container">
						<span class="tooltip red" id="idTecnicoCanjesTooltip">Este es el mensaje del tooltip</span>
					</div>
					<div class="input-select" id="tecnicoSelect">
						<input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="DNI - Nombre" autocomplete="off" value=""
							oninput="filterOptions('{{ $idInput }}', '{{ $idTecnicoOptions }}'),
									validateOptionTecnicoCanjes(this, '{{ $idTecnicoOptions }}', '{{ $idTecnicoMessageError }}', {{ json_encode($tecnicosDB) }})"
							onclick="toggleOptions('{{ $idInput }}', '{{ $idTecnicoOptions }}')">
						<ul class="select-items" id='{{ $idTecnicoOptions }}'>
							@foreach ($tecnicosDB as $tecnico)
								@php
									$value = $tecnico->idTecnico . " - " . $tecnico->nombreTecnico;
									$puntosActuales = $tecnico->totalPuntosActuales_Tecnico;
									$idTecnico = $tecnico->idTecnico
								@endphp
								<li onclick="selectOptionTecnicoCanjes('{{ $value }}', '{{ $idInput }}', '{{ $idTecnicoOptions }}', 
											'{{ $puntosActuales }}', '{{ $idTecnico }}')">
									{{ $value }}
								</li>
							@endforeach
						</ul>
					</div>
				</div>
				<div class="verticalPairGroup">
					<label class="primary-label noEditable centered"> Total de puntos </label>
					<input class="input-item" id="puntosActualesCanjesInput" maxlength="4" placeholder="0" readonly>
				</div>
				<span class="inline-alert-message" id="{{ $idTecnicoMessageError }}"> No se encontró el técnico buscado </span>      
			</div>

			<div class="thirdCanjesRow">
				<div class="verticalPairGroup tooltipInside">
					<label class="primary-label" id="numComprobanteLabel"> Número de venta </label>
					<div class="tooltip-container">
						<span class="tooltip red" id="idNumComprobanteCanjesTooltip">Este es el mensaje del tooltip</span>
					</div>
					<x-onlySelect-input 
							:idSelect="'comprobanteSelect'"
							:inputClassName="'onlySelectInput persist-input'"
							:idInput="'comprobanteCanjesInput'"
							:idOptions="'comprobanteOptions'"
							:placeholder="'Seleccionar Comp.'"
							:name="'idVentaIntermediada'"
							:options="$optionsNumComprobante"
							:onSelectFunction="'selectOptionNumComprobanteCanjes'"
							:onSpanClickFunction="'cleanAllNumeroComprobante'"
							:onClickFunction="'toggleNumComprobanteCanjesOptions'"
							:spanClassName="'noUserSelect'"
					/>
				</div>

				<div class="verticalPairGroup">
					<label class="primary-label noEditable centered"> Estado </label>

					<textarea class="textarea" id="estadoComprobanteCanjesTextarea" 
								type="text"
								rows="2" 
								placeholder="Selecciona un comprobante" disabled></textarea> 
				</div>

				<div class="verticalPairGroup">
					<label class="primary-label noEditable centered"> Puntos actuales </label>
					<input class="input-item noEditable" id="puntosGeneradosCanjesInput" name="puntosComprobante_Canje" maxlength="4" 
						placeholder="0" readonly>
				</div>

				{{--
				<div class="verticalPairGroup">
					<label class="primary-label noEditable centered"> Puntos restantes </label>
					<input class="input-item noEditable" id="puntosRestantesCanjesInput" maxlength="4" 
						placeholder="0" disabled>
				</div>
				--}}

				<div class="verticalPairGroup">
					<label class="primary-label noEditable centered"> Cliente  </label>
					<textarea class="textarea" id="clienteCanjesTextarea" 
								type="text"
								rows="2" 
								placeholder="Nombre&#10;Tipo de Doc.&#10;Número de Doc." disabled></textarea> 
				</div>

				<div class="verticalPairGroup">
					<label class="primary-label noEditable centered"> Fecha Emisión </label>
					<input class="input-item noEditable " id="fechaEmisionCanjesInput" type="date" disabled>
				</div>

				{{--<div class="verticalPairGroup noEditable">
					<label class="primary-label noEditable centered"> Fecha Cargada </label>
					<input class="input-item noEditable" id ="fechaCargadaCanjesInput" type="date" disabled>
				</div>--}}

				<div class="verticalPairGroup noEditable">
					<label class="primary-label noEditable centered">Días hasta hoy</label>
					<input class="input-item noEditable" id ="diasTranscurridosInput" maxlength="3" placeholder="0" disabled>
				</div>
			</div>

			<div class="fourthCanjesRow">
				<div class="verticalPairGroup tooltipInside">
					<label class="primary-label"> Recompensas </label>
					<div class="tooltip-container">
						<span class="tooltip red" id="idRecompensasCanjesTooltip"></span>
					</div>
					@php
						$idRecompensaInput = 'recompensasCanjesInput';
						$idRecompensaOptions = 'recompensaOptions';
						$idRecompensaMessageError = 'messageErrorRecompensaCanjes';
						$recompensasDB = $recompensas;
					@endphp
					<div class="input-select" id="recompensaCanjesSelect">
						<div class="tooltip-container"> 
							<span class="tooltip" id="idRecompensaCanjesTooltip"></span>
						</div>
						<input class="input-select-item" type="text" id='{{ $idRecompensaInput }}' maxlength="200" autocomplete="off" placeholder="Código | Tipo | Descripción | Puntos"
							oninput="filterOptions('{{ $idRecompensaInput }}', '{{ $idRecompensaOptions }}'), validateNumComprobanteInputNoEmpty(this)
									validateOptionRecompensaCanjes(this, '{{ $idRecompensaOptions }}', '{{ $idRecompensaMessageError }}', {{ json_encode($recompensasDB) }})"
							onclick="toggleOptions('{{ $idRecompensaInput }}', '{{ $idRecompensaOptions }}')">
						<ul class="select-items" id='{{ $idRecompensaOptions }}'>
							@foreach ($recompensasDB as $recompensa)
								@php
									$pointSufix = ($recompensa->costoPuntos_Recompensa == 1) ? " punto" : " puntos";
									$value = implode(" | ", [
										$recompensa->idRecompensa,
										$recompensa->nombre_TipoRecompensa,
										$recompensa->descripcionRecompensa,
										$recompensa->costoPuntos_Recompensa . $pointSufix,
										$recompensa->stock_Recompensa . " unid. stock",
									]);
									$idRecompensa = $recompensa->idRecompensa;
									$costoPuntosRecompensa = $recompensa->costoPuntos_Recompensa;
								@endphp
								
								<li onclick="selectOptionRecompensaCanjes('{{ $value }}', '{{ $idRecompensaInput }}', '{{ $idRecompensaOptions }}',
																		'{{ $idRecompensa }}')">
									{{ $value }}
								</li>
							@endforeach
						</ul>
					</div>
				</div>
				<div class="verticalPairGroup">
					<label class="primary-label noEditable centered" id="labelCantidadRecompensa_Canjes">Cantidad </label>
					<div class="input-counter">
							<div class="tooltip-container">
								<span class="tooltip" id="idCantidadCanjesTooltip"></span>
							</div>
							<input class="input-item persist-input" id="cantidadRecompensaCanjesInput" type="text" maxlength="3" 
									placeholder="0" oninput="validateNumberWithMaxLimitRealTime(this, 100)">
							<div class="counters">
							<span class="material-symbols-outlined noUserSelect" id="incrementButton">add</span>
							<span class="material-symbols-outlined noUserSelect" id="decrementButton">remove</span>
						</div>
					</div>
				</div>

				<div class="group-items centered gap">
					<x-btn-addRowTable-item
						id="idAgregarRecompensaTablaBtn"
						type="button"
						onclick="agregarFilaRecompensa()">
						Agregar a tabla
					</x-btn-addRowTable-item>

					<x-btn-delete-item 
						id="idQuitarRecompensaTablaBtn" 
						type="button"
						onclick="eliminarFilaTabla()">
						Quitar
					</x-btn-delete-item>
				</div>
			</div>	

			<!--Tabla de canjes-->
			<div class="fithCanjesRow">
				<div class="tblCanjesContainer">
					<table class="ownTable" id="tblRecompensasCanjes">
						<thead>
							<gi>
								<th class="celda-centered" id="celdaNumeroOrdenRecompensa">#</th>
								<th class="celda-centered" id="celdaCodigoRecompensa">Código</th>
								<th class="celda-centered" id="celdaTipoRecompensa">Tipo</th>
								<th class="celda-centered" id="celdaDescripcionRecompensa">Descripción</th>
								<th class="celda-centered">Stock restante</th>
								<th class="celda-centered" id="celdaCantidadnRecompensa">Cantidad</th>
								<th class="celda-centered" id="celdaCostoPuntosRecompensa">Costo puntos</th>
								<th class="celda-centered" id="celdaPuntosTotalesRecompensa">Puntos Totales</th>
							</tr>
						</thead>
						<tbody>
							{{-- Las filas se crearán dinámicamente según el usuario vaya agregando más recompensas 
							<tr>
								<td class="celda-centered">1</td>
								<td class="celda-centered">2</td>
								<td class="celda-centered">3</td>
								<td class="celda-centered">4</td>
								<td class="celda-centered">5</td>
								<td class="celda-centered">6</td>
								<td class="celda-centered">7</td>
							</tr> --}}
						</tbody>
						<tfoot class="hidden">
							<tr>
								<td colspan="7" class="celda-righted"><strong>Total</strong></td>
								<div class="tooltip-container">
									<span class="tooltip red" id="idCeldaTotalPuntosTooltip"></span>
								</div>
								<td class="celda-centered" id="celdaTotalPuntos">0</td>
							</tr> 
						</tfoot>
					</table>
					
					<span id="tblCanjesMessageBelow">Aún no hay recompensas agregadas</span>
				</div>

				<div>
					<div class="resumenContainer" id="idResumenContainer">
						<h3>RESUMEN</h3>
						<div class="resumenContent">
							<h4>Puntos actuales</h4>
							<label class="labelPuntosComprobante" id="labelPuntosComprobante">0</label>

							<h4>Puntos a canjear</h4>
							<input id="inputPuntosCanjeados_Canje_Hidden" name="puntosCanjeados_Canje" readonly>

							<h4>Puntos restantes</h4>
							<input id="inputPuntosRestantes_Canje_Hidden" name="puntosRestantes_Canje" readonly>
						</div>
					</div>
					
					<div class="btnCanjesSectionContainer"> 
						<button type="button" class="btn btn-secondary" onclick="cleanAllCanjesSection()">Limpiar tabla</button>
						<button type="button" class="btn btn-primary" id="btnGuardarCanje" onclick="guardarCanje('{{ $idForm }}', '{{ $idComentario }}')">Guardar canje</button>
					</div>
				</div>
			</div>
			<input type="hidden" id="jsonRecompensas" name="recompensas_Canje" readonly>
			<input type="text" id='{{ $idComentario }}' name="comentario_Canje" readonly>
		</form>

		<x-modalConfirmSolicitudCanje 
			:idConfirmModal="'modalConfirmActionRegistrarCanje'"
			:commentLabel="'Comentario (opcional)'"
			:message="'¿Está seguro de guardar el canje?'"
			:placeholder="'Agregar información adicional ...'"
			:auxVar="$nuevoIdCanje"
		/>
		
		<x-modalSuccessAction 
			:idSuccesModal="'successModalCanjeStore'"
			:message="'Canje registrado correctamente'"
		/>
	</div>
@endsection	

@push('scripts')
	<script src="{{ asset('js/registrarCanjes.js') }}"></script>
	<script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successCanjeStore'))
                    openModal('successModalCanjeStore');
            @endif
        });
    </script>
@endpush