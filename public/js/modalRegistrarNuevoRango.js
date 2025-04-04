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

function isRangoDuplicado(rangosDB) {
    const nombre = nombreRangoRegistrarInput.value.trim().toLowerCase(); 
    const rangoExistente = rangosDB.find(rango => rango.nombre_Rango.toLowerCase() === nombre);

    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!rangoExistente; 
}

function validarCamposCorrectosFormularioRangoRegistrar(rangosDB) {
    mensajeCombinadoRegistrarRango = "";
    var returnError = false;

    if (isRangoDuplicado(rangosDB)) {
        returnError = true;
        const msg = `El rango "${nombreRangoRegistrarInput.value}" ya ha sido registrado anteriormente`;
        openErrorModal("errorModalRangoDelete", msg);
    }

    if (returnError) {
        return false;
    }

    registrarRangoMessageError.classList.remove("shown");
    return true;
}

function guardarModalRegistrarNuevoRango(idModal, idForm, rangosDB) {
	if (validarCamposVaciosFormularioRangoRegistrar()) {
		if (validarCamposCorrectosFormularioRangoRegistrar(rangosDB)) {
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