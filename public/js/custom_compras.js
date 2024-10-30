document.addEventListener('DOMContentLoaded', function() {
    const btnBuscarProveedor = document.querySelector('#btnBuscarProveedor');
    const btnBuscarProducto = document.querySelector('#btnBuscarProducto');
    const idProveedorInput = document.querySelector('#idProveedor');
    const nombreProveedorInput = document.querySelector('#nombreProveedor');
    const idProductoInput = document.querySelector('#idProducto');
    const nombreProductoInput = document.querySelector('#nombreProducto');
    const cantidadProductoInput = document.querySelector('#cantidadProducto');
    const precioProductoInput = document.querySelector('#precioProducto');
    const tablaCompras = document.querySelector('#tabla_compras tbody');
    const totalCompraInput = document.querySelector('#total_compra');
    const btnAnadirProducto = document.querySelector('#btn_anadir_producto');
    const btnGenerarCompra = document.querySelector('#btn_generar_compra');
    const buscarFolioInput = document.querySelector('#buscarFolio');

    fetchAllCompras();

    // Evento para actualizar las tablas al escribir en el campo de búsqueda
    buscarFolioInput.addEventListener('keyup', function() {
        const folio = buscarFolioInput.value.trim();
        if (folio) {
            buscarComprasPorFolio(folio);
        } else {
            fetchAllCompras();
        }
    });

    // Función para buscar todas las compras
    function fetchAllCompras() {
        fetch('Adm_index_compra.php?action=obtener_todas_las_compras')
            .then(response => response.json())
            .then(data => {
                mostrarInformacionCompra(data);
                return fetch('Adm_index_compra.php?action=obtener_todos_los_detalles_de_compras');
            })
            .then(response => response.json())
            .then(data => {
                mostrarDetalleCompra(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar las compras.');
            });
    }

    // Función para buscar compras por folio
    function buscarComprasPorFolio(folio) {
        fetch('Adm_index_compra.php?action=buscar_compras_por_folio&folio=' + encodeURIComponent(folio))
            .then(response => response.json())
            .then(data => {
                mostrarInformacionCompra(data);
                if (data.length > 0) {
                    const folioCompra = data[0]['Folio de Compra'];
                    fetch('Adm_index_compra.php?action=mostrar_detalle_compra&id_compra=' + encodeURIComponent(folioCompra))
                        .then(response => response.json())
                        .then(detalleData => {
                            mostrarDetalleCompra(detalleData);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al buscar el detalle de la compra.');
                        });
                } else {
                    mostrarDetalleCompra([]);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al buscar las compras por folio.');
            });
    }

    // Función para mostrar la información de la compra
    function mostrarInformacionCompra(data) {
        const infoCompraDiv = document.getElementById('infoCompra');
        if (data.length > 0) {
            infoCompraDiv.innerHTML = `
                <table class="table table-striped" style="max-height: 200px; overflow-y: auto;">
                    <thead>
                        <tr>
                            <th>Folio de Compra</th>
                            <th>Nombre del Usuario</th>
                            <th>Nombre del Proveedor</th>
                            <th>Fecha y Hora</th>
                            <th>Monto Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(compra => `
                            <tr>
                                <td>${compra['Folio de Compra']}</td>
                                <td>${compra['Nombre del Usuario que registro la compra']}</td>
                                <td>${compra['Nombre del Proveedor']}</td>
                                <td>${compra['Fecha y Hora']}</td>
                                <td>${compra['Monto Total']}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            infoCompraDiv.innerHTML = 'No se encontró la información de la compra.';
        }
    }

    // Función para mostrar el detalle de la compra
    function mostrarDetalleCompra(data) {
        const detalleCompraDiv = document.getElementById('detalleCompra');
        if (data.length > 0) {
            detalleCompraDiv.innerHTML = `
                <table class="table table-striped" style="max-height: 200px; overflow-y: auto;">
                    <thead>
                        <tr>
                            <th>Folio de Compra</th>
                            <th>Código del Producto</th>
                            <th>Nombre del Producto</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(detalle => `
                            <tr>
                                <td>${detalle['Folio de Compra']}</td>
                                <td>${detalle['Código del Producto']}</td>
                                <td>${detalle['Nombre del Producto']}</td>
                                <td>${detalle['Cantidad']}</td>
                                <td>${detalle['Subtotal']}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            detalleCompraDiv.innerHTML = 'No se encontró el detalle de la compra.';
        }
    }

    function fetchProveedores(query) {
        console.log('Buscando proveedor con query:', query); // Verificador de errores
        fetch('Adm_index_compra.php?action=buscar_proveedor&query=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Proveedores recibidos:', data); // Verificador de errores
                if (data.error) {
                    console.error('Error en la respuesta del servidor:', data.error);
                    alert('Error al buscar proveedores: ' + data.error);
                    return;
                }
                let rows = '';
                data.forEach(proveedor => {
                    rows += `<tr>
                                <td>${proveedor.ID_proveedor}</td>
                                <td>${proveedor.Nombre}</td>
                                <td>${proveedor.Apellido_1}</td>
                                <td>${proveedor.Apellido_2}</td>
                                <td>${proveedor.RFC}</td>
                                <td>${proveedor.Telefono}</td>
                                <td><button class="btn btn-primary btn-sm seleccionar-proveedor" data-id="${proveedor.ID_proveedor}" data-nombre="${proveedor.Nombre}" data-dismiss="modal">Seleccionar</button></td>
                            </tr>`;
                });
                document.querySelector('#tablaProveedores tbody').innerHTML = rows;
                document.querySelectorAll('.seleccionar-proveedor').forEach(button => {
                    button.addEventListener('click', function() {
                        seleccionarProveedor(this.dataset.id, this.dataset.nombre);
                    });
                });
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX para buscar proveedor:', error); // Verificador de errores
                alert('Error al buscar proveedores: ' + error.message);
            });
    }
    
    
    
    

    function fetchProductos(query) {
        console.log('Buscando producto con query:', query); // Verificador de errores
        fetch('Adm_index_compra.php?action=buscar_producto&query=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Productos recibidos:', data); // Verificador de errores
                if (data.error) {
                    console.error('Error en la respuesta del servidor:', data.error);
                    alert('Error al buscar productos: ' + data.error);
                    return;
                }
                let rows = '';
                data.forEach(producto => {
                    rows += `<tr>
                                <td>${producto.Codigo}</td>
                                <td>${producto.Nombre}</td>
                                <td>${producto.Precio_compra}</td>
                                <td>${producto.No_existencias}</td>
                                <td><button class="btn btn-primary btn-sm seleccionar-producto" data-id="${producto.Codigo}" data-nombre="${producto.Nombre}" data-precio="${producto.Precio_compra}" data-existencias="${producto.No_existencias}" data-dismiss="modal">Seleccionar</button></td>
                            </tr>`;
                });
                document.querySelector('#tablaProductos tbody').innerHTML = rows;
                document.querySelectorAll('.seleccionar-producto').forEach(button => {
                    button.addEventListener('click', function() {
                        seleccionarProducto(this.dataset.id, this.dataset.nombre, this.dataset.precio, this.dataset.existencias);
                    });
                });
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX para buscar producto:', error); // Verificador de errores
                alert('Error al buscar productos: ' + error.message);
            });
    }
    

    window.seleccionarProveedor = function(id, nombre) {
        console.log('Proveedor seleccionado:', id, nombre); // Verificador de errores
        idProveedorInput.value = id;
        nombreProveedorInput.value = nombre;
    }

    window.seleccionarProducto = function(id, nombre, precio, existencias) {
        console.log('Producto seleccionado:', id, nombre, precio, existencias); // Verificador de errores
        idProductoInput.value = id;
        nombreProductoInput.value = nombre;
        precioProductoInput.value = precio;
        cantidadProductoInput.dataset.existencias = existencias; // Guardar existencias en un atributo de datos
    }

    btnBuscarProveedor.addEventListener('click', function() {
        const query = document.querySelector('#buscarProveedor').value;
        fetchProveedores(query);
    });

    btnBuscarProducto.addEventListener('click', function() {
        const query = document.querySelector('#buscarProducto').value;
        fetchProductos(query);
    });

    document.querySelector('#buscarProveedor').addEventListener('input', function() {
        const query = this.value;
        fetchProveedores(query);
    });

    document.querySelector('#buscarProducto').addEventListener('input', function() {
        const query = this.value;
        fetchProductos(query);
    });

    btnAnadirProducto.addEventListener('click', function() {
        const idProducto = idProductoInput.value;
        const nombreProducto = nombreProductoInput.value;
        const cantidad = parseInt(cantidadProductoInput.value);
        const precio = parseFloat(precioProductoInput.value);
        const existencias = parseInt(cantidadProductoInput.dataset.existencias);
        const subtotal = cantidad * precio;

        if (!idProducto || !nombreProducto || !cantidad || isNaN(precio)) {
            alert('Por favor complete todos los campos.');
            return;
        }

        let productoExistente = false;
        tablaCompras.querySelectorAll('tr').forEach(row => {
            if (row.dataset.idProducto === idProducto) {
                productoExistente = true;
                alert('El producto ya está en la lista.');
            }
        });

        if (productoExistente) return;

        const row = document.createElement('tr');
        row.dataset.idProducto = idProducto;
        row.innerHTML = `
            <td>${idProducto}</td>
            <td>${nombreProducto}</td>
            <td>${precio}</td>
            <td>${cantidad}</td>
            <td>${subtotal}</td>
            <td><button class="btn btn-danger btn-sm">Eliminar</button></td>
        `;
        row.querySelector('button').addEventListener('click', function() {
            row.remove();
            actualizarTotal();
        });
        tablaCompras.appendChild(row);
        actualizarTotal();

        // Limpiar los campos después de añadir el producto
        idProductoInput.value = '';
        nombreProductoInput.value = '';
        cantidadProductoInput.value = '';
        precioProductoInput.value = '';
    });

    btnGenerarCompra.addEventListener('click', function() {
        const idProveedor = idProveedorInput.value;
        const productos = [];
        tablaCompras.querySelectorAll('tr').forEach(row => {
            const idProducto = row.dataset.idProducto;
            const cantidad = parseInt(row.cells[3].textContent);
            const subtotal = parseFloat(row.cells[4].textContent);
            productos.push({ id_producto: idProducto, cantidad: cantidad, subtotal: subtotal });
        });

        if (!idProveedor || productos.length === 0) {
            alert('Por favor seleccione un proveedor y añada al menos un producto.');
            return;
        }

        console.log('Datos enviados:', { id_proveedor: idProveedor, productos: productos });

        fetch('Adm_index_compra.php?action=generar_folio_compra', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_proveedor: idProveedor, productos: productos })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Compra generada con éxito.');
                location.reload();
            } else {
                console.error('Error en la respuesta del servidor:', data.message);
                alert('Error al generar la compra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error en el proceso de generación de la compra:', error);
            alert('Error al generar la compra: ' + error.message);
        });
    });

    function actualizarTotal() {
        let total = 0;
        tablaCompras.querySelectorAll('tr').forEach(row => {
            total += parseFloat(row.cells[4].textContent);
        });
        totalCompraInput.value = total.toFixed(2);
    }
});
