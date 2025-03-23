class StorageHelper {
    static clearAll() {
        localStorage.clear();
    }
    
    static printTable() {
        console.table(localStorage);
    }
    /**
     * Guarda un valor en localStorage.
     * @param {string} key - Clave para identificar el ítem almacenado.
     * @param {any} value - Valor que se desea almacenar (se convierte a JSON).
     */
    static save(key, value) {
        if (typeof key !== 'string' || key.trim() === '') {
            throw new Error('La clave debe ser una cadena de texto no vacía.');
        }
        try {
            localStorage.setItem(key, JSON.stringify(value));
            //console.log("sessionStorage filled: ", sessionStorage);
        } catch (error) {
            console.error(`Error al guardar en localStorage con la clave "${key}":`, error);
        }
    }

    /**
     * Obtiene un valor de localStorage por su clave.
     * @param {string} key - Clave para recuperar el ítem.
     * @returns {any|null} - Devuelve el valor convertido desde JSON o null si no existe.
     */
    static load(key) {
        if (typeof key !== 'string' || key.trim() === '') {
            throw new Error('La clave debe ser una cadena de texto no vacía.');
        }
        try {
            const value = localStorage.getItem(key);
            return value ? JSON.parse(value) : null;
        } catch (error) {
            console.error(`Error al cargar de localStorage con la clave "${key}":`, error);
            return null;
        }
    }

    static partialLoad(partialKey) {
        if (typeof partialKey !== 'string' || partialKey.trim() === '') {
            throw new Error('La clave debe ser una cadena de texto no vacía.');
        }
    
        try {
            // Obtener todas las claves almacenadas en localStorage
            const keys = Object.keys(localStorage);
            
            // Buscar la primera clave que contenga el texto parcial
            const matchedKey = keys.find(key => key.startsWith(partialKey));
    
            // Si se encuentra una coincidencia, cargar su valor
            if (matchedKey) {
                const value = localStorage.getItem(matchedKey);
                return value ? JSON.parse(value) : null;
            }
    
            // Si no se encontró ninguna clave coincidente
            return null;
        } catch (error) {
            console.error(`Error al buscar en localStorage con la clave parcial "${partialKey}":`, error);
            return null;
        }
    }

    /**
     * Elimina un valor de localStorage por su clave.
     * @param {string} key - Clave para eliminar el ítem.
     */
    static clear(key) {
        if (typeof key !== 'string' || key.trim() === '') {
            throw new Error('La clave debe ser una cadena de texto no vacía.');
        }
        try {
            localStorage.removeItem(key);
        } catch (error) {
            console.error(`Error al eliminar de localStorage con la clave "${key}":`, error);
        }
    }

    static partialClear(partialKey) {
        if (typeof partialKey !== 'string' || partialKey.trim() === '') {
            throw new Error('La clave debe ser una cadena de texto no vacía, partialKey:' + partialKey);
        }
    
        try {
            // Obtener todas las claves almacenadas en localStorage
            const keys = Object.keys(localStorage);
    
            // Filtrar claves que contengan el texto parcial y eliminarlas
            keys.forEach(key => {
                if (key.includes(partialKey)) {
                    localStorage.removeItem(key);
                }
            });
        } catch (error) {
            console.error(`Error al eliminar en localStorage con la clave parcial "${partialKey}":`, error);
        }
    }

    /**
     * Elimina múltiples valores de localStorage por un conjunto de claves.
     * @param {string[]} keys - Array de claves a eliminar.
     */
    static clearMultiple(keys) {
        if (!Array.isArray(keys)) {
            throw new Error('El argumento debe ser un array de claves.');
        }
        keys.forEach(key => {
            if (typeof key === 'string' && key.trim() !== '') {
                try {
                    localStorage.removeItem(key);
                } catch (error) {
                    console.error(`Error al eliminar de localStorage con la clave "${key}":`, error);
                }
            } else {
                console.warn(`Clave inválida: "${key}". Se espera una cadena de texto no vacía.`);
            }
        });
    }

    /**
     * Guarda los datos de un modal en localStorage con un sufijo "" para modularidad.
     * @param {string} name - Nombre base del modal.
     * @param {any} data - Datos que se desean almacenar.
     */
    static saveModalDataToStorage(name, data) {
        if (typeof name !== 'string' || name.trim() === '') {
            throw new Error('El nombre del modal debe ser una cadena de texto no vacía.');
        }
        StorageHelper.save(`${name}`, data);
    }

    /**
     * Carga los datos de un modal desde localStorage.
     * @param {string} name - Nombre base del modal.
     * @returns {any|null} - Devuelve los datos almacenados o null si no existen.
     */
    static loadModalDataFromStorage(name) {
        if (typeof name !== 'string' || name.trim() === '') {
            throw new Error('El nombre del modal debe ser una cadena de texto no vacía.');
        }
        return StorageHelper.load(`${name}`);
    }

    /**
     * Elimina los datos de un modal de localStorage.
     * @param {string} name - Nombre base del modal.
     */
    static clearModalDataFromStorage(name) {
        if (typeof name !== 'string' || name.trim() === '') {
            throw new Error('El nombre del modal debe ser una cadena de texto no vacía.');
        }
        StorageHelper.clear(`${name}`);
    }
}

// Exponer StorageHelper globalmente
window.StorageHelper = StorageHelper;