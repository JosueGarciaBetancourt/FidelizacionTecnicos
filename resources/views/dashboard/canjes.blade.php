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
				<label class="primary-label"> Número de comprobante </label>
				<div class="tooltip-container">
					<span class="tooltip red" id="idNumComprobanteCanjesTooltip">Este es el mensaje del tooltip</span>
				</div>
				<x-onlySelect-input 
						:idSelect="'comprobanteSelect'"
						:inputClassName="'onlySelectInput'"
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
					   placeholder="0" name="bbb" disabled>
			</div>

			<div class="verticalPairGroup">
				<label class="primary-label noEditable centered"> Puntos restantes </label>
				<input class="input-item noEditable" id="puntosRestantesCanjesInput" maxlength="4" 
					   placeholder="0" name="bbb" disabled>
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
					<input class="input-select-item" type="text" id='{{ $idRecompensaInput }}' maxlength="50" placeholder="Código | Tipo | Descripción"
						oninput="filterOptions('{{ $idRecompensaInput }}', '{{ $idRecompensaOptions }}'), validateNumComprobanteInputNoEmpty(this)
								validateOptionRecompensaCanjes(this, '{{ $idRecompensaOptions }}', '{{ $idRecompensaMessageError }}', {{ json_encode($recompensasDB) }})"
						onclick="toggleOptions('{{ $idRecompensaInput }}', '{{ $idRecompensaOptions }}')">
					<ul class="select-items" id='{{ $idRecompensaOptions }}'>
						@foreach ($recompensasDB as $recompensa)
							@php
								$value = $recompensa->idRecompensa . " | " . $recompensa->tipoRecompensa .
										 " | " . $recompensa->descripcionRecompensa;
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
				<label class="primary-label noEditable centered"> Cantidad </label>
				<input class="input-item" id="cantidadRecompensaCanjesInput" type="number" min="1" max="100" 
					   placeholder="0" name="cantidadRecompensa_Canje"
					   oninput="validateRealTimeInputLength(this, 3), validateNumberRealTime(this), 
					   			validateMinMaxRealTime(this, 1, 100)">
			</div>

			<x-btn-addRowTable-item
				id="idAgregarRecompensaTablaBtn" 
				onclick="">
				Agregar a tabla
			</x-btn-addRowTable-item>

			<x-btn-delete-item 
				id="idQuitarRecompensaTablaBtn" 
				onclick="">
				Quitar
			</x-btn-delete-item>
		</div>	

		<!--Tabla de canjes-->
        <div class="fithCanjesRow">
            <table class="ownTable" id="tblCanjes">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th class="celda-centered" id="celdaCodigoRecompensa">Código</th>
                        <th class="celda-centered">Tipo</th>
                        <th class="celda-centered">Descripción</th>
                        <th class="celda-centered">Puntos unitario</th>
                        <th class="celda-centered">Cantidad</th>
                        <th class="celda-centered">Puntos Totales</th>
                    </tr>
                </thead>
                <tbody>
					<tr>
						<td class="celda-centered">1</td>
						<td class="celda-centered">RECOM-008</td>
						<td class="celda-centered">Herramienta</td>
						<td class="celda-centered word-wrap" id="descripcionCanjes">Palustre de 9225mmREF.: BK1040 Marca: BRICKELL</td>
						<td class="celda-centered">100</td>
						<td class="celda-centered">2</td>
						<td class="celda-centered">200</td>
					</tr>
					<tr>
						<td class="celda-centered">2</td>
						<td class="celda-centered">RECOM-002</td>
						<td class="celda-centered">EPP</td>
						<td class="celda-centered word-wrap">Par de rodilleras para cerámica</td>
						<td class="celda-centered">35</td>
						<td class="celda-centered">1</td>
						<td class="celda-centered">35</td>
					</tr>
                </tbody>
            </table>

			<div class="resumenContainer" id="idResumenContainer">
				<h3>RESUMEN</h3>
				<div class="resumenContent">
					<h4>Puntos Comprobante</h4>
					<label class="labelPuntosComprobante">100</label>
					<h4>Puntos Canjeados</h4>
					<label class="labelPuntosCanjeados">100</label>
					<h4>Puntos Restantes</h4>
					<label class="labelPuntosRestantes">80</label>
				</div>
			</div>
        </div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/canjes.js') }}"></script>
@endpush