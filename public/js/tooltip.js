function showHideTooltip(tooltipSpan, message) {
    const tooltipContainer = tooltipSpan.closest('.tooltip-container');
    const input = tooltipContainer.nextElementSibling;
    // Configurar el mensaje y el ancho del tooltip
    tooltipSpan.textContent = message;

    // Calcular y aplicar la posición
    const tooltipWidth = tooltipSpan.offsetWidth;
    const inputWidth = input.offsetWidth;
    const tooltipLeft = (inputWidth - tooltipWidth) / 2;
    const offset = 3; // Margen adicional

    // Obtener la posición del contenedor y del input
    const containerTop = tooltipContainer.getBoundingClientRect().top;
    const inputTop = input.getBoundingClientRect().top;
    const tooltipHeight = tooltipSpan.getBoundingClientRect().height;

    tooltipSpan.style.position = 'absolute';
    tooltipSpan.style.left = `${tooltipLeft}px`;
    tooltipSpan.style.top = `${inputTop - containerTop - tooltipHeight - offset}px`;

    // Asegúrate de que el tooltip está visible
    tooltipSpan.classList.add("shown");

    // Limpiar cualquier temporizador previo
    if (tooltipSpan.hideTimeout) {
        clearTimeout(tooltipSpan.hideTimeout);
    }

    // Ocultar el tooltip después de 3 segundos
    tooltipSpan.hideTimeout = setTimeout(() => {
        tooltipSpan.classList.remove("shown");
        tooltipSpan.hideTimeout = null; // Limpiar el temporizador
    }, 3000);
}