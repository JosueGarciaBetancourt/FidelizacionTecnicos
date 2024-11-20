let codigoRecompensaInput = document.getElementById('codigoRecompensaInput');
let tipoRecompensaInput = document.getElementById('tipoRecompensaInput');
let descripcionRecompensaTextarea = document.getElementById('descripcionRecompensaTextarea');
let costoUnitarioInput = document.getElementById('costoUnitarioInput');
let stockRecompensaInput = document.getElementById('stockRecompensaInput')
let registrarRecompensaMessageError = document.getElementById('registrarRecompensaMessageError');

let formInputsArray = [
    codigoRecompensaInput,
	tipoRecompensaInput,
	descripcionRecompensaTextarea,
	costoUnitarioInput, 
    stockRecompensaInput,
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
    return allFilled;
}

function isRecompensaDuplicada(recompensasDB) {
    // Obtener los valores de tipo y descripción
    const tipo = tipoRecompensaInput.value.trim(); // Eliminamos espacios en blanco
    const descripcion = descripcionRecompensaTextarea.value.trim();

    // Buscar si existe una recompensa con el mismo tipo y descripción
    const recompensaExistente = recompensasDB.find(recompensa => 
        recompensa.nombre_TipoRecompensa === tipo && 
        recompensa.descripcionRecompensa === descripcion
    );

    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!recompensaExistente; 
}

function validarCamposCorrectosFormularioRegistrar(recompensasDB) {
    mensajeCombinado = "";
    var returnError = false;

    if (costoUnitarioInput.value == 0) {
        mensajeCombinado += "El costo unitario no puede ser 0. ";
        returnError = true;
	}

    if (stockRecompensaInput.value == 0) {
        mensajeCombinado += "El stock no puede ser 0.";
        returnError = true;
	}

    if (isRecompensaDuplicada(recompensasDB)) {
        mensajeCombinado += "Esta recompensa ya ha sido registrada con los valores de tipo y descripción actuales.";
        returnError = true;
    }

    if (returnError) {
        return false;
    }

    registrarRecompensaMessageError.classList.remove("shown");
    return true;
}

function guardarModalRegistrarNuevaRecompensa(idModal, idForm, recompensasDB) {
	if (validarCamposVaciosFormularioRegistrar()) {
		if (validarCamposCorrectosFormularioRegistrar(recompensasDB)) {
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