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

    <div class="name__page">
    <i class="fa-solid fa-user-tie"></i>
        <h4>Caja</h4>
    </div>

    <div class="options__menu">	

        <a href="caja.html">
        <div class="maestros">
            <div class="option">
            <i class="fa-solid fa-cart-shopping" title="Caja"></i>
                <h4>Caja</h4>
            </div>
        </div>
        </a>

        <a href="consultas.html" >
            <div class="option">
            <i class="fa-solid fa-align-left" title="Consultas"></i>
                <h4>Consultas</h4>
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