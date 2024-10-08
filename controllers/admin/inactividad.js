const USER_API2 = 'services/admin/administrador.php'; // Asegúrate de que esta URL sea correcta
let timeout;

// Función para cerrar sesión
async function cerrarSesion() {

    const DATA = await fetchData(USER_API2, 'logOutInactividad'); // Verifica que `fetchData` funcione
    console.log(DATA); // Muestra la respuesta en la consola

    if (DATA.status) {
        sweetAlert(3, DATA.message, true, 'index.html');
    } else {
        sweetAlert(2, DATA.exception, false);
    }
}

// Reiniciar el temporizador
function reiniciarTemporizador() {
    clearTimeout(timeout);
    // Establecer un nuevo temporizador de 5 minutos (300000 ms)
    timeout = setTimeout(cerrarSesion, 300000); // 5 minutos
}

// Eventos para reiniciar el temporizador
document.addEventListener('DOMContentLoaded', reiniciarTemporizador);
document.addEventListener('mousemove', reiniciarTemporizador);
document.addEventListener('keypress', reiniciarTemporizador);
document.addEventListener('click', reiniciarTemporizador);
