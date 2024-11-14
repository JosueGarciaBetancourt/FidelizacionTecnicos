<div class="modal first"  id="modalDetalleHistorialCanje">
    <div class="modal-dialog" id="modalDetalleHistorialCanje-dialog">
        <div class="modal-content" id="modalDetalleHistorialCanje-content">
            @php
                $canjesDB = $allCanjes;
                $idCodigoCanje = 'codigoCanjeModalDetalleHistorialCanje';
				$idFechaHoraCanje = 'fechaHoraCanjeModalDetalleHistorialCanje';
				$idNumeroComprobante = 'numeroComprobanteModalDetalleHistorialCanje';
            @endphp
            <div class="modal-header">
				<div class="modal-title-container">
					<h5 class="modal-title" id='{{ $idCodigoCanje }}'></h5>
					<h5 id='{{ $idFechaHoraCanje }}'></h5>
				</div>
                <button class="close" onclick="closeModal('modalDetalleHistorialCanje')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyDetalleHistorialCanje">
				<div class="form-group gap">
					<label class="primary-label noEditable" for='{{ $idNumeroComprobante }}'>Número de comprobante:</label>
					<input class="input-item center" type="text" id='{{ $idNumeroComprobante }}' disabled>
				</div>

				<div class="form-group gap">
					<label class="primary-label noEditable" for='{{ $idNumeroComprobante }}'>Número de comprobante:</label>
					<input class="input-item center" type="text" id='{{ $idNumeroComprobante }}' disabled>
				</div>

				<div class="form-group gap">
					<label class="primary-label noEditable" for='{{ $idNumeroComprobante }}'>Número de comprobante:</label>
					<input class="input-item center" type="text" id='{{ $idNumeroComprobante }}' disabled>
				</div>

				<div class="form-group gap">
					<label class="primary-label noEditable" for='{{ $idNumeroComprobante }}'>Número de comprobante:</label>
					<input class="input-item center" type="text" id='{{ $idNumeroComprobante }}' disabled>
				</div>

				<div class="tblDetalleHistorialCanje-container">
                    <table class="ownTable" id="tblDetalleHistorialCanje">
						<thead>
							<gi>
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
								<div class="tooltip-container">
									<span class="tooltip red" id="idCeldaTotalPuntosTooltip"></span>
								</div>
								<td class="celda-centered" id="celdaTotalPuntos">0</td>
							</tr> 
						</tfoot>
					</table>
                </div>
                <div class="btnDetailOption-container">
                    <button type="button" class="btn btn-DetailOption">
                        <span class="material-symbols-outlined">description</span>A4
                    </button>
                    <button type="button" class="btn btn-DetailOption">
                        <span class="material-symbols-outlined">receipt_long</span>80MM
                    </button>
                    <button type="button" class="btn btn-DetailOption">
                        <span class="material-symbols-outlined">receipt_long</span>50MM
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalDetalleHistorialCanje')">Cerrar</button>
            </div>
        </div>
    </div>
</div>

