@extends('layouts.layoutDashboard')

@section('title', 'Canjes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/canjesStyle.css') }}">
@endpush

@section('main-content')
	<div class="canjesContainer">
		 <!-- Variables globales -->
		 @php
			$idInput = 'tecnicoCanjesInput';
			$idTecnicoOptions = 'tecnicoCanjesOptions';
			$idTecnicoMessageError = "messageErrorTecnicoCanjes";
			$tecnicosDB = $tecnicos;
		@endphp

		<div class="firstCanjesRow">
			<h3>Registrar nuevo canje</h3>
			<div class="fechaContainer">
				<label class="secondary-label"> Fecha: </label>
				<input class="input-item" id ="idFechaCanjeInput" type="date" disabled>
			</div>
		</div>

		<div class="secondCanjesRow">
			<div class="verticalPairGroup tooltipInside">
				<label class="primary-label"> Técnico </label>
				<div class="tooltip-container">
					<span class="tooltip red" id="idTecnicoCanjesTooltip">Este es el mensaje del tooltip</span>
				</div>
				<div class="input-select" id="tecnicoSelect">
					<input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="DNI - Nombre"
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
				<label class="primary-label noEditable"> Puntos Actuales </label>
				<input class="input-item" id="puntosActualesCanjesInput" maxlength="4" 
					   placeholder="0" name="aa" disabled>
			</div>
			<span class="inline-alert-message" id="{{ $idTecnicoMessageError }}"> No se encontró el técnico buscado </span>      
		</div>

		<div class="thirdCanjesRow">
			<div class="verticalPairGroup tooltipInside">
				<label class="primary-label" id="numComprobanteLabel"> Número de comprobante </label>
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
						:onSpanClickFunction="'hideResumeContainer'"
						:onClickFunction="'toggleNumComprobanteCanjesOptions'"
				/>
			</div>

			<div class="verticalPairGroup">
				<label class="primary-label noEditable"> Puntos generados </label>
				<input class="input-item noEditable" id="puntosGeneradosCanjesInput" maxlength="4" 
					   placeholder="0" disabled>
			</div>

			<div class="verticalPairGroup">
				<label class="primary-label noEditable centered"> Puntos restantes </label>
				<input class="input-item noEditable" id="puntosRestantesCanjesInput" maxlength="4" 
					   placeholder="0" disabled>
			</div>

			<div class="verticalPairGroup">
				<label class="primary-label noEditable centered"> Cliente  </label>
				<textarea class="textarea" id="clienteCanjesTextarea" 
						type="text"
						rows="2" 
						placeholder="Nombre&#10;Tipo de Doc.&#10;Número de Doc." readonly></textarea> 
			</div>

			<div class="verticalPairGroup">
				<label class="primary-label noEditable centered"> Fecha Emisión </label>
				<input class="input-item noEditable " id ="fechaEmisionCanjesInput" type="date" disabled>
			</div>

			<div class="verticalPairGroup noEditable">
				<label class="primary-label noEditable centered"> Fecha Cargada </label>
				<input class="input-item noEditable" id ="fechaCargadaCanjesInput" type="date" disabled>
			</div>
		</div>

		<div class="fourthCanjesRow">
			<div class="verticalPairGroup tooltipInside">
				<label class="primary-label"> Recompensas </label>
				<div class="tooltip-container">
					<span class="tooltip red" id="idRecompensasCanjesTooltip">Este es el mensaje del tooltip</span>
				</div>
				 @php
					$idRecompensaInput = 'recompensasCanjesInput';
					$idRecompensaOptions = 'recompensaOptions';
					$idRecompensaMessageError = 'messageErrorRecompensaCanjes';
					$recompensasDB = $RecompensasWithoutEfectivo;
				@endphp
				<div class="input-select" id="tecnicoSelect">
					<div class="tooltip-container"> <!-- Aquí se manejará el color del tooltip dinámicamente -->
						<span class="tooltip" id="idRecompensaCanjesTooltip">Este es el mensaje del tooltip</span>
					</div>
					<input class="input-select-item" type="text" id='{{ $idRecompensaInput }}' maxlength="200" placeholder="Código | Tipo | Descripción"
						oninput="filterOptions('{{ $idRecompensaInput }}', '{{ $idRecompensaOptions }}'), validateNumComprobanteInputNoEmpty(this)
								validateOptionRecompensaCanjes(this, '{{ $idRecompensaOptions }}', '{{ $idRecompensaMessageError }}', {{ json_encode($recompensasDB) }})"
						onclick="toggleOptions('{{ $idRecompensaInput }}', '{{ $idRecompensaOptions }}')">
					<ul class="select-items" id='{{ $idRecompensaOptions }}'>
						@foreach ($recompensasDB as $recompensa)
							@php
								$value = $recompensa->idRecompensa . " | " . $recompensa->tipoRecompensa .
										 " | " . $recompensa->descripcionRecompensa . " | " . $recompensa->costoPuntos_Recompensa . " puntos";
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
						<input class="input-item persist-input" id="cantidadRecompensaCanjesInput" type="text" maxlength="3" 
								placeholder="0" name="cantidadRecompensa_Canje" oninput="validateNumberWithMaxLimitRealTime(this, 100)">
						<div class="counters">
						<span class="material-symbols-outlined" id="incrementButton">add</span>
						<span class="material-symbols-outlined" id="decrementButton">remove</span>
					</div>
				</div>
			</div>

			<x-btn-addRowTable-item
				id="idAgregarRecompensaTablaBtn" 
				onclick="agregarFilaRecompensa()">
				Agregar a tabla
			</x-btn-addRowTable-item>

			<x-btn-delete-item 
				id="idQuitarRecompensaTablaBtn" 
				onclick="eliminarFilaTabla()">
				Quitar
			</x-btn-delete-item>
		</div>	

		<!--Tabla de canjes-->
        <div class="fithCanjesRow">
			<div class="tblCanjesContainer">
				<table class="ownTable" id="tblCanjes">
					<thead>
						<tr>
							<th class="celda-centered" id="celdaNumeroOrdenRecompensa">#</th>
							<th class="celda-centered" id="celdaCodigoRecompensa">Código</th>
							<th class="celda-centered" id="celdaTipoRecompensa">Tipo</th>
							<th class="celda-centered" id="celdaDescripcionRecompensa">Descripción</th>
							<th class="celda-centered" id="celdaCostoPuntosRecompensa">Costo puntos</th>
							<th class="celda-centered" id="celdaCantidadnRecompensa">Cantidad</th>
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
							<td colspan="6" class="celda-righted"><strong>Total</strong></td>
							<td class="celda-centered" id="celdaTotalPuntos">0</td>
						</tr> 
					</tfoot>
				</table>
				<span id="tblCanjesMessageBelow">Aún no hay recompensas agregadas</span>
			</div>

			<div class="resumenContainer" id="idResumenContainer">
				<h3>RESUMEN</h3>
				<div class="resumenContent">
					<h4>Puntos Comprobante</h4>
					<label class="labelPuntosComprobante" id="labelPuntosComprobante"></label>
					<h4>Puntos Canjeados</h4>
					<label class="labelPuntosCanjeados" id="labelPuntosCanjeados"></label>
					<h4>Puntos Restantes</h4>
					<label class="labelPuntosRestantes" id="labelPuntosRestantes"></label>
				</div>
			</div>
        </div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/canjes.js') }}"></script>
@endpush