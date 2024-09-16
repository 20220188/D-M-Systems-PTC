//const USER_API = 'services/admin/admin_usuarios.php';

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Llamada a la funciones que generan los gráficos en la página web.
    //graficoBarrasUltimosUsuariosCreados();
});


//const graficoBarrasUltimosUsuariosCreados = async () => {
    // Petición para obtener los datos del gráfico.
    //const DATA = await fetchData(USUARIO_API, 'readAll');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    //if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        //let usuarios = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        //DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            //usuarios.push(row.usuario);
       // });
        // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
        //barGraph('chart1', productos, 'Usuarios', 'Usuarios más recientes');
   // } else {
        //document.getElementById('chart1').remove();
        //console.log(DATA.error);
   // }
//}