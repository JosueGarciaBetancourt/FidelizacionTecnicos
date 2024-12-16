<div class="modal first"  id="modalDetalleHistorialCanje">
    <div class="modal-dialog" id="modalDetalleHistorialCanje-dialog">
        <div class="modal-content" id="modalDetalleHistorialCanje-content">
            @php
                $canjesDB = $allCanjes;
                $idCodigoCanje = 'codigoCanjeModalDetalleHistorialCanje';
				$idFechaHoraCanje = 'fechaHoraCanjeModalDetalleHistorialCanje';
				$idNumeroComprobante = 'numeroComprobanteModalDetalleHistorialCanje';
				$idFechaHoraEmisionComprobante = 'fechaHoraEmisionComprobanteModalDetalleHistorialCanje';
				$idDiasTranscurridos = 'diasTranscurridosCanjeModalDetalleHistorialCanje';
				$idPuntosComprobante = 'puntosComprobanteModalDetalleHistorialCanje';
				$idPuntosCanjeados = 'puntosCanjeadosModalDetalleHistorialCanje';
				$idPuntosRestantes = 'puntosRestantesComprobanteModalDetalleHistorialCanje';
				$idComentario = 'comentarioComprobanteModalDetalleHistorialCanje';
            @endphp
            <div class="modal-header">
				<div class="modal-title-container">
					<h5 class="modal-title" id='{{ $idCodigoCanje }}'></h5>
					<h5 id='{{ $idFechaHoraCanje }}'></h5>
					<h5 id='{{ $idDiasTranscurridos }}'></h5>
				</div>
                <button class="close noUserSelect" onclick="closeModal('modalDetalleHistorialCanje')">&times;</button>
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

				<div class="tblDetalleHistorialCanje-container">
					<div class="form-group gap">
						<label class="primary-label noEditable">Recompensas:</label>
					</div>
                    <table class="ownTable" id="tblDetalleHistorialCanje">
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

				<div class="form-group gap">
					<label class="primary-label noEditable" for='{{ $idComentario }}'>Comentario:</label>
					<input class="input-item" type="text" id='{{ $idComentario }}' maxlength="80" placeholder="No registrado" disabled>
				</div>

				<div class="btnDetailOption-container" data-routes='{"pdf": "{{ route('canjes.pdf', ['size' => ':size', 'idCanje' => ':idCanje']) }}"}'>
					<button type="button" class="btn btn-DetailOption" id="btn_pdf_A4" data-size="A4" title="Canje en formato A4">
						<span class="material-symbols-outlined noUserSelect">description</span> A4
					</button>
					<button type="button" class="btn btn-DetailOption" id="btn_pdf_80mm" data-size="80mm" title="Canje en formato 80MM">
						<span class="material-symbols-outlined noUserSelect">receipt_long</span> 80MM
					</button>
					<button type="button" class="btn btn-DetailOption" id="btn_pdf_50mm" data-size="50mm" title="Canje en formato 50MM">
						<span class="material-symbols-outlined noUserSelect">receipt_long</span> 50MM
					</button>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeFetchModal('modalDetalleHistorialCanje')">Cerrar</button>
            </div>
        </div>
    </div>
</div>

