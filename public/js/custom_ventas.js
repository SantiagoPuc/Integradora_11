document.addEventListener('DOMContentLoaded', function() {
    const btnBuscarCliente = document.querySelector('#btnBuscarCliente');
    const btnBuscarProducto = document.querySelector('#btnBuscarProducto');
    const idClienteInput = document.querySelector('#idCliente');
    const nombreClienteInput = document.querySelector('#nombreCliente');
    const idProductoInput = document.querySelector('#idProducto');
    const nombreProductoInput = document.querySelector('#nombreProducto');
    const cantidadProductoInput = document.querySelector('#cantidadProducto');
    const precioProductoInput = document.querySelector('#precioProducto');
    const tablaVentas = document.querySelector('#tabla_ventas tbody');
    const totalVentaInput = document.querySelector('#total_venta');
    const btnAnadirProducto = document.querySelector('#btn_anadir_producto');
    const btnGenerarVenta = document.querySelector('#btn_generar_venta');
    const buscarFolioInput = document.querySelector('#buscarFolio');

    fetchAllVentas();

    // Evento para actualizar las tablas al escribir en el campo de búsqueda
    buscarFolioInput.addEventListener('keyup', function() {
        const folio = buscarFolioInput.value.trim();
        if (folio) {
            buscarVentasPorFolio(folio);
        } else {
            fetchAllVentas();
        }
    });

    // Función para buscar todas las ventas
    function fetchAllVentas() {
        fetch('Adm_index_venta.php?action=obtener_todas_las_ventas')
            .then(response => response.json())
            .then(data => {
                mostrarInformacionVenta(data);
                return fetch('Adm_index_venta.php?action=obtener_todos_los_detalles_de_ventas');
            })
            .then(response => response.json())
            .then(data => {
                mostrarDetalleVenta(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar las ventas.');
            });
    }

    // Función para buscar ventas por folio
    function buscarVentasPorFolio(folio) {
        fetch('Adm_index_venta.php?action=buscar_ventas_por_folio&folio=' + encodeURIComponent(folio))
            .then(response => response.json())
            .then(data => {
                mostrarInformacionVenta(data);
                if (data.length > 0) {
                    const folioVenta = data[0]['Folio de Venta'];
                    fetch('Adm_index_venta.php?action=mostrar_detalle_venta&id_venta=' + encodeURIComponent(folioVenta))
                        .then(response => response.json())
                        .then(detalleData => {
                            mostrarDetalleVenta(detalleData);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al buscar el detalle de la venta.');
                        });
                } else {
                    mostrarDetalleVenta([]);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al buscar las ventas por folio.');
            });
    }

    // Función para mostrar la información de la venta
    function mostrarInformacionVenta(data) {
        const infoVentaDiv = document.getElementById('infoVenta');
        if (data.length > 0) {
            infoVentaDiv.innerHTML = `
                <table class="table table-striped" style="max-height: 200px; overflow-y: auto;">
                    <thead>
                        <tr>
                            <th>Folio de Venta</th>
                            <th>Nombre del Usuario</th>
                            <th>Nombre del Cliente</th>
                            <th>Fecha y Hora</th>
                            <th>Monto Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(venta => `
                            <tr>
                                <td>${venta['Folio de Venta']}</td>
                                <td>${venta['Nombre del Usuario que registro la venta']}</td>
                                <td>${venta['Nombre del Cliente']}</td>
                                <td>${venta['Fecha y Hora']}</td>
                                <td>${venta['Monto Total']}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            infoVentaDiv.innerHTML = 'No se encontró la información de la venta.';
        }
    }

    // Función para mostrar el detalle de la venta
    function mostrarDetalleVenta(data) {
        const detalleVentaDiv = document.getElementById('detalleVenta');
        if (data.length > 0) {
            detalleVentaDiv.innerHTML = `
                <table class="table table-striped" style="max-height: 200px; overflow-y: auto;">
                    <thead>
                        <tr>
                            <th>Folio de Venta</th>
                            <th>Código del Producto</th>
                            <th>Nombre del Producto</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(detalle => `
                            <tr>
                                <td>${detalle['Folio de Venta']}</td>
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
            detalleVentaDiv.innerHTML = 'No se encontró el detalle de la venta.';
        }
    }

    function fetchClientes(query) {
        console.log('Buscando cliente con query:', query); // Verificador de errores
        fetch('Adm_index_venta.php?action=buscar_cliente&query=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Clientes recibidos:', data); // Verificador de errores
                let rows = '';
                data.forEach(cliente => {
                    rows += `<tr>
                                <td>${cliente.ID_cliente}</td>
                                <td>${cliente.Nombre}</td>
                                <td>${cliente.Apellido_1}</td>
                                <td>${cliente.Apellido_2}</td>
                                <td>${cliente.Correo}</td>
                                <td>${cliente.Telefono}</td>
                                <td><button class="btn btn-primary btn-sm seleccionar-producto" data-id="${producto.Codigo}" data-dismiss="modal">Seleccionar</button></td></tr>`;
                });
                document.querySelector('#tablaClientes tbody').innerHTML = rows;
                document.querySelectorAll('.seleccionar-cliente').forEach(button => {
                    button.addEventListener('click', function() {
                        seleccionarCliente(this.dataset.id, this.dataset.nombre);
                    });
                });
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX para buscar cliente:', error); // Verificador de errores
            });
    }

    function fetchProductos(query) {
        console.log('Buscando producto con query:', query); // Verificador de errores
        fetch('Adm_index_venta.php?action=buscar_producto&query=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Productos recibidos:', data); // Verificador de errores
                let rows = '';
                data.forEach(producto => {
                    rows += `<tr>
                                <td>${producto.Codigo}</td>
                                <td>${producto.Nombre}</td>
                                <td>${producto.Precio}</td>
                                <td>${producto.No_existencias}</td>
                                <td><button class="btn btn-primary btn-sm seleccionar-producto" data-id="${producto.Codigo}" data-nombre="${producto.Nombre}" data-precio="${producto.Precio}" data-existencias="${producto.No_existencias}" data-dismiss="modal">Seleccionar</button></td>
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
            });
    }

    window.seleccionarCliente = function(id, nombre) {
        console.log('Cliente seleccionado:', id, nombre); // Verificador de errores
        idClienteInput.value = id;
        nombreClienteInput.value = nombre;
    }

    window.seleccionarProducto = function(id, nombre, precio, existencias) {
        console.log('Producto seleccionado:', id, nombre, precio, existencias); // Verificador de errores
        idProductoInput.value = id;
        nombreProductoInput.value = nombre;
        precioProductoInput.value = precio;
        cantidadProductoInput.dataset.existencias = existencias; // Guardar existencias en un atributo de datos
    }

    btnBuscarCliente.addEventListener('click', function() {
        const query = document.querySelector('#buscarCliente').value;
        fetchClientes(query);
    });

    btnBuscarProducto.addEventListener('click', function() {
        const query = document.querySelector('#buscarProducto').value;
        fetchProductos(query);
    });

    document.querySelector('#buscarCliente').addEventListener('input', function() {
        const query = this.value;
        fetchClientes(query);
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

        if (cantidad > existencias) {
            alert('La cantidad no puede ser mayor que las existencias.');
            return;
        }

        let productoExistente = false;
        tablaVentas.querySelectorAll('tr').forEach(row => {
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
        tablaVentas.appendChild(row);
        actualizarTotal();

        // Limpiar los campos después de añadir el producto
        idProductoInput.value = '';
        nombreProductoInput.value = '';
        cantidadProductoInput.value = '';
        precioProductoInput.value = '';
    });


    btnGenerarVenta.addEventListener('click', function() {
        const idCliente = idClienteInput.value;
        const productos = [];
        tablaVentas.querySelectorAll('tr').forEach(row => {
            const idProducto = row.dataset.idProducto;
            const cantidad = parseInt(row.cells[3].textContent);
            const subtotal = parseFloat(row.cells[4].textContent);
            productos.push({ id_producto: idProducto, cantidad: cantidad, subtotal: subtotal });
        });

        if (!idCliente || productos.length === 0) {
            alert('Por favor seleccione un cliente y añada al menos un producto.');
            return;
        }

        console.log('Datos enviados:', { id_cliente: idCliente, productos: productos });

        fetch('Adm_index_venta.php?action=generar_folio_venta', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_cliente: idCliente, productos: productos })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Venta generada con éxito.');
                location.reload();
            } else {
                console.error('Error en la respuesta del servidor:', data.message);
                alert('Error al generar la venta: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error en el proceso de generación de la venta:', error);
            alert('Error al generar la venta: ' + error.message);
        });
    });

    function actualizarTotal() {
        let total = 0;
        tablaVentas.querySelectorAll('tr').forEach(row => {
            total += parseFloat(row.cells[4].textContent);
        });
        totalVentaInput.value = total.toFixed(2);
    }
});


