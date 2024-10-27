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
