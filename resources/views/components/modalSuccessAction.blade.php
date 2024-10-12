@php
    $successModal = $idSuccesModal ?? '';
    $messageSucess = $message ?? '';
@endphp

<div class="modal first" id="{{ $successModal }}">
    <div class="modal-dialog success">
        <div class="modal-content success">
            <div class="modal-header success">
                <h5 class="modal-title success">¡Acción Exitosa!</h5>
            </div>
            <div class="modal-body success">
				<i class="fa-solid fa-circle-check"></i>
                <p>{{ $messageSucess }}</p>
            </div>
            <div class="modal-footer success">
                <button type="button" class="btn btn-secondary" onclick="closeModal('{{ $successModal }}')">Cerrar</button>
            </div>
        </div>
    </div>
</div>
