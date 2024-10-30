<!--MEE-->
        <main class="mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Lista de productos</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <input type="text" id="searchInput" class="form-control me-2" placeholder="Buscar...">
                        <button id="searchButton" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#agregar_producto">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="list-group" id="productList">
                <?php if (isset($productos) && is_array($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <?php $fotoPath = $producto['foto'] ? "../uploads/productosimg/" . htmlspecialchars($producto['foto']) : "../uploads/productosimg/producto.jgp"; ?>
                                <img src="<?= $fotoPath ?>" alt="Product Image" class="product-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                    <p class="mb-1">Código: <?= htmlspecialchars($producto['codigo']) ?></p>
                                    <p class="mb-1">Marca: <?= htmlspecialchars($producto['marca']) ?></p>
                                    <p class="mb-1">Stock: <?= htmlspecialchars($producto['existencias']) ?></p>
                                    
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-outline-primary btn-sm btn-view-details" data-bs-toggle="modal" data-bs-target="#detalles_producto" data-product-id="<?= htmlspecialchars($producto['codigo']) ?>"><i class="fas fa-search"></i></button>
                                <button class="btn btn-outline-warning btn-sm btn-edit-product" data-bs-toggle="modal" data-bs-target="#editar_producto" data-product-id="<?= htmlspecialchars($producto['codigo']) ?>"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm btn-delete-product" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-product-id="<?= htmlspecialchars($producto['codigo']) ?>"><i class="fas fa-trash"></i></button>                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay productos registrados.</p>
                <?php endif; ?>
            </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="agregar_producto" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <img src="../img/Producto(1).JPG" alt="Producto" class="rounded-circle" width="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="marca">Marca:</label>
                            <select class="form-control" id="id_marca" name="id_marca">
                                <option value="">Seleccione</option>
                                <option value="1">Cisco</option>
                                <option value="2">TP-Link</option>
                                <option value="3">Belden</option>
                                <option value="4">Cober</option>
                            </select>
                            <div id="marcaError" class="error-message" style="color: red;"></div>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría:</label>
                            <select class="form-control" id="id_categoria" name="id_categoria">
                                <!-- Las opciones se actualizarán dinámicamente mediante JavaScript -->
                            </select>
                            <div id="categoriaError" class="error-message" style="color: red;"></div>
                        </div>
                        <div class="form-group">
                            <label for="no_serie">Número Serial:</label>
                            <input type="text" class="form-control" id="no_serie" name="no_serie" required>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo:</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" required>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio de Venta:</label>
                            <input type="text" class="form-control" id="precio" name="precio" required>
                            <div id="precioError" class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="precio_compra">Precio de Compra:</label>
                            <input type="text" class="form-control" id="precio_compra" name="precio_compra" required>
                            <div id="precio_compraError" class="error-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto:</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Producto -->
<div class="modal fade" id="editar_producto" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" enctype="multipart/form-data">
                    <input type="hidden" id="editProductId" name="codigo">
                    <input type="hidden" id="existing_foto" name="existing_foto">
                    <div class="text-center mb-4">
                        <img id="edit_foto_preview" src="../img/Producto(1).JPG" alt="Producto" class="rounded-circle" width="100">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre_edit" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <select class="form-control" id="id_marca_edit" name="id_marca">
                            <option value="1">Cisco</option>
                            <option value="2">TP-Link</option>
                            <option value="3">Belden</option>
                            <option value="4">Cober</option>
                        </select>
                        <div id="marcaEditError" class="error-message" style="color: red;"></div>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select class="form-control" id="id_categoria_edit" name="id_categoria">
                            <!-- Las opciones se actualizarán dinámicamente mediante JavaScript -->
                        </select>
                        <div id="categoriaEditError" class="error-message" style="color: red;"></div>
                    </div>
                    <div class="form-group">
                        <label for="no_serie">Número Serial:</label>
                        <input type="text" class="form-control" id="no_serie_edit" name="no_serie" required>
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" class="form-control" id="modelo_edit" name="modelo" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio de Venta:</label>
                        <input type="text" class="form-control" id="precio_edit" name="precio" required>
                        <div id="precioError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra:</label>
                        <input type="text" class="form-control" id="precio_compra_edit" name="precio_compra" required>
                        <div id="precio_compraEditError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" id="descripcion_edit" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto:</label>
                        <input type="file" class="form-control" id="foto_edit" name="foto">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal Eliminar Producto -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este producto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalles Producto -->
    <div class="modal fade" id="detalles_producto" tabindex="-1" aria-labelledby="detallesProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesProductoModalLabel">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="detalles_foto" src="../img/no-image.jpg" alt="Foto del producto" class="rounded-circle" width="100">
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <p id="detalles_nombre"></p>
                    </div>
                    <div class="form-group">
                        <label>Modelo:</label>
                        <p id="detalles_modelo"></p>
                    </div>
                    <div class="form-group">
                        <label>Número de serie:</label>
                        <p id="detalles_no_serie"></p>
                    </div>
                    <div class="form-group">
                        <label>Precio de Compra:</label>
                        <p id="detalles_precio_compra"></p>
                    </div>
                    <div class="form-group">
                        <label>Precio de Venta:</label>
                        <p id="detalles_precio"></p>
                    </div>
                    <div class="form-group">
                        <label>Stock:</label>
                        <p id="detalles_existencias"></p>
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <p id="detalles_descripcion"></p>
                    </div>
                    <div class="form-group">
                        <label>Marca:</label>
                        <p id="detalles_marca"></p>
                    </div>
                    <div class="form-group">
                        <label>Categoría:</label>
                        <p id="detalles_categoria"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and dependencies -->
    <script src="../js/scriptProductos1.js"></script>
    <script src="../js/scriptProductos2.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <script src="../js/custom_productos.js"></script>

    <script>
        //DETALLES PRODUCTO//
        document.addEventListener('DOMContentLoaded', function () {
    // Función para abrir el modal de detalles y cargar los datos del producto
    document.querySelectorAll('.btn-view-details').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');

            fetch(`Adm_index_Productos.php?action=get_producto2&codigo=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('detalles_nombre').innerText = data.nombre;
                        document.getElementById('detalles_modelo').innerText = data.modelo;
                        document.getElementById('detalles_no_serie').innerText = data.no_serie;
                        document.getElementById('detalles_precio_compra').innerText = data.precio_compra;
                        document.getElementById('detalles_precio').innerText = data.precio;
                        document.getElementById('detalles_existencias').innerText = data.existencias;
                        document.getElementById('detalles_descripcion').innerText = data.descripcion;
                        document.getElementById('detalles_marca').innerText = data.marca;
                        document.getElementById('detalles_categoria').innerText = data.categoria;

                        if (data.foto) {
                            document.getElementById('detalles_foto').src = `../uploads/productosimg/${data.foto}`;
                        } else {
                            document.getElementById('detalles_foto').src = '../img/no-image.jpg';
                        }

                        $('#detalles_producto').modal('show');
                    } else {
                        alert('Error al cargar los datos del producto');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
});

    </script>

    
    <script>
        //AGREGAR PRODUCTO//
    document.addEventListener('DOMContentLoaded', function () {
        const addProductForm = document.getElementById('addProductForm');
        
        addProductForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            fetch('Adm_index_Productos.php?action=create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#agregar_producto').modal('hide');
                    window.location.reload(); // Recarga la página para ver los cambios
                } else {
                    alert("Error al agregar producto: " + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    </script>

    <script>
        //EDITAR PRODUCTO//
        document.addEventListener('DOMContentLoaded', function () {
    let productIdToEdit = null;

    // Evento para abrir el modal de edición y cargar los datos del producto
    document.querySelectorAll('.btn-edit-product').forEach(button => {
        button.addEventListener('click', function () {
            productIdToEdit = this.getAttribute('data-product-id');

            fetch(`Adm_index_Productos.php?action=get_producto&codigo=${productIdToEdit}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('editProductId').value = data.codigo;
                        document.getElementById('nombre_edit').value = data.nombre;
                        document.getElementById('no_serie_edit').value = data.no_serie;
                        document.getElementById('modelo_edit').value = data.modelo;
                        document.getElementById('precio_edit').value = data.precio;
                        document.getElementById('precio_compra_edit').value = data.precio_compra;
                        document.getElementById('descripcion_edit').value = data.descripcion;
                        document.getElementById('existing_foto').value = data.foto;

                        // Actualizar la marca y disparar el evento change
                        document.getElementById('id_marca_edit').value = data.id_marca;
                        const marcaEvent = new Event('change');
                        document.getElementById('id_marca_edit').dispatchEvent(marcaEvent);

                        // Esperar a que se carguen las categorías y luego seleccionar la categoría correcta
                        const waitForCategoryOptions = () => {
                            return new Promise((resolve) => {
                                setTimeout(() => {
                                    resolve();
                                }, 100); // Delay for 100ms to allow categories to load
                            });
                        };

                        waitForCategoryOptions().then(() => {
                            document.getElementById('id_categoria_edit').value = data.id_categoria;
                        });

                        if (data.foto) {
                            document.getElementById('edit_foto_preview').src = `../uploads/productosimg/${data.foto}`;
                        } else {
                            document.getElementById('edit_foto_preview').src = '../img/no-image.jpg';
                        }

                        $('#editar_producto').modal('show');
                    } else {
                        alert('Error al cargar los datos del producto');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Evento para enviar el formulario de edición
    document.getElementById('editProductForm').addEventListener('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        fetch('Adm_index_Productos.php?action=update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#editar_producto').modal('hide');
                window.location.reload(); // Recarga la página para ver los cambios
            } else {
                alert('Error al actualizar el producto: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});


    </script>


    <script>
        //BORRAR PRODUCTO//
        document.addEventListener('DOMContentLoaded', function () {
    let productIdToDelete = null;

    // Evento para abrir el modal de confirmación y guardar el ID del producto a eliminar
    document.querySelectorAll('.btn-delete-product').forEach(button => {
        button.addEventListener('click', function () {
            productIdToDelete = this.getAttribute('data-product-id');
        });
    });

    // Evento para confirmar la eliminación del producto
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (productIdToDelete) {
            fetch('Adm_index_Productos.php?action=delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `codigo=${productIdToDelete}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#confirmDeleteModal').modal('hide');
                    window.location.reload(); // Recarga la página para ver los cambios
                } else {
                    alert("Error al eliminar producto: " + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});

    </script>

    <script>
        //CONDICIONAR SELECT AÑADIR//
        document.addEventListener('DOMContentLoaded', (event) => {
            const marcaSelect = document.getElementById('id_marca');
            const categoriaSelect = document.getElementById('id_categoria');

            const opcionesCategoria = {
                '1': [
                    { value: '1', text: 'Switches' },
                    { value: '2', text: 'Routers' },
                    { value: '4', text: 'Racks y Gabinetes' }
                ],
                '2': [
                    { value: '1', text: 'Switches' },
                    { value: '2', text: 'Routers' },
                    { value: '4', text: 'Racks y Gabinetes' }
                ],
                '3': [
                    { value: '3', text: 'Cables' }
                ],
                '4': [
                    { value: '3', text: 'Cables' }
                ]
            };

            marcaSelect.addEventListener('change', function() {
                const marcaSeleccionada = this.value;
                categoriaSelect.innerHTML = '';

                if (opcionesCategoria[marcaSeleccionada]) {
                    opcionesCategoria[marcaSeleccionada].forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.value;
                        option.textContent = categoria.text;
                        categoriaSelect.appendChild(option);
                    });
                }
            });
        });
    </script>

<script>
    //CONDICIONAR SELECT EDITAR//
        document.addEventListener('DOMContentLoaded', (event) => {
            const marcaSelect = document.getElementById('id_marca_edit');
            const categoriaSelect = document.getElementById('id_categoria_edit');

            const opcionesCategoria = {
                '1': [
                    { value: '1', text: 'Switches' },
                    { value: '2', text: 'Routers' },
                    { value: '4', text: 'Racks y Gabinetes' }
                ],
                '2': [
                    { value: '1', text: 'Switches' },
                    { value: '2', text: 'Routers' },
                    { value: '4', text: 'Racks y Gabinetes' }
                ],
                '3': [
                    { value: '3', text: 'Cables' }
                ],
                '4': [
                    { value: '3', text: 'Cables' }
                ]
            };

            marcaSelect.addEventListener('change', function() {
                const marcaSeleccionada = this.value;
                categoriaSelect.innerHTML = '';

                if (opcionesCategoria[marcaSeleccionada]) {
                    opcionesCategoria[marcaSeleccionada].forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.value;
                        option.textContent = categoria.text;
                        categoriaSelect.appendChild(option);
                    });
                }
            });
        });
    </script>
    
    <script>
        //BUSCAR PRODUCTO//
        document.addEventListener('DOMContentLoaded', function () {
    // Logica de busqueda en tiempo real para productos
    const searchInput = document.getElementById('searchInput');
    const productList = document.getElementById('productList');

    searchInput.addEventListener('input', function (e) {
        const searchTerm = e.target.value.toLowerCase();

        fetch(`Adm_index_Productos.php?action=search&query=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    productList.innerHTML = '';
                    data.forEach(producto => {
                        const fotoPath = producto.foto ? `../uploads/productosimg/${producto.foto}` : "../img/no-image.jpg";
                        const productItem = `
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div class="d-flex">
                                    <img src="${fotoPath}" alt="Product Image" class="product-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div>
                                        <h5 class="mb-1">${producto.nombre}</h5>
                                        <p class="mb-1">Código: ${producto.codigo}</p>
                                        <p class="mb-1">Marca: ${producto.marca}</p>
                                        <p class="mb-1">Stock: ${producto.existencias}</p>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm btn-view-details" data-bs-toggle="modal" data-bs-target="#detalles_producto" data-product-id="${producto.codigo}"><i class="fas fa-search"></i></button>
                                    <button class="btn btn-outline-warning btn-sm btn-edit-product" data-bs-toggle="modal" data-bs-target="#editar_producto" data-product-id="${producto.codigo}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-outline-danger btn-sm btn-delete-product" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-product-id="${producto.codigo}"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>`;
                        productList.innerHTML += productItem;
                    });
                    // Reasignar event listeners después de actualizar la lista
                    assignEventListeners();
                } else {
                    productList.innerHTML = '<p>No se encontraron productos.</p>';
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Función para asignar los event listeners
    function assignEventListeners() {
        // Ver detalles del producto
        document.querySelectorAll('.btn-view-details').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');

                fetch(`Adm_index_Productos.php?action=get_producto2&codigo=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('detalles_nombre').innerText = data.nombre;
                            document.getElementById('detalles_modelo').innerText = data.modelo;
                            document.getElementById('detalles_no_serie').innerText = data.no_serie;
                            document.getElementById('detalles_precio_compra').innerText = data.precio_compra;
                            document.getElementById('detalles_precio').innerText = data.precio;
                            document.getElementById('detalles_existencias').innerText = data.existencias;
                            document.getElementById('detalles_descripcion').innerText = data.descripcion;
                            document.getElementById('detalles_marca').innerText = data.marca;
                            document.getElementById('detalles_categoria').innerText = data.categoria;

                            if (data.foto) {
                                document.getElementById('detalles_foto').src = `../uploads/productosimg/${data.foto}`;
                            } else {
                                document.getElementById('detalles_foto').src = '../img/no-image.jpg';
                            }

                            $('#detalles_producto').modal('show');
                        } else {
                            alert('Error al cargar los datos del producto');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Editar producto
        document.querySelectorAll('.btn-edit-product').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');

                fetch(`Adm_index_Productos.php?action=get_producto&codigo=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('editProductId').value = data.codigo;
                            document.getElementById('nombre_edit').value = data.nombre;
                            document.getElementById('no_serie_edit').value = data.no_serie;
                            document.getElementById('modelo_edit').value = data.modelo;
                            document.getElementById('precio_edit').value = data.precio;
                            document.getElementById('precio_compra_edit').value = data.precio_compra;
                            document.getElementById('descripcion_edit').value = data.descripcion;
                            document.getElementById('existing_foto').value = data.foto;

                            // Actualizar la marca y disparar el evento change
                            document.getElementById('id_marca_edit').value = data.id_marca;
                            const marcaEvent = new Event('change');
                            document.getElementById('id_marca_edit').dispatchEvent(marcaEvent);

                            // Esperar a que se carguen las categorías y luego seleccionar la categoría correcta
                            const waitForCategoryOptions = () => {
                                return new Promise((resolve) => {
                                    setTimeout(() => {
                                        resolve();
                                    }, 100); // Delay for 100ms to allow categories to load
                                });
                            };

                            waitForCategoryOptions().then(() => {
                                document.getElementById('id_categoria_edit').value = data.id_categoria;
                            });

                            if (data.foto) {
                                document.getElementById('edit_foto_preview').src = `../uploads/productosimg/${data.foto}`;
                            } else {
                                document.getElementById('edit_foto_preview').src = '../img/no-image.jpg';
                            }

                            $('#editar_producto').modal('show');
                        } else {
                            alert('Error al cargar los datos del producto');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Eliminar producto
        document.querySelectorAll('.btn-delete-product').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                document.getElementById('deleteProductId').value = productId;
                $('#confirmDeleteModal').modal('show');
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            const productId = document.getElementById('deleteProductId').value;

            fetch('Adm_index_Productos.php?action=delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `codigo=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al eliminar el producto: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Inicialmente asignar event listeners
    assignEventListeners();
});

    </script>
