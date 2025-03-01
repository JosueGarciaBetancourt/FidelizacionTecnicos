let userLoggedMAIN;
let adminEmailMAIN;

async function getUserLogged() {
    const url = `${baseUrlMAIN}/dashboard-getLoggedUser`;

    try {
        const response = await fetch(url);

        // Verificar si la respuesta HTTP es un error (por ejemplo, 401)
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`${errorData.message || 'Error desconocido'}`);
        }

        // Convertir la respuesta a JSON
        const userData = await response.json();

        // Validar la estructura esperada
        if (!userData.success) {
            throw new Error(userData.message || 'No autenticado');
        }

        return userData.user;
    } catch (error) {
        console.log("Error al obtener el usuario:", error.message);
    }
}

async function initUserLogged() {
   userLoggedMAIN = await getUserLogged();
}

async function getAdminEmail() {
    const url = `${baseUrlMAIN}/dashboard-getAdminEmail`;

    try {
        const response = await fetch(url);

        // Verificar si la respuesta HTTP es un error (por ejemplo, 401)
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`${errorData.message || 'Error desconocido'}`);
        }

        // Convertir la respuesta a JSON
        const adminData = await response.json();

        // Validar la estructura esperada
        if (!adminData.success) {
            throw new Error(userData.message || 'No autenticado');
        }

        return adminData.email;
    } catch (error) {
        console.log("Error al obtener el usuario:", error.message);
    }
}

async function initAdminEmail() {
    adminEmailMAIN = await getAdminEmail();
}

initUserLogged();
initAdminEmail();


