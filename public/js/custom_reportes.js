document.addEventListener('DOMContentLoaded', function () {
    // Función para obtener y mostrar los productos en el modal
    function fetchProductos(query) {
        fetch(`../controller/Adm_reporte_controller.php?action=buscar_producto&query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                const tablaProductos = document.querySelector('#tablaProductos tbody');
                tablaProductos.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(producto => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${producto.Codigo}</td>
                            <td>${producto.Nombre}</td>
                            <td>${producto.Precio}</td>
                            <td>${producto.No_existencias}</td>
                            <td><button class="btn btn-primary btn-sm seleccionar-producto" data-id="${producto.Codigo}" data-dismiss="modal">Seleccionar</button></td>
                        `;
                        tablaProductos.appendChild(row);
                    });

                    document.querySelectorAll('.seleccionar-producto').forEach(button => {
                        button.addEventListener('click', function() {
                            const codigoProducto = this.dataset.id;
                            document.getElementById('codigo_producto').value = codigoProducto;
                            $('#productoModal').modal('hide');
                            // Forzar el cierre completo del modal
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        });
                    });
                } else {
                    tablaProductos.innerHTML = '<tr><td colspan="5">No hay datos disponibles</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
    }

    $('#productoModal').on('show.bs.modal', function() {
        fetchProductos('');
    });

    document.getElementById('buscarProducto').addEventListener('input', function () {
        const query = this.value.trim();
        fetchProductos(query);
    });

    // Código para los reportes existentes...
    function fetchReport(url, elementId) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (elementId === 'reporte1') {
                    // Crear gráfica para reporte1
                    const labels = data.map(item => item.Marca);
                    const values = data.map(item => item.Total_de_Venta_del_ultimo_mes);
                    createChart('reporte1Chart', labels, values, 'Venta total por cada Marca');
                } else {
                    // Resto de reportes como texto
                    const reportContainer = document.getElementById(elementId);
                    reportContainer.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const itemText = Object.values(item).map(value => `<strong>${value}</strong>`).join(': ');
                            const itemElement = document.createElement('p');
                            itemElement.innerHTML = itemText;
                            reportContainer.appendChild(itemElement);
                        });
                    } else {
                        reportContainer.textContent = 'No hay datos disponibles';
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching report:', error);
            });
    }

    function createChart(elementId, labels, data, title) {
        const ctx = document.getElementById(elementId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: title,
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Ajusta las rutas de las solicitudes para que sean correctas
    fetchReport('../controller/Adm_reporte_controller.php?action=getReporte1', 'reporte1');
    fetchReport('../controller/Adm_reporte_controller.php?action=getReporte2', 'reporte2');
    fetchReport('../controller/Adm_reporte_controller.php?action=getReporte3', 'reporte3');
});
