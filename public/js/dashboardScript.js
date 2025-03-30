let canjesMenuClosed = true;

document.addEventListener('DOMContentLoaded', function() {
    const headerSection = document.querySelector('.header');
    const mainSection = document.querySelector('.main');
    const menuToggleButton = document.getElementById('menu_toggle_button');
    const aside = document.querySelector('aside');
    const h5Elements = document.querySelectorAll('.sidebar a h5'); // Selecciona todos los h5 
    const sidebar = document.querySelectorAll('.sidebar'); 
    const canjesLink = document.getElementById('canjesLink');
    const canjesMenu = document.getElementById('canjesMenu');
    const canjesArrowDownSpan = document.getElementById('canjesArrowDownSpan');

    // Función para alternar la visibilidad del menú Canjes
    canjesLink.addEventListener('click', (event) => {
        event.preventDefault(); // Evita que el enlace recargue la página
        canjesMenu.classList.toggle('hidden');
        canjesArrowDownSpan.classList.toggle('up');
        canjesMenuClosed = !canjesMenuClosed;
        localStorage.setItem('canjesMenu', canjesMenuClosed ? 'hidden' : '')
        localStorage.setItem('canjesMenu', canjesMenuClosed ? 'hidden' : '')
    });

    // Función para obtener el estado guardado del sidebar
    function getSidebarState() {
        return localStorage.getItem('sidebarState') === 'closed';
    }

    function getMenuButtonState() {
        return localStorage.getItem('menuToggleButton') === 'closed';
    }

    function getCanjesMenuState() {
        return localStorage.getItem('canjesMenu') === 'hidden';
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
        const isCanjesMenuHidden = getCanjesMenuState();
        aside.classList.toggle('closed', isClosed);
        menuToggleButton.classList.toggle('closed', isBtnClosed);
        headerSection.classList.toggle('asideClosed', isClosed);
        mainSection.classList.toggle('asideClosed', isClosed);

        if (!isClosed) {
            if (isCanjesMenuHidden) {
                canjesMenu.classList.add('hidden');
            } else {
                canjesMenu.classList.remove('hidden');
            }
        } else { //Aside cerrado
            canjesMenu.classList.add('hidden');
        }
   
        // Oculta los h5, span arrow down y #canjesMenu si el sidebar está cerrado al inicio
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
    window.toggleOptionsUser = function(idOptions, idSpan) {
        var options = document.getElementById(idOptions);
        var span = document.getElementById(idSpan);
        
        options.style.display = (options.style.display === 'block') ? 'none' : 'block';
        
        if (span) {
            span.textContent = span.textContent === "keyboard_arrow_down" ? "keyboard_arrow_up" : "keyboard_arrow_down";
        }
    }

    const routesData = document.querySelector('.dashboard-container').getAttribute('data-routes');
    const routes = JSON.parse(routesData);  // Convierte la cadena JSON a un objeto

    window.linkOption = function(value) {
        try {
            const route = routes[value];
            if (route) {
                if (value === 'logout') {
                    // No redirigir para logout, sino manejar el formulario
                    document.getElementById('logoutForm').submit();
                } else {
                    window.location.href = route;  // Redirige a la ruta
                }
            } else {
                console.error('Ruta no encontrada para la opción:', value);
            }
        } catch (error) {
            console.log("Error: ", error);
        }
    };

    // Manejar el botón de menú para abrir/cerrar el sidebar
    menuToggleButton.addEventListener('click', () => {
        const isClosed = aside.classList.toggle('closed');
        menuToggleButton.classList.toggle('closed', isClosed);
        mainSection.classList.toggle('asideClosed');
        headerSection.classList.toggle('asideClosed');
        canjesArrowDownSpan.classList.toggle('hidden', isClosed);
        const isCanjesMenuHidden = getCanjesMenuState();

        if (!isClosed) {
            if (isCanjesMenuHidden) {
                canjesMenu.classList.add('hidden');
            } else {
                canjesMenu.classList.remove('hidden');
            }
        } else { //Aside cerrado
            canjesMenu.classList.add('hidden');
        }

        // Guarda el estado actual de los elementos en localStorage
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
        mainSection.classList.add('asideHovered')
        headerSection.classList.add('asideHovered')
        h5Elements.forEach(function(h5) {
            h5.classList.remove('hidden');
        });
    }

    // Función para remover la clase 'hovered' del aside
    function handleMouseLeave() {
        aside.classList.remove('hovered');
        mainSection.classList.remove('asideHovered')
        headerSection.classList.remove('asideHovered')
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

    //console.log(items);
    return items;
}

function closeUserList() {
    userList.style.opacity = 0;
}