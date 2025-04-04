// Obtener los datos de rutas desde el atributo 'data-routes'
const routesData = document.querySelector('.btnDetailOption-container').getAttribute('data-routes');
const routes = JSON.parse(routesData);

document.querySelectorAll('.btn-DetailOption').forEach(button => {
    button.addEventListener('click', () => {
        const size = button.getAttribute('data-size');
        const codigoCanjeDetalleHistorial = document.getElementById('codigoCanjeModalDetalleHistorialCanje').textContent;
        if (size && codigoCanjeDetalleHistorial) {
            linkOptionPDF(size, codigoCanjeDetalleHistorial);
        } else {
            console.log("Faltan parámetros para generar el PDF");
        }
    });
});

window.linkOptionPDF = function(size, idCanje) {
    try {
        const route = routes['pdf'];  

        if (route && size && idCanje) {
            const urlWithParams = route.replace(':size', size).replace(':idCanje', idCanje);
            window.open(urlWithParams, '_blank');
            // console.log('window.location.origin', window.location.origin);
            // console.log('urlWithParams: ', urlWithParams);
            // console.log(`Abriendo PDF con tamaño ${size} y ID ${idCanje} en nueva pestaña`);
        }
    } catch (error) {
        console.error("Error: ", error);
    }
};

function closeDetalleHistorialCanje(modalId) {
    StorageHelper.clearModalDataFromStorage('currentCanje');
    StorageHelper.clearModalDataFromStorage('currentCanjeDetails');
    justCloseModal(modalId);
}