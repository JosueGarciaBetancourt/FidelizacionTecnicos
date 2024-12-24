const scrollStep = 35; // En píxeles (puedes ajustar este valor)
const shortStepsElements = document.getElementsByClassName('shortSteps'); // Obtén todos los elementos con esa clase

// Recorre todos los elementos que tienen la clase 'shortSteps'
Array.from(shortStepsElements).forEach(element => {
    element.addEventListener('wheel', function(event) {
        // Evita el comportamiento predeterminado de desplazamiento
        event.preventDefault();

        // Desplazamiento controlado con scrollStep
        if (event.deltaY > 0) {
            // Desplazamiento hacia abajo
            this.scrollBy(0, scrollStep);
        } else {
            // Desplazamiento hacia arriba
            this.scrollBy(0, -scrollStep);
        }
    }, { passive: false }); // Cambiar a false para poder usar event.preventDefault()
});