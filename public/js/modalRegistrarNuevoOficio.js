let codigoOficioRegistrarInput = document.getElementById('codigoOficioInputRegistrar');
let nombreOficioRegistrarInput = document.getElementById('nombreOficioInputRegistrar');
let descripcionOficioRegistrarTextarea = document.getElementById('descripcionOficioInputRegistrar');
let registrarOficioMessageError = document.getElementById('generalRegistrarOficioError');

let formInputsArray = [
    codigoOficioRegistrarInput,
    nombreOficioRegistrarInput,
	descripcionOficioRegistrarTextarea,
];

let mensajeCombinadoRegistrarOficio = "";

function validarCamposVaciosFormularioOficioRegistrar() {
    let allFilled = true;
    formInputsArray.forEach(input => {
        if (!input.value.trim()) {
            console.log(input.value);
            allFilled = false;
        }
    });
    return allFilled;
}

function validarCamposCorrectosFormularioOficioRegistrar() {
    mensajeCombinadoRegistrarOficio = "";
    var returnError = false;

    /*if (costoUnitarioInput.value == 0) {
        mensajeCombinado += "El costo unitario no puede ser 0.";
        returnError = true;
	}*/

    if (returnError) {
        return false;
    }

    registrarOficioMessageError.classList.remove("shown");
    return true;
}

function guardarModalRegistrarNuevoOficio(idModal, idForm) {
	if (validarCamposVaciosFormularioOficioRegistrar()) {
		if (validarCamposCorrectosFormularioOficioRegistrar()) {
			console.log("Enviando formulario satisfactoriamente");
			registrarOficioMessageError.classList.remove("shown");
			guardarModal(idModal, idForm);	
		} else {
			registrarOficioMessageError.textContent = mensajeCombinadoRegistrarOficio;
			registrarOficioMessageError.classList.add("shown");
		}
	} else {
        registrarOficioMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        registrarOficioMessageError.classList.add("shown");
    }
}