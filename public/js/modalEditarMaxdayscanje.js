let editarMaxdayscanjeInput = document.getElementById('editarMaxdayscanjeInput');
let editarMaxdayscanjeError = document.getElementById('editarMaxdayscanjeError');

function guardarModalEditarMaxdayscanje(idModal, idForm) {
    if (!editarMaxdayscanjeInput.value || editarMaxdayscanjeInput.value == null || editarMaxdayscanjeInput.value == 0)  {
        editarMaxdayscanjeError.classList.add('shown');
        return;
    }

    editarMaxdayscanjeError.classList.remove('shown');

    guardarModal(idModal, idForm);
}