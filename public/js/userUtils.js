let userLoggedMAIN;
let adminEmailMAIN;
let emailDomainMAIN;
let maxdaysCanjeMAIN;
let diasAgotarVentaIntermediadaNotificacionMAIN;

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
    //console.log("userLoggedMAIN: ");
    //consoleLogJSONItems(userLoggedMAIN);
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
    //console.log("adminEmailMAIN: " + adminEmailMAIN);
}


async function getEmailDomain() {
    const url = `${baseUrlMAIN}/dashboard-getEmailDomain`;

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

        return adminData.emailDomain;
    } catch (error) {
        console.log("Error al obtener el dominio de correo:", error.message);
    }
}

async function initEmailDomain() {
    emailDomainMAIN = await getEmailDomain();
    //console.log("emailDomainMAIN: " + emailDomainMAIN);
}


async function getMaxdaysCanje() {
    const url = `${baseUrlMAIN}/dashboard-getMaxdaysCanje`;

    try {
        const response = await fetch(url);

        // Verificar si la respuesta HTTP es un error (por ejemplo, 401)
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`${errorData.message || 'Error desconocido'}`);
        }

        // Convertir la respuesta a JSON
        const maxdaysCanje = await response.json();

        // Validar la estructura esperada
        if (!maxdaysCanje.success) {
            throw new Error(maxdaysCanje.message || 'No autenticado');
        }

        return maxdaysCanje.maxdaysCanje;
    } catch (error) {
        console.log("Error al obtener maxdaysCanje:", error.message);
    }
}

async function initMaxdaysCanje() {
    maxdaysCanjeMAIN = await getMaxdaysCanje();
    //console.log("maxdaysCanjeMAIN: " + maxdaysCanjeMAIN);
}


async function getDiasAgotarVentaIntermediadaNotificacion() {
    const url = `${baseUrlMAIN}/dashboard-getdiasAgotarVentaIntermediadaNotificacion`;

    try {
        const response = await fetch(url);

        // Verificar si la respuesta HTTP es un error (por ejemplo, 401)
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`${errorData.message || 'Error desconocido'}`);
        }

        // Convertir la respuesta a JSON
        const diasAgotarVentaIntermediadaNotificacion = await response.json();

        // Validar la estructura esperada
        if (!diasAgotarVentaIntermediadaNotificacion.success) {
            throw new Error(diasAgotarVentaIntermediadaNotificacion.message || 'No autenticado');
        }

        return diasAgotarVentaIntermediadaNotificacion.diasAgotarVentaIntermediadaNotificacion;
    } catch (error) {
        console.log("Error al obtener diasAgotarVentaIntermediadaNotificacion:", error.message);
    }
}

async function initDiasAgotarVentaIntermediada() {
    diasAgotarVentaIntermediadaNotificacionMAIN = await getDiasAgotarVentaIntermediadaNotificacion();
    //console.log("diasAgotarVentaIntermediadaNotificacionMAIN: " + diasAgotarVentaIntermediadaNotificacionMAIN);
}


initMaxdaysCanje();
initUserLogged();
initAdminEmail();
initEmailDomain();
initEmailDomain();
initDiasAgotarVentaIntermediada();