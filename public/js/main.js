let todayMAIN = new Date();
let minYearMAIN = '1950';
let minDateMAIN = `${minYearMAIN}-01-01`;
let maxDateMAIN = todayMAIN.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }).split('/').reverse().join('-');

let objMaxDateMAIN = new Date(maxDateMAIN);

let csrfTokenMAIN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let baseUrlMAIN = window.location.origin /* `${window.location.origin}/FidelizacionTecnicos/public` */;

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

function obtenerFechaHoraFormateadaExportaciones() {
    const fecha = new Date();
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexados
    const anio = fecha.getFullYear();
    return `${dia}${mes}${anio}`;
}