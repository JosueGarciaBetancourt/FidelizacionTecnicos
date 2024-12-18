class StorageHelper {
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