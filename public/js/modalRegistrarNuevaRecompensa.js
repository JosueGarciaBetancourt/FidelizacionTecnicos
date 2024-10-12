let codigoRecompensaInput = document.getElementById('codigoRecompensaInput');
let tipoRecompensaInput = document.getElementById('tipoRecompensaInput');
let descripcionRecompensaTextarea = document.getElementById('descripcionRecompensaTextarea');
let costoUnitarioInput = document.getElementById('costoUnitarioInput');
let registrarRecompensaMessageError = document.getElementById('registrarRecompensaMessageError');

let formInputsArray = [
    codigoRecompensaInput,
	tipoRecompensaInput,
	descripcionRecompensaTextarea,
	costoUnitarioInput, 
];

let mensajeCombinado = "";

function validarCamposVaciosFormularioRegistrar() {
    let allFilled = true;
    formInputsArray.forEach(input => {
        if (!input.value.trim()) {
            console.log(input.value);
            allFilled = false;
        }
    });
    console.log("aaaaaa");
    return allFilled;
}

function validarCamposCorrectosFormularioRegistrar() {
    mensajeCombinado = "";
    var returnError = false;

    if (costoUnitarioInput.value == 0) {
        mensajeCombinado += "El costo unitario no puede ser 0.";
        returnError = true;
	}

    if (returnError) {
        return false;
    }

    registrarRecompensaMessageError.classList.remove("shown");
    return true;
}

function guardarModalRegistrarNuevaRecompensa(idModal, idForm) {
	if (validarCamposVaciosFormularioRegistrar()) {
		if (validarCamposCorrectosFormularioRegistrar()) {
			console.log("Enviando formulario satisfactoriamente");
			registrarRecompensaMessageError.classList.remove("shown");
			guardarModal(idModal, idForm);	
		} else {
			registrarRecompensaMessageError.textContent = mensajeCombinado;
			registrarRecompensaMessageError.classList.add("shown");
		}
	} else {
        registrarRecompensaMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        registrarRecompensaMessageError.classList.add("shown");
    }
}