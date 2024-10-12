function openModal(modalId, origin=null) {
    if (origin) {
        // Establecer el valor de origen en el campo oculto
        document.getElementById('origin').value = origin;
    }

    var modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
        
        // Recuperar el array de modales abiertos y agregar el nuevo
        let openModals = JSON.parse(localStorage.getItem('openModals')) || [];
        if (!openModals.includes(modalId)) {
            openModals.push(modalId);
            localStorage.setItem('openModals', JSON.stringify(openModals));
        }
    }
}

function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.querySelector('.modal-dialog').classList.remove('open');
        setTimeout(function() {
            modal.style.display = 'none';
        }, 300); // Espera 0.3 segundos (igual a la duración de la transición CSS)
        
         // Elimina el modal de la lista de modales abiertos
         let openModals = JSON.parse(localStorage.getItem('openModals')) || [];
         openModals = openModals.filter(id => id !== modalId);
         if (openModals.length > 0) {
            localStorage.setItem('openModals', JSON.stringify(openModals));
         } else {
            document.body.style.overflow = ''; //Permitir el scroll de fondo luego de cerrar todos los modales
            localStorage.removeItem('openModals');
         }
    }
}

// Función para simular clic en el input file al hacer clic en el botón
function handleFileSelect() {
    const fileInput = document.getElementById('fileInput');
    fileInput.value = ''; // Limpia el valor del input antes de seleccionar el archivo
    fileInput.click();
}

var fileInput = document.getElementById('fileInput');
var fileArea = document.getElementById('fileArea');

if (fileInput) {
    // Event listener para cuando ya se seleccionó un archivo
    document.getElementById('fileInput').addEventListener('change', function() {
        var file = this.files[0]; // Obtener el archivo seleccionado
        if (file) {
            analizarXML(file);
        }
    });
} else {
    console.warn('El elemento fileInput no existe en el DOM actual.');
}

if (fileArea) {
    // Event listener para soltar sobre el área
    document.getElementById('fileArea').addEventListener('drop', function(event) {
        event.preventDefault();
        var file = event.dataTransfer.files[0]; // Obtener el archivo soltado
        if (file) {
            checkFileAccess(file).then((isAccessible) => {
                if (isAccessible) {
                    analizarXML(file);
                }
            }).catch((error) => {
                console.error('Error al revisar acceso a archivo:', error);
            });
        }
    });
} else {
    console.warn('El elemento fileArea no existe en el DOM actual.');
}

function allowDrop(event) {
    event.preventDefault(); 
    document.getElementById('fileArea').classList.add('drag-over');
}

function removeDrop(event) {
    event.preventDefault();
    document.getElementById('fileArea').classList.remove('drag-over');
}

function handleDrop(event) {
    event.preventDefault();
    document.getElementById('fileArea').classList.remove('drag-over');
}



// Función para verificar el acceso al archivo usando promesas
function checkFileAccess(file) {
    return new Promise((resolve, reject) => {
        // Comprobar el tipo de archivo
        if (file.type !== 'text/xml' && file.type !== '') {
            console.error('Tipo de archivo no permitido:', file.type);
            reject('Tipo de archivo no permitido');
        }

        // Intentar leer el archivo para verificar el acceso
        const reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function() {
            console.log('Acceso al archivo permitido');
            resolve(true);
        };
        reader.onerror = function() {
            reject('Acceso al archivo denegado');
        };
    });
}

document.addEventListener("DOMContentLoaded", function() {
    closeOptionsOnClickOutside();
    setOnlySelectInputFocusColor();

    let openModals = JSON.parse(localStorage.getItem('openModals')) || [];
    openModals.forEach(modalId => openModal(modalId));
});

function setOnlySelectInputFocusColor() {
    document.   addEventListener('click', function(event) {
        var elements = document.querySelectorAll('.onlySelectInput-container');
        elements.forEach(function(element) {
            var isClickInside = element.contains(event.target);
            if (!isClickInside) {
                element.classList.remove('activeFocus');
            } else {
                element.classList.add('activeFocus'); // Mantener el color de foco si está dentro
            }
        });
    });
}

function toggleOptions(idInput, idOptions) {
    var input = document.getElementById(idInput);
    var options = document.getElementById(idOptions);

    if (options) {
        if (input.value && !input.classList.contains("onlySelectInput")) {
            filterOptions(idInput, idOptions);
        } else {
            if (options.classList.contains('show')) {
                options.classList.remove('show');
            } else {
                options.classList.add('show');
            }
        }
    }
}

function filterOptions(idInput, idOptions) {
    var input, filter, ul, li, i, txtValue, hasVisibleOptions = false;
    input = document.getElementById(idInput);
    filter = input.value.toUpperCase();
    ul = document.getElementById(idOptions);
    li = ul.getElementsByTagName('li');
    
    for (i = 0; i < li.length;   i++) {
        txtValue = li[i].textContent || li[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
            hasVisibleOptions = true;
        } else {
            li[i].style.display = "none";
        }
    }

    if (hasVisibleOptions) {
        ul.classList.add('show');
    } else {
        ul.classList.remove('show');
    }
}

function selectOption(value, idInput, idOptions) {
    var input = document.getElementById(idInput);
    var options = document.getElementById(idOptions);

    if (input) {
        input.value = value;
        options.classList.remove('show'); // Ocultar las opciones
    } else {
        console.error('El elemento con id ' + idOptions + ' no se encontró en el DOM');
    }
}

function clearInput(idInput) {
    var input = document.getElementById(idInput); 
    if (input) {
        input.value = ''; // Limpia el valor del input
    } else {
        console.error('No se encontró un input siguiente para el contenedor ' + container + '.');
    }
}

function closeOptionsOnClickOutside() {
    // Encuentra todos los elementos select en el documento
    var selects = document.querySelectorAll('.input-select');
    
    // Función para manejar el clic fuera del select
    function handleClickOutside(event) {
        var isClickInside = false;

        // Recorre todos los selects y verifica si el clic fue dentro de uno
        selects.forEach(function(select) {
            var options = select.querySelector('ul');
            if (options) {
                if (select.contains(event.target) || options.contains(event.target)) {
                    isClickInside = true;
                } else {
                    options.classList.remove('show');
                }
            }
        });
    }

    // Añadir el event listener de clic en el documento
    document.addEventListener('click', handleClickOutside);
}

function validateNumberRealTime(input) {
    // Elimina todos los caracteres que no sean dígitos como "e" ó "-"
    input.value = input.value.replace(/[^0-9]/g, '');
}

function validateRealTimeInputLength(input, length) {
    if (input.value.length > length) {
        input.value = input.value.slice(0, length);
    }
}

function validateInputLength(input, length) {
    return input.value.length === length;
}

function guardarModal(idModal, idForm) {
    document.getElementById(idForm).submit();
    closeModal(idModal);
}

/*
function getAllLiText(idOptions) {
    // Obtener el elemento UL que contiene todas las opciones
    const ul = document.getElementById(idOptions);
    
    // Obtener todos los elementos LI dentro de la UL
    const liElements = ul.getElementsByTagName('li');
    
    // Extraer el texto de cada LI y almacenarlo en un array
    let tecnicos = [];
    for (let li of liElements) {
        tecnicos.push(li.textContent.trim());
    }
    
    return tecnicos;
}
Esta función está en dashboardScript.js
*/

function validateMinMaxRealTime(input, min, max) {
    // Obtener el valor actual del input
    const value = parseFloat(input.value);gi

    // Verificar si el valor es NaN o no está dentro del rango permitido
    if (isNaN(value)) {
        input.value = '';
        return;
    }

    // Ajustar el valor si está fuera del rango mínimo
    if (value < min) {
        input.value = min;
    }

    // Ajustar el valor si está fuera del rango máximo
    if (value > max) {
        input.value = max;
    }
}

function validateValueOnRealTime(input, idOptions, idMessageError, someHiddenIdInputsArray, 
                                otherInputsArray = null, itemsDB = null, searchField = null,
                                dbFieldsNameArray = null) {

    const value = input.value;
    const messageError = document.getElementById(idMessageError);
   
    // Recorrer el array y asignar valor vacío a cada input
    const clearHiddenInputs = () => {
        someHiddenIdInputsArray.forEach(idInput => {
            const inputElement = document.getElementById(idInput);
            if (inputElement) {
                inputElement.value = ""; // Asignar valor vacío
            }
        });
    };

    // Obtener todos los valores del item
    const allItems = getAllLiText(idOptions);
    
    // Comparar el valor ingresado con la lista de items
    const itemEncontrado = allItems.includes(value);

    // Dividir el valor en partes (id y nombre)
    const [id, nombre] = value.split(' - ');

    const clearInputs = () => {
        clearHiddenInputs();
        if (otherInputsArray) {
            otherInputsArray.forEach(idOtherInput => {
                const otherInputElement = document.getElementById(idOtherInput);
                if (otherInputElement) {
                    otherInputElement.value = ""; 
                }
            });
        }
    };

    if (value === "") {
        messageError.classList.remove('shown');
        clearInputs();
    } else if (!itemEncontrado) {
        clearInputs();
        messageError.classList.add('shown'); 
    }
    else {
        messageError.classList.remove('shown');
        // Actualizar los inputs ocultos
        if (id) {
            document.getElementById(someHiddenIdInputsArray[0]).value = id;
            if (nombre && someHiddenIdInputsArray.length === 2) {
                document.getElementById(someHiddenIdInputsArray[1]).value = nombre;
            }
        }

        // Rellenar otros inputs visibles si se requiere
        if (otherInputsArray && itemsDB && searchField) {
            const searchValue = id;
            const itemArraySearched = returnItemDBValueWithRequestedID(searchField, searchValue, itemsDB);
            console.log(itemArraySearched);

            if (itemArraySearched) {
                otherInputsArray.forEach((idOtherInput, index) => {
                    const otherInputElement = document.getElementById(idOtherInput);
                    if (otherInputElement) {
                        // Usar el índice para acceder al nombre del campo en dbFieldsNameArray
                        const dbField = dbFieldsNameArray[index];
                        otherInputElement.value = itemArraySearched[dbField] || ""; 
                    }
                });
            }
        }
    }
}

function returnItemDBValueWithRequestedID(searchField, searchValue, itemsDB) {
    // Buscar el objeto en itemsDB que tenga el searchField con el valor searchValue
    for (const key in itemsDB) {
        if (itemsDB[key][searchField] === searchValue) {
            return itemsDB[key]; // Devolver el objeto encontrado
        }
    }
    return null; // Retornar null si no se encuentra el objeto
}
