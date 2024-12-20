@php
    $errorModal = $idErrorModal ?? '';
    $messageError = $message ?? '';
@endphp

<div class="modal first" id="{{ $errorModal }}">
    <div class="modal-dialog error">
        <div class="modal-content error">
            <div class="modal-header error">
                <h5 class="modal-title error">Error</h5>
            </div>
            <div class="modal-body error">
				<i class="fa-solid fa-circle-exclamation"></i>
                <p id="messageErrorModalFailedAction">{{ $messageError }}</p>
            </div>
            <div class="modal-footer error">
                <button type="button" class="btn btn-secondary" onclick="justCloseModal('{{ $errorModal }}')">Cerrar</button>
            </div>
        </div>
    </div>
</div>
