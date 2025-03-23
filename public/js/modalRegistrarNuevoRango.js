let codigoRangoRegistrarInput = document.getElementById('codigoRangoInputRegistrar');
let nombreRangoRegistrarInput = document.getElementById('nombreRangoInputRegistrar');
let descripcionRangoRegistrarTextarea = document.getElementById('descripcionRangoInputRegistrar');
let puntosMinimosRangoInput = document.getElementById('puntosMinimosRangoInputRegistrar');
let registrarRangoMessageError = document.getElementById('generalRegistrarRangoError');

let formInputsArray = [
    codigoRangoRegistrarInput,
    nombreRangoRegistrarInput,
	//descripcionRangoRegistrarTextarea,
    puntosMinimosRangoInput,
];

let mensajeCombinadoRegistrarRango = "";

function validarCamposVaciosFormularioRangoRegistrar() {
    let allFilled = true;
    formInputsArray.forEach(input => {
        if (!input.value.trim()) {
            console.log(input.value);
            allFilled = false;
        }
    });
    return allFilled;
}

function validarCamposCorrectosFormularioRangoRegistrar() {
    mensajeCombinadoRegistrarRango = "";
    var returnError = false;

    /*if (costoUnitarioInput.value == 0) {
        mensajeCombinado += "El costo unitario no puede ser 0.";
        returnError = true;
	}*/

    if (returnError) {
        return false;
    }

    registrarRangoMessageError.classList.remove("shown");
    return true;
}

function guardarModalRegistrarNuevoRango(idModal, idForm) {
	if (validarCamposVaciosFormularioRangoRegistrar()) {
		if (validarCamposCorrectosFormularioRangoRegistrar()) {
			console.log("Enviando formulario satisfactoriamente");
			registrarRangoMessageError.classList.remove("shown");
			guardarModal(idModal, idForm);	
		} else {
			registrarRangoMessageError.textContent = mensajeCombinadoRegistrarRango;
			registrarRangoMessageError.classList.add("shown");
		}
	} else {
        registrarRangoMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        registrarRangoMessageError.classList.add("shown");
    }
}