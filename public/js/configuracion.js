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

    function saveConfig() {
        // Guardar configuración en localStorage
        localStorage.setItem('darkMode', darkModeToggle.checked);
        localStorage.setItem('fontSize', fontSizeSelect.value);
        localStorage.setItem('sidebarColor', sidebarColorInput.value);

        // Mostrar modal de éxito
        openModal('successModalConfiguracionGuardada');

        // Aplicar cambios inmediatamente
        applyDarkMode();
        applyFontSize();
        applySidebarColor();
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

// Función para abrir el modal (asumiendo que tienes una función similar en tu aplicación)
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}
