const domainPattern = /^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
let newDomainInputEditarDominioCorreo = document.getElementById('newDomainInputEditarDominioCorreo');
let editarDominioCorreoMessageError = document.getElementById('editarDominioCorreoMessageError');

function guardarModalEditarDominioCorreo(idModal, idForm) {
    if (!domainPattern.test(newDomainInputEditarDominioCorreo.value)) {
        editarDominioCorreoMessageError.textContent = 'El dominio nuevo debe seguir un formato similar a "dominionuevo.com"';
        editarDominioCorreoMessageError.classList.add('shown');
        return;
    }

    editarDominioCorreoMessageError.classList.remove('shown');
    guardarModal(idModal, idForm);
}