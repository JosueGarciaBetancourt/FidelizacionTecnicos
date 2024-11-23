@php
    $confirmModal = $idConfirmModal ?? '';
    $messageToConfirm = $message ?? '';
    $auxiliarVar = $auxVar ?? '';
    //dd($confirmModal);
@endphp

<div class="modal first" id="{{ $confirmModal }}">
    <div class="modal-dialog confirm">
        <div class="modal-content confirm">
            <div class="modal-header confirm">
                <h5 class="modal-title confirm">Confirmar acción</h5>
            </div>
            <div class="modal-body confirm">
				<i class="fa-solid fa-triangle-exclamation"></i>
                <p>{{ $messageToConfirm }} {{ $auxiliarVar }}</p>
            </div>
            <div class="modal-footer confirm">
                <button type="button" class="btn btn-secondary confirm" onclick="noConfirmAction()">No</button>
                <button type="button" class="btn btn-primary confirm" onclick="yesConfirmAction()">Sí</button>
            </div>
        </div>
    </div>
</div>

