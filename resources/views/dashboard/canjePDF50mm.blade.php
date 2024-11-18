<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Club de técnicos | {{ $canjeWithTecnico->idCanje }} | {{ $size }}</title>
	<link rel="ico" href="{{ public_path('images/mainIcon.ico') }}" type="image/ico">
	<link rel="stylesheet" href="{{ public_path('css/canjePDF50mm.css') }}" type="text/css">
</head>

<body>
	<img id="logoDimacof" src="{{ public_path('images/logo_DIMACOF_recortado.png') }}" alt="logo_Dimacof.png">
	<div class="infoDimacof-container">
		<h2><strong>DISTRIBUIDORA MAT. CONST. Y FERRETERIA</strong></h2>
		<h3>RUC 20140231275</h3>
		<h4>Av. Mariscal Castilla 2070, El Tambo, Huancayo - Junín</h4>
	</div>
	<div class="comprobante-container">
		<h2> COMPROBANTE DE CANJE</h2>
		<h2> {{ $canjeWithTecnico->idCanje }} </h2>
	</div>

	<table class="tblSecondRowCanjePDF">
		<tbody>
			<tr>
				<td>
					<h2>F. y H. Canje</h2>
				</td>
				<td>
					<h2>: <span>{{ $canjeWithTecnico->fechaHora_Canje }}</span></h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>Núm. Venta intermediada</h2>
				</td>
				<td>
					<h2>: <span>{{ $canjeWithTecnico->idVentaIntermediada }}</span></h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>Puntos venta intermediada</h2>
				</td>
				<td>
					<h2>: <span>{{ $canjeWithTecnico->puntosComprobante_Canje }}</span></h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>F. y H. emisión venta intermediada</h2>
				</td>
				<td>
					<h2>: <span>{{ $canjeWithTecnico->fechaHoraEmision_VentaIntermediada }}</span></h2>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="tblTecnicoInfo">
		<tbody>
			<tr>
				<td>
					<h2>Técnico</h2>
				</td>
				<td>
					<h2>: <span>{{ $canjeWithTecnico->nombreTecnico }}</span></h2>
				</td>
			</tr>
			<tr>
				<td>
					<h2>DNI</h2>
				</td>
				<td>
					<h2>: <span>{{ $canjeWithTecnico->idTecnico }}</span></h2>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="tblDetalleHistorialCanjePDF-container">
		<h3 id="recompesasCanjeadasLabel">Recompensas canjeadas</h3>
		<table id="tblDetalleHistorialCanjePDF">
			<thead>
				<tr>
					<th class="celda-centered" id="celdaDescripcionRecompensa">Tipo/Descr.</th>
					<th class="celda-centered" id="celdaCantidadnRecompensa">Cant.</th>
					<th class="celda-centered" id="celdaCostoPuntosRecompensa">Costo puntos</th>
					<th class="celda-centered" id="celdaPuntosTotalesRecompensa">Subtotal</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($canjesRecompensas as $canjRecom)
					<tr>
						<td class="celda-centered tdDescrRecompensa">{{ $canjRecom->tipoRecompensa }}<br>{{ $canjRecom->descripcionRecompensa }}</td>
						<td class="celda-centered">{{ $canjRecom->cantidad }}</td>
						<td class="celda-centered">{{ $canjRecom->costoRecompensa }}</td>
						<td class="celda-centered">{{ $canjRecom->puntosTotales }}</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="celda-righted" id="celdaLabelTotalPuntos"><strong>Total</strong></td>
					<td class="celda-centered" id="celdaTotalPuntos">{{ $totalPuntos }}</td>
				</tr> 
			</tfoot>
		</table>
	</div>

	<table class="tableResumenCanjePDF-container">
		<h3 id="resumenCanjeLabel">Resumen</h3>
		<table id="tableResumenCanjePDF">
			<tbody>
				<tr>
					<td>
						<h2>Puntos venta intermediada</h2>
					</td>
					<td>
						<h2>: <span>{{ $canjeWithTecnico->puntosComprobante_Canje }}</span></h2>
					</td>
				</tr>
				<tr>
					<td>
						<h2>Puntos canjeados</h2>
					</td>
					<td>
						<h2>: <span>{{ $canjeWithTecnico->puntosCanjeados_Canje }}</span></h2>
					</td>
				</tr>
				<tr>
					<td>
						<h2>Puntos restantes</h2>
					</td>
					<td>
						<h2>: <span>{{ $canjeWithTecnico->puntosRestantes_Canje }}</span></h2>
					</td>
				</tr>
			</tbody>
		</table>
	</table>

	<h3 id="vendedorH3"><strong>Vendedor:</strong> <span>{{ Auth::check() ? Auth::user()->name : 'Invitado' }}</span></h3>
</body>
</html>