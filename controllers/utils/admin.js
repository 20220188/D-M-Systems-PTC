// Constante para completar la ruta de la API.
const USER_API = 'services/admin/administrador.php';

// Constante para establecer el elemento del título principal.
const MAIN_TITLE = document.getElementById('mainTitle');
if (MAIN_TITLE) {
    MAIN_TITLE.classList.add('text-center', 'py-3');
}

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
                if (DATA.user_level == 1) {
                    navOptions = `
                        <header class="header">
                            <div class="icon__menu">
                                <i class="fas fa-bars" id="btn_open"></i>
                            </div>
                            <div><img src="" alt=""></div>
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
                                <a href="profile.html" class="menu-option">
                                    <div class="option">
                                        <i class="fa-solid fa-user" title="Editar perfil"></i>
                                    </div>
                                </a>
                                <a href="admin_usuarios.html" class="menu-option" onclick="logOut()">
                                    <div class="option">
                                        <i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    `;
                } else if (DATA.user_level == 2) {
                    navOptions = `
                        <header class="header">
                            <div class="icon__menu">
                                <i class="fas fa-bars" id="btn_open"></i>
                            </div>
                            <div><img src="" alt=""></div>
                        </header>
                        <div class="menu__side" id="menu_side">
                            <div class="name__page">
                                <i class="fa-solid fa-user-tie"></i>
                                <h4>Inventario</h4>
                            </div>
                            <div class="options__menu">    
                                <a href="admin_modulo_compras.html" class="menu-option">
                                    <div class="option">
                                        <i class="fa-solid fa-star" title="Maestros"></i>
                                    </div>
                                </a>
                                <a href="admin_reporte_ventas.html" class="menu-option">
                                    <div class="option">
                                        <i class="fa-solid fa-file" title="Reportes"></i>
                                    </div>
                                </a>
                                <a href="profile.html" class="menu-option">
                                    <div class="option">
                                        <i class="fa-solid fa-user" title="Editar perfil"></i>
                                    </div>
                                </a>
                                <a href="admin_usuarios.html" class="menu-option" onclick="logOut()">
                                    <div class="option">
                                        <i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    `;
                } else if (DATA.user_level == 3) {
                    navOptions = `
                        <header class="header">
                            <div class="icon__menu">
                                <i class="fas fa-bars" id="btn_open"></i>
                            </div>
                            <div><img src="" alt=""></div>
                        </header>
                        <div class="menu__side" id="menu_side">
                            <div class="name__page">
                                <i class="fa-solid fa-user-tie"></i>
                                <h4>Inventario</h4>
                            </div>
                            <div class="options__menu">    
                                <a href="admin_modulo_compras.html" class="menu-option">
                                    <div class="option">
                                        <i class="fa-solid fa-star" title="Maestros"></i>
                                    </div>
                                </a>
                                <a href="admin_reporte_ventas.html" class="menu-option">
                                    <div class="option">
                                        <i class="fa-solid fa-file" title="Reportes"></i>
                                    </div>
                                </a>
                                <a href="profile.html" class="menu-option">
                                    <div class="option">
                                        <i class="fa-solid fa-user" title="Editar perfil"></i>
                                    </div>
                                </a>
                                <a href="admin_usuarios.html" class="menu-option" onclick="logOut()">
                                    <div class="option">
                                        <i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
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
                asideElement.innerHTML = asideHTML;
                document.body.appendChild(asideElement);
            } else {
                location.href = 'index.html';
            }
        }
    } catch (error) {
        console.error('Error loading template:', error);
        location.href = 'index.html';
    }
}

// Ejecutar
