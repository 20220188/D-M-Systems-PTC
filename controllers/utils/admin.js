/* Plantilla para el footer del sitio privado */


// Constante para completar la ruta de la API.
const USER_API = 'services/admin/administrador.php';

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
                    <header class="header">
        
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
        <div><img src="" alt="">
        </div>
        </header>
                    <div class="menu__side" id="menu_side">
    <div class="name__page">
        <i class="fa-solid fa-user-tie"></i>
        <h4>Admin</h4>
    </div>
   
    <div class="options__menu">    
        <a href="admin_modulo_compras.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-star" title="Maestros"></i>
            </div>
        </a>
        <a href="modulo_compras.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-share-nodes" title="Movimientos"></i>
            </div>
        </a>
        <a href="admin_reporte_ventas.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-file" title="Reportes"></i>
            </div>
        </a>
        <a href="admin_usuarios.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-eye" title="Utilidades"></i>
            </div>
        </a>
    </div>
</div>
                `;
            
            }else if (DATA.user_level == 2) {
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



//Ejecutar función en el evento click
document.getElementById("btn_open").addEventListener("click", open_close_menu);

//Declaramos variables
var side_menu = document.getElementById("menu_side");
var btn_open = document.getElementById("btn_open");
var body = document.getElementById("body");

//Evento para mostrar y ocultar menú
    function open_close_menu(){
        body.classList.toggle("body_move");
        side_menu.classList.toggle("menu__side_move");
    }

//Si el ancho de la página es menor a 760px, ocultará el menú al recargar la página

if (window.innerWidth < 760){

    body.classList.add("body_move");
    side_menu.classList.add("menu__side_move");
}

//Haciendo el menú responsive(adaptable)

window.addEventListener("resize", function(){

    if (window.innerWidth > 760){

        body.classList.remove("body_move");
        side_menu.classList.remove("menu__side_move");
    }

    if (window.innerWidth < 760){

        body.classList.add("body_move");
        side_menu.classList.add("menu__side_move");
    }

});


