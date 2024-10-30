


<main class="mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Lista de Proveedores</h2>
        </div>
        <div class="card-body">
            <div class="d-flex mb-3">
                <input type="text" id="buscarProveedor" class="form-control me-2" placeholder="Buscar...">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#agregar_proveedor" style="height: 34px; padding: 0 10px; font-size: 14px; line-height: 1;">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="btn btn-outline-success me-1" data-bs-toggle="modal" data-bs-target="#habilitar_proveedor" style="height: 34px; padding: 0 10px; font-size: 14px; line-height: 1;">
                    <i class="fas fa-check"></i> Habilitar
                </button>
            </div>
            <div class="list-group" id="listaProveedores">
                <?php if (isset($proveedores) && is_array($proveedores)): ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <?php $fotoPath = $proveedor['foto'] ? "../uploads/proveedoresimg/" . htmlspecialchars($proveedor['foto']) : "../img/0.jpg"; ?>
                                <img src="<?= $fotoPath ?>" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($proveedor['nombre']) ?></h5>
                                    <p class="mb-1">ID: <?= htmlspecialchars($proveedor['id_proveedor']) ?></p>
                                    <p class="mb-1">RFC: <?= htmlspecialchars($proveedor['rfc']) ?></p>
                                    <p class="mb-1">Teléfono: <?= htmlspecialchars($proveedor['telefono']) ?></p>
                                    <p class="mb-1">Dirección: <?= htmlspecialchars($proveedor['calle'] . ' ' . $proveedor['numero'] . ', ' . $proveedor['colonia'] . ', ' . $proveedor['Ciudad'] . ', ' . $proveedor['Estado'] . ', ' . $proveedor['Pais']) ?></p>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1 btn-view-details" data-user-id="<?= $proveedor['id_proveedor'] ?>"><i class="fas fa-search"></i></button>
                                <button class="btn btn-outline-warning btn-sm btn-edit-user" data-bs-toggle="modal" data-bs-target="#editar_proveedor" data-user-id="<?= $proveedor['id_proveedor'] ?>"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm btn-toggle-status" data-user-id="<?= $proveedor['id_proveedor'] ?>" data-status="2"><i class="fas fa-ban"></i></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay proveedores registrados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>


<!-- Modal de Editar Proveedor -->
<div class="modal fade" id="editar_proveedor" tabindex="-1" aria-labelledby="editarProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarProveedorLabel">Editar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_editar_proveedor" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_primer_apellido" class="form-label">Primer apellido</label>
                                <input type="text" class="form-control" id="edit_primer_apellido" name="primer_apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_segundo_apellido" class="form-label">Segundo apellido</label>
                                <input type="text" class="form-control" id="edit_segundo_apellido" name="segundo_apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="edit_telefono" name="telefono" required>
                                <div id="telefonoEditError" class="error-message" style="color: red;"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_rfc" class="form-label">RFC</label>
                                <input type="text" class="form-control" id="edit_rfc" name="rfc" required>
                                <div id="rfcEditError" class="error-message" style="color: red;"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_calle" class="form-label">Calle</label>
                                <input type="text" class="form-control" id="edit_calle" name="calle" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="edit_numero" name="numero" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_codigo_postal" class="form-label">Código postal</label>
                                <input type="text" class="form-control" id="edit_codigo_postal" name="codigo_postal" required>
                                <div id="codigoPostalEditError" class="error-message text-danger" style="color: red;" ></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_cruzamientos" class="form-label">Cruzamientos</label>
                                <input type="text" class="form-control" id="edit_cruzamientos" name="cruzamientos" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_colonia" class="form-label">Colonia</label>
                                <input type="text" class="form-control" id="edit_colonia" name="colonia" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_pais" class="form-label">País</label>
                                <select class="form-select" id="edit_pais" name="pais" required></select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_estado" class="form-label">Estado</label>
                                <select class="form-select" id="edit_estado" name="estado" required></select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_ciudad" class="form-label">Ciudad</label>
                                <select class="form-select" id="edit_ciudad" name="ciudad" required></select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="edit_foto" name="foto">
                                <img id="edit_foto_preview" src="../img/0.jpg" alt="Foto del Proveedor" style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px;">
                                <input type="hidden" id="existing_foto" name="existing_foto">
                            </div>
                            <div class="mb-3">
                                <label for="edit_marcas" class="form-label">Seleccione marcas:</label>
                                <div id="edit_marcas"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="guardar_cambios">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Habilitar Proveedor -->
<div class="modal fade" id="habilitar_proveedor" tabindex="-1" aria-labelledby="habilitarProveedorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="habilitarProveedorLabel">Habilitar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_habilitar_proveedor">
                    <div class="mb-3">
                        <label for="select_proveedor" class="form-label">Seleccione un proveedor</label>
                        <select class="form-select" id="select_proveedor" name="proveedor"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                        <button type="button" class="btn btn-primary" id="habilitar_btn">Habilitar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar detalles del proveedor -->
<div class="modal fade" id="detalle_proveedor" tabindex="-1" aria-labelledby="detalleProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleProveedorLabel">Detalles del Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleProveedorContenido">
                <!-- Contenido de los detalles del proveedor se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Agregar Proveedor -->
<div class="modal fade" id="agregar_proveedor" tabindex="-1" aria-labelledby="agregarProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarProveedorLabel">Registrar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_agregar_proveedor" action="Adm_index_Proveedores.php?m=add_proveedor" method="post" enctype="multipart/form-data" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="primer_apellido" class="form-label">Primer apellido</label>
                                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="segundo_apellido" class="form-label">Segundo apellido</label>
                                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                                <div id="telefonoError" class="error-message" style="color: red;"></div>
                            </div>
                            <div class="mb-3">
                                <label for="rfc" class="form-label">RFC</label>
                                <input type="text" class="form-control" id="rfc" name="rfc" required>
                                <div id="rfcError" class="error-message" style="color: red;"></div>
                            </div>
                            <div id="error_message" class="alert alert-danger" style="display:none;"></div>
                            <div class="mb-3">
                                <label for="calle" class="form-label">Calle</label>
                                <input type="text" class="form-control" id="calle" name="calle" required>
                            </div>
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero" required>
                            </div>
                            <div class="mb-3">
                                <label for="codigo_postal" class="form-label">Código postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                                <div id="codigoPostalError" class="error-message text-danger" style="color: red;"></div>
                            </div>
                            <div class="mb-3">
                                <label for="cruzamientos" class="form-label">Cruzamientos</label>
                                <input type="text" class="form-control" id="cruzamientos" name="cruzamientos" required>
                            </div>
                            <div class="mb-3">
                                <label for="colonia" class="form-label">Colonia</label>
                                <input type="text" class="form-control" id="colonia" name="colonia" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pais" class="form-label">País</label>
                                <select class="form-select" id="pais" name="pais" required></select>
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required></select>
                            </div>
                            <div class="mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <select class="form-select" id="ciudad" name="ciudad" required></select>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto">
                                <img id="foto_preview" src="../img/0.jpg" alt="Foto del Proveedor" style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px;">
                            </div>
                            <div class="mb-3">
                                <label for="marcas" class="form-label">Seleccione marcas:</label>
                                <div id="marcas"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="submit_button" disabled>Registrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('rfc').addEventListener('input', function() {
    const rfc = this.value;
    if (rfc) {
        fetch(`../controller/Adm_Proveedores_controller.php?action=check_rfc&rfc=${rfc}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('error_message').textContent = 'El RFC ya está en uso por otro proveedor.';
                    document.getElementById('error_message').style.display = 'block';
                    document.getElementById('submit_button').disabled = true;
                } else {
                    document.getElementById('error_message').style.display = 'none';
                    document.getElementById('submit_button').disabled = false;
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('error_message').style.display = 'none';
        document.getElementById('submit_button').disabled = false;
    }
});
</script>


