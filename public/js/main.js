function consoleLogJSONItems(items) {
    console.log(JSON.stringify(items, null, 2));
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
    const loadingSpinner = document.createElement('span');
    loadingSpinner.className = 'spinner';
    element.appendChild(loadingSpinner);

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
            const spinner = element.querySelector('.spinner');
            if (spinner) {
                element.removeChild(spinner);
            }

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