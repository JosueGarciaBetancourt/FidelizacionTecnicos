@php
    $confirmModal = $idConfirmModal ?? '';
    $messageToConfirm = $message ?? '';
    $modalTitle = $title ?? 'Confirmar acción';
    $commentLabel = $commentLabel ?? 'Comentario';
    $placeholder = $placeholder ?? '...';
    $auxiliarVar = $auxVar ?? '';
@endphp

<div class="modal first" id="{{ $confirmModal }}">
    <div class="modal-dialog confirmSolicitudCanje">
        <div class="modal-content confirmSolicitudCanje">
            <div class="modal-header confirmSolicitudCanje">
                <h5 class="modal-title confirmSolicitudCanje">{{ $modalTitle }}</h5>
            </div>
            <div class="modal-body confirmSolicitudCanje">
                <div class="question-container">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p>{{ $messageToConfirm }} {{ $auxiliarVar }}</p>
                </div>
                <div class="comentario-container">
                    <label for="idComentarioInput">{{ $commentLabel }}:</label>
                    <input class="comentarioInput" id="idComentarioInput" type="text" maxlength="80" placeholder="{{ $placeholder }}">
                </div>
                <div class="form-group start">
                    <span class="inline-alert-message" id="comentarioAprobRechaCanjeErrorMessage">El campo comentario es obligatorio</span>      
                </div>
            </div>
            <div class="modal-footer confirmSolicitudCanje">
                <button type="button" class="btn btn-secondary confirmSolicitudCanje" onclick="noConfirmAction()">No</button>
                <button type="button" class="btn btn-primary confirmSolicitudCanje" onclick="yesConfirmAction()">Sí</button>
            </div>
        </div>
    </div>
</div>

