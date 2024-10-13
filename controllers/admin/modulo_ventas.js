document.addEventListener('DOMContentLoaded', function () {
    const PRODUCTO_API = 'services/admin/admin_maestro_productos.php';
    const tableBody = document.getElementById('tableBody');
    const searchRow = document.getElementById('searchRow');

    attachEventListeners(searchRow);

    function fetchProducts(term) {
        return fetch(`${PRODUCTO_API}?action=search&term=${encodeURIComponent(term)}`)
            .then(response => response.json());
    }

    function searchProducts(input) {
        const searchTerm = input.value.trim();
        const searchResults = input.nextElementSibling;

        if (searchTerm.length < 2) {
            searchResults.innerHTML = '';
            return;
        }

        fetchProducts(searchTerm)
            .then(products => {
                searchResults.innerHTML = '';
                products.forEach(product => {
                    const div = document.createElement('div');
                    div.classList.add('dropdown-item');
                    div.textContent = `${product.codigo} - ${product.nombre}`;
                    div.addEventListener('click', () => selectProduct(product, input.closest('tr')));
                    searchResults.appendChild(div);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function selectProduct(product, row) {
        row.product = product;
        row.querySelector('.productSearch').value = `${product.codigo} - ${product.nombre}`;
        row.querySelector('.unitPrice').value = parseFloat(product.precio_venta).toFixed(2);
        row.querySelector('.searchResults').innerHTML = '';
        updateSubtotal(row.querySelector('.quantity'));
    }

    function updateSubtotal(quantityInput) {
        const row = quantityInput.closest('tr');
        if (row.product) {
            const price = parseFloat(row.product.precio_venta) || 0;
            const quantity = parseFloat(quantityInput.value) || 0;
            const total = price * quantity;
            row.querySelector('.subtotal').value = total.toFixed(2);
        }
    }

    function addProductToTable(row) {
        if (!row.product) return;

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${row.product.codigo}</td>
            <td>${row.product.nombre}</td>
            <td>${row.querySelector('.quantity').value}</td>
            <td>${parseFloat(row.product.precio_venta).toFixed(2)}</td>
            <td>${row.querySelector('.subtotal').value}</td>
            <td><button class="btn btn-danger btn-sm removeProduct">Eliminar</button></td>
        `;

        newRow.querySelector('.removeProduct').addEventListener('click', function () {
            tableBody.removeChild(newRow);
            updateTotal();
        });

        tableBody.appendChild(newRow);
        updateTotal();
        resetSearchRow(row);

        // Clonar la fila de búsqueda si no es la fila fija
        if (row !== searchRow) {
            const clonedRow = row.cloneNode(true);
            setupSearchRow(clonedRow);
            tableBody.insertBefore(clonedRow, row);
        }
    }

    function resetSearchRow(row) {
        row.querySelector('.productSearch').value = '';
        row.querySelector('.quantity').value = '1';
        row.querySelector('.unitPrice').value = '';
        row.querySelector('.subtotal').value = '';
        row.product = null;
    }

    function updateTotal() {
        const total = Array.from(tableBody.querySelectorAll('tr:not(#searchRow)'))
            .reduce((sum, row) => sum + (parseFloat(row.querySelector('.subtotal').value) || 0), 0);
        document.getElementById('total').value = total.toFixed(2);
    }

    function attachEventListeners(row) {
        row.querySelector('.productSearch').addEventListener('input', debounce(event => searchProducts(event.target), 300));
        row.querySelector('.quantity').addEventListener('input', event => updateSubtotal(event.target));
        row.querySelector('.addProduct').addEventListener('click', () => addProductToTable(row));
    }

    function setupSearchRow(row) {
        attachEventListeners(row);
        resetSearchRow(row);
    }

    function debounce(func, delay) {
        let timeoutId;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(context, args), delay);
        };
    }
});
