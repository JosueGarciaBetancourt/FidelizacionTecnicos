function closeDetalleSolicitudCanje(modalId) {
    StorageHelper.clearModalDataFromStorage('currentSolicitudCanje');
    StorageHelper.clearModalDataFromStorage('currentSolicitudCanjeDetails');
    justCloseModal(modalId);
}