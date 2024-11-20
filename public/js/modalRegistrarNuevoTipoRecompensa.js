let codigoTipoRecompensaInput = document.getElementById('codigoTipoRecompensaInputRegistrar');
let nombreTipoRecompensaInput = document.getElementById('nombreTipoRecompensaInputRegistrar');
let idGeneralTipoRecompensaMessageError = document.getElementById('generalRegistrarTipoRecompensaError');

let formInputsArrayTipoRecompensaRegistrar = [
    codigoTipoRecompensaInput,
	nombreTipoRecompensaInput,
];

let mensajeCombinadoTipoRecompensaRegistrar = "";

function validarCamposVaciosFormularioRegistrarNuevoTipoRecompensa() {
    let allFilled = true;
    formInputsArrayTipoRecompensaRegistrar.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });

    return allFilled;
}

function guardarmodalRegistrarNuevoTipoRecompensa(idModal, idForm) {
	if (validarCamposVaciosFormularioRegistrarNuevoTipoRecompensa()) {
        idGeneralTipoRecompensaMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
	} else {
        idGeneralTipoRecompensaMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        idGeneralTipoRecompensaMessageError.classList.add("shown");
    }
}