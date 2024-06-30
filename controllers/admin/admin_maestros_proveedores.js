document.addEventListener('DOMContentLoaded', () => {
    loadProveedores();

    document.getElementById('btnAgregar').addEventListener('click', () => {
        const proveedor = {
            nombre_proveedor: document.getElementById('nombreProveedor').value,
            pais_proveedor: document.getElementById('paisProveedor').value,
            giro_negocio_proveedor: document.getElementById('giroNegocioProveedor').value,
            dui_proveedor: document.getElementById('duiProveedor').value,
            nombre_comercial_proveedor: document.getElementById('nombreComercialProveedor').value,
            fecha_proveedor: document.getElementById('fechaProveedor').value,
            nit_proveedor: document.getElementById('nitProveedor').value,
            telefono_proveedor: document.getElementById('telefonoProveedor').value,
            contacto_proveedor: document.getElementById('contactoProveedor').value,
            direccion_proveedor: document.getElementById('direccionProveedor').value,
            departamento_proveedor: document.getElementById('departamentoProveedor').value,
            municipio_proveedor: document.getElementById('municipioProveedor').value
        };

        fetch('services/admin_maestros_proveedores.php?action=createRow', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(proveedor)
        })
        .then(response => response.json())
        .then(data => {
            alert('Proveedor agregado correctamente');
            loadProveedores();
        })
        .catch(error => console.error('Error:', error));
    });

    document.getElementById('btnEliminar').addEventListener('click', () => {
        const id = document.getElementById('inputId').value;
        fetch(`services/admin_maestros_proveedores.php?action=deleteRow&id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            alert('Proveedor eliminado correctamente');
            loadProveedores();
        })
        .catch(error => console.error('Error:', error));
    });
});

function loadProveedores() {
    fetch('services/admin_maestros_proveedores.php?action=readAll')
    .then(response => response.json())
    .then(data => {
        const proveedoresContainer = document.getElementById('proveedoresContainer');
        proveedoresContainer.innerHTML = '';
        data.dataset.forEach(proveedor => {
            const proveedorElement = document.createElement('div');
            proveedorElement.innerHTML = `
                <p>ID: ${proveedor.id_proveedor}</p>
                <p>Nombre: ${proveedor.nombre_proveedor}</p>
                <p>País: ${proveedor.pais_proveedor}</p>
                <p>Giro Negocio: ${proveedor.giro_negocio_proveedor}</p>
                <p>DUI: ${proveedor.dui_proveedor}</p>
                <p>Nombre Comercial: ${proveedor.nombre_comercial_proveedor}</p>
                <p>Fecha: ${proveedor.fecha_proveedor}</p>
                <p>NIT: ${proveedor.nit_proveedor}</p>
                <p>Teléfono: ${proveedor.telefono_proveedor}</p>
                <p>Contacto: ${proveedor.contacto_proveedor}</p>
                <p>Dirección: ${proveedor.direccion_proveedor}</p>
                <p>Departamento: ${proveedor.departamento_proveedor}</p>
                <p>Municipio: ${proveedor.municipio_proveedor}</p>
                <button onclick="deleteProveedor(${proveedor.id_proveedor})">Eliminar</button>
            `;
            proveedoresContainer.appendChild(proveedorElement);
        });
    })
    .catch(error => console.error('Error:', error));
}

function deleteProveedor(id) {
    fetch(`services/admin_maestros_proveedores.php?action=deleteRow&id=${id}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        alert('Proveedor eliminado correctamente');
        loadProveedores();
    })
    .catch(error => console.error('Error:', error));
}
