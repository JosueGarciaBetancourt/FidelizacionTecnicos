document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkMode');
    const fontSizeSelect = document.getElementById('fontSize');
    const sidebarColorInput = document.getElementById('sidebarColor');
    const saveButton = document.getElementById('saveConfig');

    // Cargar configuración guardada
    loadConfig();

    // Evento para guardar la configuración
    saveButton.addEventListener('click', saveConfig);

    // Eventos para aplicar cambios en tiempo real
    darkModeToggle.addEventListener('change', applyDarkMode);
    fontSizeSelect.addEventListener('change', applyFontSize);
    sidebarColorInput.addEventListener('input', applySidebarColor);

    function loadConfig() {
        // Cargar configuración desde localStorage
        darkModeToggle.checked = localStorage.getItem('darkMode') === 'true';
        fontSizeSelect.value = localStorage.getItem('fontSize') || 'medium';
        sidebarColorInput.value = localStorage.getItem('sidebarColor') || '#007bff';

        // Aplicar configuración cargada
        applyDarkMode();
        applyFontSize();
        applySidebarColor();
    }

    let maxdaysCanjeSettingsInput = document.getElementById("maxdaysCanjeSettingsInput");
    let diasAgotarVentaIntermediadaNotificacionInput = document.getElementById("diasAgotarVentaIntermediadaNotificacionInput");

    function validateSettingsVariables() {
        //const puntosMinRangoPlata = parseInt(document.getElementById("puntosMinRangoPlataSettingsInput").value, 10);
        //const puntosMinRangoOro = parseInt(document.getElementById("puntosMinRangoOroSettingsInput").value, 10);
        //const puntosMinRangoBlack = parseInt(document.getElementById("puntosMinRangoBlackSettingsInput").value, 10);
        
        /* if (isNaN(puntosMinRangoPlata) || isNaN(puntosMinRangoOro) || isNaN(puntosMinRangoBlack)) {
            return false; // Evita errores si algún campo está vacío o tiene valores inválidos
        }
    
        if (puntosMinRangoPlata >= puntosMinRangoOro || puntosMinRangoPlata >= puntosMinRangoBlack || puntosMinRangoOro >= puntosMinRangoBlack) {
            const msg = "Los valores de los rangos son incorrectos. Asegúrate de que Plata < Oro < Black y vuelve a guardar.";
            openErrorModal("errorModalConfiguracion", msg);
            return false;
        } */
        const newMaxdaysCanje = parseInt(maxdaysCanjeSettingsInput.value, 10);
        const newDiasAgotarVentaIntermediadaNotificacion = parseInt(diasAgotarVentaIntermediadaNotificacionInput.value, 10);

        if (newMaxdaysCanje < newDiasAgotarVentaIntermediadaNotificacion) {
            const msg = `Los días antes para notificar a un técnico sobre agotamiento de canje (${newDiasAgotarVentaIntermediadaNotificacion}) \
                        deben ser menores a los días máximos para canjear una venta (${newMaxdaysCanje}).`;
            openErrorModal("errorModalConfiguracion", msg);
            return false;
        }       
        return true;
    }

    function saveConfig() {
        // Guardar configuración en localStorage
        localStorage.setItem('darkMode', darkModeToggle.checked);
        localStorage.setItem('fontSize', fontSizeSelect.value);
        localStorage.setItem('sidebarColor', sidebarColorInput.value);

        // Aplicar cambios inmediatamente
        applyDarkMode();
        applyFontSize();
        applySidebarColor();
        
        // Validar variables generales
        if (validateSettingsVariables()) {
            const newMaxdaysCanje = parseInt(maxdaysCanjeSettingsInput.value, 10);

            // Confirmar acción cuando cambie maxdayscanje
            //console.log(newMaxdaysCanje, maxdaysCanjeMAIN);

            if (newMaxdaysCanje != maxdaysCanjeMAIN) {
                document.getElementById("idMessageConfirmModal").innerText = `¿Está seguro de esta acción? Al modificar los Días máximos de registro de \
                                                                            venta/Días máximos para canjear una venta, algunas ventas y solicitudes de canjes\
                                                                            actualizarán sus estados a Tiempo Agotado`
                openConfirmModal('modalConfirmActionConfiguracion').then((response) => {
                    if (response) {
                        try {
                            document.getElementById("formEditaVariablesConfiguracion").submit();
                        } catch (error) {
                            console.log(error.message);
                        }
                    }
                });

                return;
            }

            // Guardar variables generales editadas
            document.getElementById("formEditaVariablesConfiguracion").submit();
        }
    }

    function applyDarkMode() {
        if (darkModeToggle.checked) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }

    function applyFontSize() {
        document.documentElement.classList.remove('font-small', 'font-medium', 'font-large');
        document.documentElement.classList.add(`font-${fontSizeSelect.value}`);
    }

    function applySidebarColor() {
        const activeLink = document.querySelector('.sidebar a.active');
        if (activeLink) {
            activeLink.style.backgroundColor = sidebarColorInput.value;
        }
    }
});

/* // Función para abrir el modal (asumiendo que tienes una función similar en tu aplicación)
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    } 
}
*/