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
						<h2> {{ $canjeData->idCanje }} </h2>
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
					<h2>: 2024-10-31</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>FECHA Y HORA CANJEADA</h2>
				</td>
				<td>
					<h2>: 2024-10-31</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>NÚMERO DE VENTA INTERMEDIADA</h2>
				</td>
				<td>
					<h2>: B001-000034</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>FECHA Y HORA DE EMISIÓN VENTA INTERMEDIADA</h2>
				</td>
				<td>
					<h2>: 2024-10-31</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>TÉCNICO</h2>
				</td>
				<td>
					<h2>: Carrasco, Manuel</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>DNI</h2>
				</td>
				<td>
					<h2>: 77889922</h2>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="tblDetalleHistorialCanjePDF-container">
		<div class="form-group gap">
			<label class="primary-label noEditable">Recompensas canjeadas</label>
		</div>
		<table class="ownTable" id="tblDetalleHistorialCanjePDF">
			<thead>
				<tr>
					<th class="celda-centered" id="celdaDescripcionRecompensa">DESCRIPCIÓN</th>
					<th class="celda-centered" id="celdaTipoRecompensa">TIPO</th>
					<th class="celda-centered" id="celdaCantidadnRecompensa">CANTIDAD</th>
					<th class="celda-centered" id="celdaCostoPuntosRecompensa">COSTO PUNTOS</th>
					<th class="celda-centered" id="celdaPuntosTotalesRecompensa">TOTAL</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="celda-centered">3</td>
					<td class="celda-centered">4</td>
					<td class="celda-centered">5</td>
					<td class="celda-centered">6</td>
					<td class="celda-centered">7</td>
				</tr> 
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="celda-righted" id="celdaLabelTotalPuntos"><strong>TOTAL PUNTOS</strong></td>
					<td class="celda-centered" id="celdaTotalPuntos">0</td>
				</tr> 
			</tfoot>
		</table>
	</div>
	
	<table class="tableResumenCanjePDF">
	</table>
</body>
</html>