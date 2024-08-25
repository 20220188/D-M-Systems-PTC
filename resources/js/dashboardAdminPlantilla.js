//Plantilla para el footer del sitio privado
const asideHTML = `

<header class="header">
        
        <div class="icon__menu">
            <i class="fas fa-bars" id="btn_open"></i>
        </div>
        <div><img src="" alt="">
        </div>
        </header>
<div class="menu__side" id="menu_side">

    <a href="dashboard.html"><div class="name__page">
    <i class="fa-solid fa-user-tie"></i>
        <h4>Admin</h4>
    </div></a>

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
        <a href="admin_reporte_ventas.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-file" title="Reportes"></i>
                <h4>Reportes</h4>
            </div>
        </a>
        <a href="admin_usuarios.html" class="menu-option">
            <div class="option">
                <i class="fa-solid fa-eye" title="Utilidades"></i>
                <h4>Utilidades</h4>
            </div>
        </a>
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
</aside>

  
`;



// Create a new element
const asideElement = document.createElement('aside');
asideElement.classList.add('aside'); // Add class for styling

// Set the inner HTML of the element
asideElement.innerHTML = asideHTML;

// Append the element to the desired location in your HTML
document.body.appendChild(asideElement);




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