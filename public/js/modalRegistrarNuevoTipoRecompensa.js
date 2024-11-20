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

function isTipoRecompensaDuplicado(tiposRecompensasDB) {
    const nombre = nombreTipoRecompensaInput.value.trim(); 

    const tipoRecompensaExistente = tiposRecompensasDB.find(tipoRecompensa => tipoRecompensa.nombre_TipoRecompensa === nombre);

    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!tipoRecompensaExistente; 
}

function validarCamposCorrectosFormularioRegistrarNuevoTipoRecompensa(tiposRecompensasDB) {
    mensajeCombinado = "";
    var returnError = false;

    if (isTipoRecompensaDuplicado(tiposRecompensasDB)) {
        console.log("isTipoRecompensaDuplicado");
        mensajeCombinado += "El nombre de este tipo de recompensa ya ha sido registrado anteriormente.";
        returnError = true;
    }

    if (returnError) {
        return false;
    }

    idGeneralTipoRecompensaMessageError.classList.remove("shown");
    return true;
}

function guardarmodalRegistrarNuevoTipoRecompensa(idModal, idForm, tiposRecompensasDB) {
	if (validarCamposVaciosFormularioRegistrarNuevoTipoRecompensa()) {
        if (validarCamposCorrectosFormularioRegistrarNuevoTipoRecompensa(tiposRecompensasDB)) {
            idGeneralTipoRecompensaMessageError.classList.remove("shown");
            guardarModal(idModal, idForm);	
		} else {
			idGeneralTipoRecompensaMessageError.textContent = mensajeCombinado;
			idGeneralTipoRecompensaMessageError.classList.add("shown");
		}
	} else {
        idGeneralTipoRecompensaMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        idGeneralTipoRecompensaMessageError.classList.add("shown");
    }
}