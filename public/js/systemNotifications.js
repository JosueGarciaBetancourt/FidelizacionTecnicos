const loadingModal = document.getElementById('loadingModal');

document.addEventListener("DOMContentLoaded", function () {
    const notificationToggle = document.getElementById("notification-toggle");
    const notificationPanel = document.getElementById("notification-panel");
    const notificationList = document.getElementById("notification-list");
    const notificationCount = document.getElementById("notification-count");
    const reviewBtn = document.getElementById("review_btn");

    // Alternar la visibilidad del panel de notificaciones
    notificationToggle.addEventListener("click", function (event) {
        event.preventDefault();
        notificationPanel.classList.toggle("open");
    });
    
    // Cerrar panel si se hace clic fuera de él
    document.addEventListener("click", function (event) {
        if (!notificationPanel.contains(event.target) && !notificationToggle.contains(event.target)) {
            notificationPanel.classList.remove("open");
        }
    });

    // Función para actualizar el contador de notificaciones no leídas
    function actualizarContador() {
        const unreadNotifications = document.querySelectorAll(".notification_item.unread").length;
        notificationCount.textContent = unreadNotifications;
        if (unreadNotifications === 0) {
            notificationCount.style.display = "none"; // Oculta el contador si no hay notificaciones
        } else {
            notificationCount.style.display = "block";
        }
    }

    actualizarContador(); // Inicializar contador al cargar la página

    const filterData = sessionStorage.getItem("datatableNotificationFilter");

    if (filterData) {
        const { tblToFilter, item } = JSON.parse(filterData);

        // Asegurar que la tabla esté inicializada antes de filtrar
        setTimeout(() => {
            if (window[tblToFilter]) {
                window[tblToFilter].search(item).draw();
            } else {
                console.error(`Tabla ${tblToFilter} no encontrada.`);
            }
        }, 1000);

        // Eliminar el filtro de sessionStorage después de aplicarlo
        sessionStorage.removeItem("datatableNotificationFilter");
    }
});

async function reviewNotification(idNotificacion, routeToReview, tblToFilter, item) {
    if (!idNotificacion || !routeToReview || !tblToFilter || !item) {
        console.error("Parámetro faltante en reviewNotification");
        return;
    }

    loadingModal.classList.add('show');

    const url = `${baseUrlMAIN}/systemNotification/deactivateNotification`;

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": csrfTokenMAIN
            },
            body: JSON.stringify({ idNotificacion, routeToReview })
        });

        if (!response.ok) {
            console.error("Error en la respuesta:", response);
            return;
        }

        const data = await response.json();

        if (data.status === "success") {
            // Guardar en sessionStorage antes de redirigir
            sessionStorage.setItem("datatableNotificationFilter", JSON.stringify({ tblToFilter, item }));
            window.location.href = data.redirect_url;
        }
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}
