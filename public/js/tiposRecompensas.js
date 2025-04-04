function fillColorInputOnRealTimeIDIntegerNameApart(input, nameInput, idOptions, inputsArray, dbFieldsNameArray, searchIdField, itemsDB, previewColorSpan) {
    const codigoTipoRecompensa = input.value;
    const nameTipoRecompensa = nameInput.value;
    
    if (!codigoTipoRecompensa) {
        return false; 
    }

    // Obtener todos los valores de la lista
    const allItems = getAllLiText(idOptions);
    const itemEncontrado = allItems.includes(codigoTipoRecompensa);
    
    if (!itemEncontrado) {
        return false; 
    }

    // Rellenar inputs visibles si se requiere
    if (inputsArray && dbFieldsNameArray && searchIdField && itemsDB && nameTipoRecompensa) {
        const idTipoRecompensa = returnIDIntegerByStringID(codigoTipoRecompensa);
        const name = nameTipoRecompensa;
        const itemArraySearched = returnItemDBValueWithRequestedID(searchIdField, idTipoRecompensa, itemsDB);
        
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

