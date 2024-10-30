<script src="../js/custom_reportes.js"></script>
<script src="../js/chartt.js"></script>
<main class="mt-3">
    <div class="container">
        <h2 class="text-center my-4">Reportes</h2>
        <div class="report-section">
            <div class="report-title">Venta total por cada Marca:</div>
            <canvas id="reporte1Chart"></canvas>
        </div>
        <div class="report-section">
            <div class="report-title">Producto más barato:</div>
            <div class="report-content" id="reporte2"></div>
        </div>
        <div class="report-section">
            <div class="report-title">Producto más vendido:</div>
            <div class="report-content" id="reporte3"></div>
        </div>


        <!--FUNCIÓN MATEMÁTICA-->
        <!-- Agregar los campos de precio y precio de compra en el formulario -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <main class="mt-3">
    <div class="container">
        <h2 class="text-center my-4">Cálculo de Inventario</h2>
        <!-- Formulario existente -->
        <form action="../controller/Adm_reporte_controller.php" method="GET">
    <input type="hidden" name="action" value="calcular_inventario">
    <div class="form-group">
        <label for="codigo_producto">Código de Producto:</label>
        <div class="input-group mb-2">
            <input type="text" class="form-control" id="codigo_producto" name="codigo_producto" required>
            <button id="btnBuscarProducto" class="input-group-text" data-bs-toggle="modal" data-bs-target="#productoModal"><i class="fas fa-search"></i></button>
        </div>
    </div>
    <div class="form-group">
        <label for="precio">Precio de Venta por Unidad (Pv):</label>
        <input type="text" class="form-control" id="precio" name="precio" readonly>
    </div>
    <div class="form-group">
        <label for="precio_compra">Costo de Compra por Unidad (Cc):</label>
        <input type="text" class="form-control" id="precio_compra" name="precio_compra" readonly>
    </div>
    <div class="form-group">
        <label for="demanda_mensual">Demanda Mensual (D):</label>
        <input type="text" class="form-control" id="demanda_mensual" name="demanda_mensual" readonly>
    </div>
    <div class="form-group">
        <label for="cs">Costo de Almacenamiento por Unidad por Mes (Cs):</label>
        <input type="number" class="form-control" id="cs" name="cs" required>
    </div>
    <button type="submit" class="btn btn-primary">Calcular Inventario</button>
</form>
<?php if (isset($resultados)): ?>
            <h2 class="text-center my-4">Ganancia Neta en Función del Stock:    G(S) = D * (Pv - Cc) - S * Cs</h2>
            <p class="text-center"><strong>Cantidad de stock máxima para seguir obteniendo ganacias:</strong> <?php echo $resultados['ultimoValorPositivo']['stock']; ?></p>
            <canvas id="gananciaChart"></canvas>
            <script>
                const ctx = document.getElementById('gananciaChart').getContext('2d');
                const stock = <?php echo json_encode($resultados['stock']); ?>;
                const ganancia = <?php echo json_encode($resultados['ganancia']); ?>;
                const ultimoValorPositivo = <?php echo json_encode($resultados['ultimoValorPositivo']); ?>;

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: stock,
                        datasets: [{
                            label: 'Ganancia Neta',
                            data: ganancia,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false,
                            pointBackgroundColor: stock.map(s => s === ultimoValorPositivo.stock ? 'red' : 'rgba(75, 192, 192, 1)'),
                            pointRadius: stock.map(s => s === ultimoValorPositivo.stock ? 6 : 3)
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        },
                        plugins: {
                            annotation: {
                                annotations: {
                                    point1: {
                                        type: 'point',
                                        xValue: ultimoValorPositivo.stock,
                                        yValue: ultimoValorPositivo.ganancia,
                                        backgroundColor: 'red',
                                        borderColor: 'red',
                                        borderWidth: 2,
                                        radius: 6,
                                        label: {
                                            content: 'Último valor positivo',
                                            enabled: true,
                                            position: 'top'
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        <?php else: ?>
            <h2>No se han calculado los stocks aún.</h2>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
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
                            <td><button class="btn btn-primary btn-sm seleccionar-producto" data-id="${producto.Codigo}" data-nombre="${producto.Nombre}" data-precio="${producto.Precio}" data-preciocompra="${producto.Precio_compra}" data-demanda="${producto.Demanda_Mensual}">Seleccionar</button></td>
                        `;
                        tablaProductos.appendChild(row);
                    });

                    document.querySelectorAll('.seleccionar-producto').forEach(button => {
                        button.addEventListener('click', function() {
                            const codigoProducto = this.dataset.id;
                            const precio = this.dataset.precio;
                            const precioCompra = this.dataset.preciocompra;
                            const demanda = this.dataset.demanda;
                            document.getElementById('codigo_producto').value = codigoProducto;
                            document.getElementById('precio').value = precio;
                            document.getElementById('precio_compra').value = precioCompra;
                            document.getElementById('demanda_mensual').value = demanda;
                            $('#productoModal').modal('hide');
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
});
</script>


<!-- Modal para buscar productos -->
<div class="modal fade" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productoModalLabel">Buscar Producto</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-2" id="buscarProducto" placeholder="Ingrese el ID o Nombre del Producto">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped" id="tablaProductos">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Existencias</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se llenarán los datos de los productos -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
