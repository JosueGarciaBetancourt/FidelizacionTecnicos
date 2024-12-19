<div class="modal first"  id="modalDetalleSolicitudCanje">
    <div class="modal-dialog" id="modalDetalleSolicitudCanje-dialog">
        <div class="modal-content" id="modalDetalleSolicitudCanje-content">
            @php
                $idCodigoSolicitudCanje = 'codigoModalDetalleSolicitudCanje';
				$idFechaHoraSolicitudCanje = 'fechaHoraModalDetalleSolicitudCanje';
				$idNumeroComprobante = 'numeroComprobanteModalDetalleSolicitudCanje';
				$idFechaHoraEmisionComprobante = 'fechaHoraEmisionComprobanteModalDetalleSolicitudCanje';
				$idDiasTranscurridos = 'diasTranscurridosModalDetalleSolicitudCanje';
				$idEstadoSolicitudCanje = 'estadoSolicitudCanjeModalDetalleSolicitudCanje';
				$idPuntosComprobante = 'puntosComprobanteModalDetalleSolicitudCanje';
				$idPuntosCanjeados = 'puntosCanjeadosModalDetalleSolicitudCanje';
				$idPuntosRestantes = 'puntosRestantesComprobanteModalDetalleSolicitudCanje';
				$idUserInput = 'userModalDetalleSolicitudCanje';
				$idComentario = 'comentarioComprobanteModalDetalleSolicitudCanje';
            @endphp
            <div class="modal-header">
				<div class="modal-title-container">
					<h5 class="modal-title" id='{{ $idCodigoSolicitudCanje }}'></h5>
					<h5 id='{{ $idFechaHoraSolicitudCanje }}'></h5>
					<h5 id='{{ $idDiasTranscurridos }}'></h5>
					<h3 id='{{ $idEstadoSolicitudCanje }}'></h3>
				</div>
                <button class="close noUserSelect" onclick="closeDetalleSolicitudCanje('modalDetalleSolicitudCanje')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyDetalleHistorialCanje">
				<div class="form-group gap">
					<label class="primary-label noEditable" for='{{ $idNumeroComprobante }}'>Número de comprobante:</label>
					<input class="input-item center" type="text" id='{{ $idNumeroComprobante }}' disabled>
					<label class="primary-label noEditable" for='{{ $idFechaHoraEmisionComprobante }}'>Fecha y hora de emisión:</label>
					<input class="input-item center" type="text" id='{{ $idFechaHoraEmisionComprobante }}' disabled>
				</div>

				<div class="form-group gap">
					<label class="primary-label noEditable" for='{{ $idPuntosComprobante }}'>Puntos comprobante:</label>
					<input class="input-item center" type="text" id='{{ $idPuntosComprobante }}' disabled>
					<label class="primary-label noEditable" for='{{ $idPuntosCanjeados }}'>Puntos canjeados:</label>
					<input class="input-item center" type="text" id='{{ $idPuntosCanjeados }}' disabled>
					<label class="primary-label noEditable" for='{{ $idPuntosRestantes }}'>Puntos restantes:</label>
					<input class="input-item center" type="text" id='{{ $idPuntosRestantes }}' disabled>
				</div>

				<div class="tblDetalleSolicitudCanje-container">
					<div class="form-group gap">
						<label class="primary-label noEditable">Recompensas:</label>
					</div>
                    <table class="ownTable" id="tblDetalleSolicitudCanje">
						<thead>
							<tr>
								<th class="celda-centered" id="celdaNumeroOrdenRecompensa">#</th>
								<th class="celda-centered" id="celdaCodigoRecompensa">Código</th>
								<th class="celda-centered" id="celdaTipoRecompensa">Tipo</th>
								<th class="celda-centered" id="celdaDescripcionRecompensa">Descripción</th>
								<th class="celda-centered" id="celdaCantidadnRecompensa">Cantidad</th>
								<th class="celda-centered" id="celdaCostoPuntosRecompensa">Costo puntos</th>
								<th class="celda-centered" id="celdaPuntosTotalesRecompensa">Puntos Totales</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="celda-centered">1</td>
								<td class="celda-centered">2</td>
								<td class="celda-centered">3</td>
								<td class="celda-centered">4</td>
								<td class="celda-centered">5</td>
								<td class="celda-centered">6</td>
								<td class="celda-centered">7</td>
							</tr> 
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6" class="celda-righted"><strong>Total</strong></td>
								<td class="celda-centered" id="celdaTotalPuntos">0</td>
							</tr> 
						</tfoot>
					</table>
                </div>

				<div id="userInfoContainer">
					<div class="form-group gap">
						<label class="primary-label noEditable" for='{{ $idUserInput }}'>Usuario:</label>
						<input class="input-item" type="text" id='{{ $idUserInput }}' maxlength="80" placeholder="No registrado" disabled>
					</div>
	
					<div class="form-group gap">
						<label class="primary-label noEditable" for='{{ $idComentario }}'>Comentario:</label>
						<input class="input-item" type="text" id='{{ $idComentario }}' maxlength="80" placeholder="No registrado" disabled>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDetalleSolicitudCanje('modalDetalleSolicitudCanje')">Cerrar</button>
            </div>
        </div>
    </div>
</div>

