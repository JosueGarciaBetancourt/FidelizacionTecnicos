function consoleLogJSONItems(items, options = { indent: 2 }) {
    try {
        // Validación del objeto
        if (typeof items === 'string') {
            try { items = JSON.parse(items); } 
            catch (e) { throw new Error('La cadena proporcionada no es un JSON válido.'); }
        }
        
        if (typeof items !== 'object' || items === null) {
            throw new Error('El argumento proporcionado no es un objeto o array válido.');
        }

        // Usar JSON.stringify para la visualización inicial
        console.log(items);
    } catch (error) {
        console.error(`Error al procesar JSON: ${error.message}`);
    }
}