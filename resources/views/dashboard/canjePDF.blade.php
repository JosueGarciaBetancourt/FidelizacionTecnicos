<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Club de técnicos | CANJE0001 | {{ $size }}</title>
	<link rel="ico" href="{{ public_path('images/mainIcon.ico') }}" type="image/ico">
	<link rel="stylesheet" href="{{ public_path('css/canjePDFStyles.css') }}" type="text/css">
</head>

<body>
	<table class="tblFirstRowCanjePDF">
		<tbody>
			<tr>
				<td>
					<img src="{{ asset('images/logo_DIMACOF.png') }}" alt="logo_Dimacof.png">
				</td>
				<td>
					<div class="infoDimacof-container">
						<h2>DIMACOF</h2>
						<h3>RUC 20140231275</h3>
						<h4>Av. Mariscal Ramón Castilla 2070, El Tambo, Huancayo - Junín</h4>
					</div>
				</td>
				<td>
					<div class="comprobante-container">
						<h2> COMPROBANTE DE CANJE</h2>
						<h2> {{ $canjeWithTecnico->idCanje }} </h2>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="tblSecondRowCanjePDF">
		<tbody>
			<tr>
				<td>
					<h2>FECHA Y HORA DE EMISIÓN</h2>
				</td>
				<td>
					<h2>: {{ $canjeWithTecnico->fechaHoraEmision }}</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>FECHA Y HORA CANJEADA</h2>
				</td>
				<td>
					<h2>: {{ $canjeWithTecnico->fechaHora_Canje }}</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>NÚMERO DE VENTA INTERMEDIADA</h2>
				</td>
				<td>
					<h2>: {{ $canjeWithTecnico->idVentaIntermediada }}</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>FECHA Y HORA DE EMISIÓN VENTA INTERMEDIADA</h2>
				</td>
				<td>
					<h2>: {{ $canjeWithTecnico->fechaHoraEmision_VentaIntermediada }}</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>TÉCNICO</h2>
				</td>
				<td>
					<h2>: {{ $canjeWithTecnico->nombreTecnico }}</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>DNI</h2>
				</td>
				<td>
					<h2>: {{ $canjeWithTecnico->idTecnico }}</h2>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="tblDetalleHistorialCanjePDF-container">
		<div class="form-group gap">
			<label class="primary-label noEditable" id="recompesasCanjeadasLabel">Recompensas canjeadas</label>
		</div>
		<table class="ownTable" id="tblDetalleHistorialCanjePDF">
			<thead>
				<tr>
					<th class="celda-centered" id="celdaDescripcionRecompensa">DESCRIPCIÓN</th>
					<th class="celda-centered" id="celdaTipoRecompensa">TIPO</th>
					<th class="celda-centered" id="celdaCantidadnRecompensa">CANTIDAD</th>
					<th class="celda-centered" id="celdaCostoPuntosRecompensa">COSTO PUNTOS</th>
					<th class="celda-centered" id="celdaPuntosTotalesRecompensa">SUBTOTAL</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($canjesRecompensas as $canjRecom)
					<tr>
						<td class="celda-centered">{{ $canjRecom->descripcionRecompensa }}</td>
						<td class="celda-centered">{{ $canjRecom->tipoRecompensa }}</td>
						<td class="celda-centered">{{ $canjRecom->cantidad }}</td>
						<td class="celda-centered">{{ $canjRecom->costoRecompensa }}</td>
						<td class="celda-centered">{{ $canjRecom->puntosTotales }}</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="celda-righted" id="celdaLabelTotalPuntos"><strong>TOTAL</strong></td>
					<td class="celda-centered" id="celdaTotalPuntos">FALTA IMPLEMENTAR</td>
				</tr> 
			</tfoot>
		</table>

		<h3 id="vendedorH3"><strong>Vendedor</strong>: <span>{{ Auth::check() ? Auth::user()->name : 'Invitado' }}</span></h3>
	</div>
	
	<table class="tableResumenCanjePDF">
	</table>
</body>
</html>