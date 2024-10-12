document.addEventListener('DOMContentLoaded', function() {
    const dashboardContainer = document.querySelector('.dashboard-container');
    const routes = JSON.parse(dashboardContainer.getAttribute('data-routes'));
    const menuToggleButton = document.getElementById('menu_toggle_button');
    const aside = document.querySelector('aside');
    const linksSidebar = document.querySelectorAll('aside .sidebar a'); // Selecciona todos los enlaces 
                                                                        // dentro del sidebar 
    const h5Elements = document.querySelectorAll('.sidebar a h5'); // Selecciona todos los h5 
                                                                   // dentro de los enlaces del sidebar
    const sidebar = document.querySelectorAll('.sidebar'); 

    // Función para obtener el estado guardado del sidebar
    function getSidebarState() {
        return localStorage.getItem('sidebarState') === 'closed';
    }

    function getMenuButtonState() {
        return localStorage.getItem('menuToggleButton') === 'closed';
    }

    // Función para guardar el estado del sidebar
    function saveSidebarState(state) {
        localStorage.setItem('sidebarState', state ? 'closed' : 'open');
        localStorage.setItem('menuToggleButton', state ? 'closed' : 'open');
    }

    // Inicializa el estado del sidebar según lo guardado en localStorage
    function initializeSidebarState() {
        const isClosed = getSidebarState();
        const isBtnClosed = getMenuButtonState();
        aside.classList.toggle('closed', isClosed);
        menuToggleButton.classList.toggle('closed', isBtnClosed);

        // Oculta los h5 si el sidebar está cerrado al inicio
        if (isClosed) {
            h5Elements.forEach(function(h5) {
                h5.classList.add('hidden');
            });
        }
    }

    // Carga el estado del sidebar al cargar la página
    initializeSidebarState();

    // Manejar el enlace de logout
    document.getElementById('logoutLink').addEventListener('click', function(event) {
        event.preventDefault(); // Prevenir la acción predeterminada del enlace
        document.getElementById('logoutForm').submit(); // Enviar el formulario
    });

    // Manejar la lista de opciones desplegable de Admin
    // Función para cerrar la lista desplegable si se hace clic fuera de ella
    document.addEventListener('click', function(event) {
        var userList = document.getElementById('userList');
        var idUserDivList = document.getElementById('idUserDivList');
        var isClickInsideOptions = userList.contains(event.target);
        var isClickInsideSelect = idUserDivList.contains(event.target);
    
        if (!isClickInsideOptions && !isClickInsideSelect) {
            userList.style.display = 'none';
        }
    });

    // Mostrar opciones de la lista con el id correspondiente
    window.toggleOptionsUser = function(id) {
        var options = document.getElementById(id);
        options.style.display = (options.style.display === 'block') ? 'none' : 'block';
    }

    // Colocar el valor de la opción en el input
    /*window.selectOptionDashboard = function(value) {
        var input = document.getElementById('idUserDivList').getElementsByTagName('input')[0];
        input.value = value;
        toggleOptionsUser('userList');
    }*/

    // Ir al enlace de la opción de la lista
    window.linkOption = function(value) {
        const route = routes[value];

        if (route) {
            window.location.href = route;
        } else {
            console.error('Ruta no encontrada para la opción:', value);
        }
    }

    // Manejar el botón de menú para abrir/cerrar el sidebar
    menuToggleButton.addEventListener('click', () => {
        const isClosed = aside.classList.toggle('closed');
        menuToggleButton.classList.toggle('closed', isClosed);
        
        // Guarda el estado actual del sidebar y del botón 'menu_toggle_button' en localStorage
        saveSidebarState(isClosed);
        
        // Toggle para ocultar los h5 cuando se cierra el aside
        h5Elements.forEach(function(h5) {
            h5.classList.toggle('hidden', isClosed); // Agrega o quita la clase 'hidden'
        });
    });

    /*Manejar el pasar el mouse por encima de un enlace del sidebar*/
    // Añadir los event listeners a cada enlace
    sidebar.forEach(sb => {
        sb.addEventListener('mouseover', handleMouseEnter);
        sb.addEventListener('mouseout', handleMouseLeave);
    });
    // Función para agregar la clase 'hovered' al aside
    function handleMouseEnter() {
        aside.classList.add('hovered');
        h5Elements.forEach(function(h5) {
            h5.classList.remove('hidden');
        });
    }

    // Función para remover la clase 'hovered' del aside
    function handleMouseLeave() {
        aside.classList.remove('hovered');
        initializeSidebarState(); // Reestablecer el sidebar al estado correcto
    }
});

function getAllLiText(idOptions) {
    // Obtener el elemento UL que contiene todas las opciones
    const ul = document.getElementById(idOptions);
    
    // Obtener todos los elementos LI dentro de la UL
    const liElements = ul.getElementsByTagName('li');
    // Extraer el texto de cada LI y almacenarlo en un array
    let items = [];
    for (let li of liElements) {
        items.push(li.textContent.trim());
    }

    console.log(items);
    return items;
}


