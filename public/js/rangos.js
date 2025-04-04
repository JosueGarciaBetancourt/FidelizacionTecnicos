/* function fillColorInputOnRealTimeIDInteger(input, idOptions, inputsArray, dbFieldsNameArray, searchIdField, itemsDB, previewColorSpan) {
    const value = input.value;

    if (!value) {
        return false; 
    }

    // Obtener todos los valores de la lista
    const allItems = getAllLiText(idOptions);
    const itemEncontrado = allItems.includes(value);
    
    if (!itemEncontrado) {
        return false; 
    }

    // Rellenar inputs visibles si se requiere
    if (inputsArray && dbFieldsNameArray && searchIdField && itemsDB) {
        const idRango = returnIDIntegerByStringID(value.split(' | ')[0]);
        const name = value.split(' | ')[1];
        const itemArraySearched = returnItemDBValueWithRequestedID(searchIdField, idRango, itemsDB);
        
        if (itemArraySearched) {
            inputsArray.forEach((idInput, index) => {
                const inputElement = document.getElementById(idInput);
                if (inputElement) {
                    // Usar el índice para acceder al nombre del campo en dbFieldsNameArray
                    const dbField = dbFieldsNameArray[index];
                    inputElement.value = itemArraySearched[dbField]; 
                    
                    // Index 0 → colorTexto_Rango, Index 1 → colorFondo_Rango
                    if (index == 0) {
                        previewColorSpan.style.color = itemArraySearched[dbField];
                    } else if  (index == 1) {
                        previewColorSpan.style.backgroundColor = itemArraySearched[dbField];
                    }
                }
            });

            previewColorSpan.textContent = name;
        }

        return true;
    }

    return false;
}

function updateColorsInput(colorTextoRangoInput, colorFondoRangoInput, previewColorSpan,
                            colorTextoRango="#3206B0", colorFondoRango="#DCD5F0", name="") {
    colorTextoRangoInput.value = colorTextoRango;
    colorFondoRangoInput.value = colorFondoRango;
    previewColorSpan.style.color = colorTextoRango;
    previewColorSpan.style.backgroundColor = colorFondoRango;
    previewColorSpan.textContent = name;
} */