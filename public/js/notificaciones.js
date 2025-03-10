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
});

async function reviewNotification(idNotificacion) {
    if (!idNotificacion) {
        return;
    }

    const url = `${baseUrlMAIN}/systemNotification/deactivateNotification`;

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": csrfTokenMAIN
            },
            body: JSON.stringify({idNotificacion: idNotificacion})
        });

        if (!response.ok) {
            console.error("Error en la respuesta:", response);
            return;
        }

        const data = await response.json(); // success true o false
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}

