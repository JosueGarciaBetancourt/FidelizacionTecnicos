function consoleLogJSONItems(items, options = { indent: 2, maxDepth: null }) {
    try {
        // Si `items` es una cadena, intenta parsearla
        if (typeof items === 'string') {
            try {
                items = JSON.parse(items);
            } catch (parseError) {
                throw new Error('La cadena proporcionada no es un JSON válido.');
            }
        }

        // Validación: Verificar si `items` es un objeto o un array
        if (typeof items !== 'object' || items === null) {
            throw new Error('El argumento proporcionado no es un objeto o array válido.');
        }

        // Opciones por defecto
        const { indent, maxDepth } = options;

        // Función recursiva para limitar la profundidad
        function formatJSON(obj, depth = 0) {
            if (maxDepth !== null && depth >= maxDepth) {
                return typeof obj === 'object' && obj !== null ? '[Object]' : obj;
            }

            if (Array.isArray(obj)) {
                return obj.map((item) => formatJSON(item, depth + 1));
            } else if (typeof obj === 'object' && obj !== null) {
                return Object.fromEntries(
                    Object.entries(obj).map(([key, value]) => [key, formatJSON(value, depth + 1)])
                );
            }

            return obj; // Retorna valores primitivos tal cual
        }

        // Formatear el JSON con profundidad limitada
        const formattedJSON = maxDepth !== null ? formatJSON(items) : items;

        // Imprimir en consola el JSON formateado
        console.log(JSON.stringify(formattedJSON, null, indent));
    } catch (error) {
        console.error('Error al procesar el JSON:', error.message);
    }
}

function handleFormSubmission(elementId, formId, timeout = 2000) {
    const element = document.getElementById(elementId);
    const form = document.getElementById(formId);

    if (!element || !form) {
        console.error('Elemento o formulario no encontrado');
        return;
    }

    // Prevenir múltiples envíos
    if (element.dataset.submitting === 'true') {
        console.log('Envío en proceso, por favor espere...');
        return;
    }

    // Marcar como en proceso de envío
    element.dataset.submitting = 'true';
    
    // Deshabilitar el elemento
    if (element.tagName === 'BUTTON') {
        element.disabled = true;
    }

    element.classList.add('disabled');
    element.style.pointerEvents = 'none';
    element.style.opacity = '0.7';
    
    // Agregar indicador visual de carga
    /* const loadingSpinner = document.createElement('span');
    loadingSpinner.className = 'spinner';
    element.appendChild(loadingSpinner); */

    // Enviar el formulario
    try {
        form.submit();
    } catch (error) {
        console.error('Error al enviar formulario:', error);
    }

    // Restaurar el elemento después del timeout
    setTimeout(() => {
        if (element) {
            // Eliminar indicador de carga
            /* const spinner = element.querySelector('.spinner');
            if (spinner) {
                element.removeChild(spinner);
            } */

            // Restaurar el elemento
            if (element.tagName === 'BUTTON') {
                element.disabled = false;
            }
            element.classList.remove('disabled');
            element.style.pointerEvents = '';
            element.style.opacity = '';
            element.dataset.submitting = 'false';
        }
    }, timeout);
}
 
// Función para enviar el mensaje de error al log de Laravel
function registrarErrorEnLaravel(mensajeError) {
    fetch('/log-error', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: mensajeError })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Error registrado en Laravel:", data.status);
    })
    .catch(error => {
        console.error("Error al enviar el mensaje al servidor:", error);
    });
}

function togglePasswordVisibility(viewPasswordIcon, idPasswordInput) {
    const passwordInput = document.getElementById(idPasswordInput);

    if (viewPasswordIcon.textContent != 'visibility') {
        viewPasswordIcon.textContent = 'visibility';
        passwordInput.type = 'text';
    } else {
        viewPasswordIcon.textContent = 'visibility_off';
        passwordInput.type = 'password';
    }
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

let csrfTokenMAIN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let baseUrlMAIN = `${window.location.origin}/FidelizacionTecnicos/Public`;

