/* Plantilla para el footer del sitio privado */


// Constante para completar la ruta de la API.
const USER_API = 'services/admin/administrador.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '75px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');
// Constante para establecer el elemento del título principal.
const MAIN_TITLE = document.getElementById('mainTitle');
MAIN_TITLE.classList.add('text-center', 'py-3');

/*  Función asíncrona para cargar el encabezado y pie del documento.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const loadTemplate = async () => {
    // Petición para obtener el nombre del usuario que ha iniciado sesión.
    const DATA = await fetchData(USER_API, 'getUser');
    // Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
    if (DATA.session) {
        // Se comprueba si existe un alias definido para el usuario, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Generar el contenido del header según el nivel de usuario
            let navOptions = '';
            if (DATA.user_level == 1) {
                navOptions = `
                <div class="menu__side" id="menu_side">

    <a href="admin.html"><div class="name__page">
    <i class="fa-solid fa-user-tie"></i>
        <h4>Admin</h4>
    </div></a>

    <div class="options__menu">	

        <a href="admin_maestro_clientes.html">
        <div class="maestros">
            <div class="option">
            <i class="fa-solid fa-star" title="Maestros"></i>
                <h4>Maestros</h4>
            </div>
        </div>
        </a>

        <a href="admin_modulo_compras.html">
            <div class="option">
            <i class="fa-solid fa-share-nodes" title="Movimientos"></i>
                <h4>Movimientos</h4>
            </div>
        </a>
        
        <a href="admin_reporte_ventas.html">
            <div class="option">
            <i class="fa-solid fa-file" title="reportes"></i>
                <h4>Reportes</h4>
            </div>
        </a>

        <a href="admin_usuarios.html">
            <div class="option">
            <i class="fa-solid fa-eye" title="Utilidades"></i>
                <h4>Utilidades</h4>
            </div>
        </a>

    </div>

</div>

                `;
            } else if (DATA.user_level == 2) {
                navOptions = `
                    <li class="nav-item px-2 py-2 dropdown">
                        <a class="nav-link text-uppercase text-dark dropdown-toggle" href="#" id="productosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Productos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productosDropdown">
                            <li><a class="dropdown-item" href="../admin/producto.html">Productos</a></li>
                            <li><a class="dropdown-item" href="../admin/categoria.html">Categorías</a></li>
                            <li><a class="dropdown-item" href="../admin/genero.html">Género</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../admin/colores.html">Colores</a></li>
                            <li><a class="dropdown-item" href="../admin/marcas.html">Marcas</a></li>
                            <li><a class="dropdown-item" href="../admin/tallas.html">Tallas</a></li>
                        </ul>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="../admin/descuento.html">Descuentos</a>
                    </li>
                `;
            } else if (DATA.user_level == 3) {
                navOptions = `
                   <li class="nav-item px-2 py-2 dropdown">
                        <a class="nav-link text-uppercase text-dark dropdown-toggle" href="#" id="productosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Productos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productosDropdown">
                            <li><a class="dropdown-item" href="../admin/producto.html">Productos</a></li>
                            <li><a class="dropdown-item" href="../admin/categoria.html">Categorías</a></li>
                            <li><a class="dropdown-item" href="../admin/genero.html">Género</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../admin/colores.html">Colores</a></li>
                            <li><a class="dropdown-item" href="../admin/marcas.html">Marcas</a></li>
                            <li><a class="dropdown-item" href="../admin/tallas.html">Tallas</a></li>
                        </ul>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="../admin/descuento.html">Descuentos</a>
                    </li>
                `;
            }
            // Se agrega el encabezado de la página web antes del contenido principal.
            MAIN.insertAdjacentHTML('beforebegin', `
                <header>
                    <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
                        <div class="container">
                            <a class="navbar-brand d-flex justify-content-between align-items-center order-lg-0" href="../admin/dashboard.html">
                                <img src="../../resources/img/LogoComods.png" class="logo img-fluid" alt="site icon">
                            </a>
                        </div>
                        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse order-lg-1" id="navMenu">
                            <ul class="navbar-nav mx-auto text-center">
                                ${navOptions}
                                <li class="nav-item px-2 py-2 dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Cuenta: <b>${DATA.username}</b></a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="perfil.html">Editar perfil</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="logOut()">Cerrar sesión</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </header>
            `);
            // Se agrega el footer de la página web después del contenido principal.
            const asideElement = document.createElement('aside');
            asideElement.classList.add('aside'); // Add class for styling
            asideElement.innerHTML = asideHTML;
            document.body.appendChild(asideElement);
        } else {
            sweetAlert(3, DATA.error, false, 'index.html');
        }
    } else {
        // Se comprueba si la página web es la principal, de lo contrario se direcciona a iniciar sesión.
        if (location.pathname.endsWith('index.html')) {
            // Se agrega el footer de la página web después del contenido principal.
            const asideElement = document.createElement('aside');
            asideElement.classList.add('aside'); // Add class for styling
            asideElement.innerHTML = asideHTML;
            document.body.appendChild(asideElement);
        } else {
            location.href = 'index.html';
        }
    }
}

// Llamar a la función para cargar la plantilla.
loadTemplate();

// Ejecutar función en el evento click para abrir y cerrar el menú lateral.
document.getElementById("btn_open").addEventListener("click", open_close_menu);

// Declarar variables para el menú lateral y el botón de abrir.
var side_menu = document.getElementById("menu_side");
var btn_open = document.getElementById("btn_open");
var body = document.body;

// Función para mostrar y ocultar el menú lateral.
function open_close_menu() {
    body.classList.toggle("body_move");
    side_menu.classList.toggle("menu__side_move");
}

// Si el ancho de la página es menor a 760px, ocultará el menú al recargar la página.
if (window.innerWidth < 760) {
    body.classList.add("body_move");
    side_menu.classList.add("menu__side_move");
}

// Haciendo el menú responsive (adaptable) al cambiar el tamaño de la ventana.
window.addEventListener("resize", function () {
    if (window.innerWidth > 760) {
        body.classList.remove("body_move");
        side_menu.classList.remove("menu__side_move");
    }
    if (window.innerWidth < 760) {
        body.classList.add("body_move");
        side_menu.classList.add("menu__side_move");
    }
});
