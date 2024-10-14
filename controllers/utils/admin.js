// Constante para completar la ruta de la API.
const USER_API = 'services/admin/administrador.php';

// Constante para establecer el elemento del título principal.
const MAIN_TITLE = document.getElementById('mainTitle');
if (MAIN_TITLE) {
    MAIN_TITLE.classList.add('text-center', 'py-3');
}

const asideContent = `
    <footer>
        <p>&copy; 2024 D-M SYSTEM. Todos los derechos reservados.</p>
    </footer>
`;

/* Función asíncrona para cargar el encabezado y pie del documento.
 * Parámetros: ninguno.
 * Retorno: ninguno.
 */
const loadTemplate = async () => {
    try {
        // Petición para obtener el nombre del usuario que ha iniciado sesión.
        const DATA = await fetchData(USER_API, 'getUser');

        // Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
        if (DATA.session) {
            let navOptions = '';
            if (DATA.status) {
                // Generar el contenido del header según el nivel de usuario
                if (DATA.user_level == 1) { //NavBar para el nivel de administrador
                    navOptions = `
<header class="header">
        
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
        <div><img src="" alt=""></div>
</header>
<div class="menu__side" id="menu_side">

    <a href="dashboard.html">
        <div class="name__page">
            <i class="fa-solid fa-user-tie"></i>
            <h4>Admin</h4>
        </div>
    </a>

    <div class="options__menu">    
        <a href="admin_maestro_productos.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-star" title="Maestros"></i>
                <h4>Maestros</h4>
            </div>
        </a>
        <a href="admin_ingresos.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-share-nodes" title="Movimientos"></i>
                <h4>Movimientos</h4>
            </div>
        </a>
        <a href="admin_usuarios.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-eye" title="Utilidades"></i>
                <h4>Utilidades</h4>
            </div>
        </a>
        <a href="profile.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-user" title="Editar perfil"></i>
                <h4>Editar Perfil</h4>
            </div>
        </a>
        <a href="#" class="menu-option" onclick="logOut()">
            <div class="option">
                <i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
                <h4>Cerrar sesión</h4>
            </div>
        </a>
    </div>

</div>
`;
                } else if (DATA.user_level == 2) {  //NavBar para el nivel de inventario
                    navOptions = `
<header class="header">
        
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
        <div><img src="" alt=""></div>
</header>
<div class="menu__side" id="menu_side">

    <a href="dashboard.html">
        <div class="name__page">
            <i class="fa-solid fa-user-tie"></i>
            <h4>Inventario</h4>
        </div>
    </a>

    <div class="options__menu">  
    <a href="admin_maestro_productos.html">
        <div class="maestros">
            <div class="option">
            <i class="fa-solid fa-star" title="Maestros"></i>
                <h4>Maestros</h4>
            </div>
        </div>
        </a>  
        <a href="admin_ingresos.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-share-nodes" title="Movimientos"></i>
                <h4>Movimientos</h4>
            </div>
        </a>
        <a href="admin_reporte_ventas.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-file" title="Reportes"></i>
                <h4>Reportes</h4>
            </div>
        </a>
        <a href="profile.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-user" title="Editar perfil"></i>
                <h4>Editar Perfil</h4>
            </div>
        </a>
        <a href="#" class="menu-option" onclick="logOut()">
            <div class="option">
                <i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
                <h4>Cerrar sesión</h4>
            </div>
        </a>
    </div>

</div>
`;
                } else if (DATA.user_level == 3) { //NavBar para el nivel de ventas
                    navOptions = `
<header class="header">
        
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
        <div><img src="" alt=""></div>
</header>

<div class="menu__side" id="menu_side">

    <a href="dashboard.html">
        <div class="name__page">
            <i class="fa-solid fa-user-tie"></i>
            <h4>Ventas</h4>
        </div>
    </a>

    <div class="options__menu">    

        <a href="ventas_modulo_ventas.html" class="menu-option">
            <div class="option">
            <i class="fa-solid fa-cart-shopping" title="Ventas"></i>
                <h5>Ventas</h5>
            </div>
        </a>

        <a href="profile.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-user" title="Editar perfil"></i>
                <h4>Editar Perfil</h4>
            </div>
        </a>
        
        <a href="#" class="menu-option" onclick="logOut()">
            <div class="option">
                <i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
                <h4>Cerrar sesión</h4>
            </div>
        </a>
    </div>

</div>
`;
                } else if (DATA.user_level == 4) {   //NavBar para el nivel de caja
                    navOptions = `
<header class="header">

<div class="icon__menu">
<i class="fas fa-bars" id="btn_open"></i>
</div>
<div><img src="" alt=""></div>
</header>
<div class="menu__side" id="menu_side">

<a href="dashboard.html">
<div class="name__page">
<i class="fa-solid fa-user-tie"></i>
<h4>Inventario</h4>
</div>
</a>

<div class="options__menu">    
<a href="admin_ingresos.html" class="menu-option">
<div class="option">
<i class="fa-solid fa-share-nodes" title="Movimientos"></i>
<h4>Movimientos</h4>
</div>
</a>
<a href="admin_reporte_ventas.html" class="menu-option">
<div class="option">
<i class="fa-solid fa-file" title="Reportes"></i>
<h4>Reportes</h4>
</div>
</a>
<a href="profile.html" class="menu-option">
<div class="option">
<i class="fa-solid fa-user" title="Editar perfil"></i>
<h4>Editar Perfil</h4>
</div>
</a>
<a href="#" class="menu-option" onclick="logOut()">
<div class="option">
<i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
<h4>Cerrar sesión</h4>
</div>
</a>
</div>

</div>
`;

                }

                // Se agrega el contenido del menú.
                const menuElement = document.createElement('div');
                menuElement.classList.add('menu');
                menuElement.innerHTML = navOptions;
                document.body.appendChild(menuElement);

            } else {
                sweetAlert(3, DATA.error, false, 'index.html');
            }
        } else {
            if (location.pathname.endsWith('index.html')) {
                // Agregar el footer
                const asideElement = document.createElement('aside');
                asideElement.classList.add('aside'); // Add class for styling
                asideElement.innerHTML = asideContent; // Cambiado asideHTML a asideContent
                document.body.appendChild(asideElement);
            } else {
                location.href = 'index.html';
            }
        }
    } catch (error) {
        console.error('Error loading template:', error);
        location.href = 'index.html';
    }


    //Ejecutar función en el evento click
    document.getElementById("btn_open").addEventListener("click", open_close_menu);

    // Asignar el botón de abrir/cerrar menú y el cuerpo del documento.
    const btnOpen = document.getElementById("btn_open");
    const sideMenu = document.getElementById("menu_side");
    const body = document.getElementById("body");

    // Verificar si btnOpen, sideMenu y body existen antes de agregar el evento.
    if (btnOpen && sideMenu && body) {
        // Evento para mostrar y ocultar menú
        function open_close_menu() {
            body.classList.toggle("body_move");
            sideMenu.classList.toggle("menu__side_move");
        }

        // Asignar el evento click al botón de abrir/cerrar menú
        btnOpen.addEventListener("click", open_close_menu);

        // Si el ancho de la página es menor a 760px, ocultará el menú al recargar la página
        if (window.innerWidth < 760) {
            body.classList.add("body_move");
            sideMenu.classList.add("menu__side_move");
        }

        // Haciendo el menú responsive (adaptable)
        window.addEventListener("resize", function () {
            if (window.innerWidth > 760) {
                body.classList.remove("body_move");
                sideMenu.classList.remove("menu__side_move");
            } else {
                body.classList.add("body_move");
                sideMenu.classList.add("menu__side_move");
            }
        });
    }

};
