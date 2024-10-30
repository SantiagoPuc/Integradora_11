<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
<!-- Tus scripts personalizados -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>




<main class="mt-3">
    <div class="card">
        <div class="card-header">
            <h2 class="h5 mb-0">Lista de Clientes</h2>
        </div>
        <div class="card-body">
            <div class="d-flex mb-3">
                <input type="text" id="buscarCliente" class="form-control me-2" placeholder="Buscar...">
                <button class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#agregar_cliente">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="list-group" id="listaClientes">
                <?php if (isset($clientes) && is_array($clientes)): ?>
                    <?php foreach($clientes as $cliente): ?>
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <?php $fotoPath = $cliente['foto'] ? "../uploads/" . htmlspecialchars($cliente['foto']) : "../img/0.jpg"; ?>
                                <img src="<?= $fotoPath ?>" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($cliente['nombre']) ?></h5>
                                    <p class="mb-1">ID: <?= htmlspecialchars($cliente['id_cliente']) ?></p>
                                    <p class="mb-1">Correo: <?= htmlspecialchars($cliente['correo']) ?></p>
                                    <p class="mb-1">Teléfono: <?= htmlspecialchars($cliente['telefono']) ?></p>
                                    <p class="mb-1">Dirección: <?= htmlspecialchars($cliente['calle'] . ' ' . $cliente['numero'] . ', ' . $cliente['colonia'] . ', ' . $cliente['Ciudad'] . ', ' . $cliente['Estado'] . ', ' . $cliente['Pais']) ?></p>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1 btn-view-details" data-user-id="<?= $cliente['id_cliente'] ?>"><i class="fas fa-search"></i></button>
                                <button class="btn btn-outline-warning btn-sm btn-edit-user" data-bs-toggle="modal" data-bs-target="#editar_cliente" data-user-id="<?= $cliente['id_cliente'] ?>"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm btn-delete-user" data-user-id="<?= $cliente['id_cliente'] ?>"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay clientes registrados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>


    
<!-- Modal de Editar Cliente -->
<div class="modal fade" id="editar_cliente" tabindex="-1" aria-labelledby="editarClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarClienteLabel">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_editar_cliente" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id" value="">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre">
                    </div>
                    <div class="mb-3">
                        <label for="edit_primer_apellido" class="form-label">Primer apellido</label>
                        <input type="text" class="form-control" id="edit_primer_apellido" name="primer_apellido">
                    </div>
                    <div class="mb-3">
                        <label for="edit_segundo_apellido" class="form-label">Segundo apellido</label>
                        <input type="text" class="form-control" id="edit_segundo_apellido" name="segundo_apellido">
                    </div>
                    <div class="mb-3">
                        <label for="edit_telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="edit_telefono" name="telefono">
                        <div class="invalid-feedback" id="error-edit-telefono"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_correo" class="form-label">Correo electrónico</label>
                        <input type="text" class="form-control" id="edit_correo" name="correo">
                        <div class="invalid-feedback" id="error-edit-correo"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_calle" class="form-label">Calle</label>
                        <input type="text" class="form-control" id="edit_calle" name="calle">
                    </div>
                    <div class="mb-3">
                        <label for="edit_numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="edit_numero" name="numero">
                    </div>
                    <div class="mb-3">
                        <label for="edit_codigo_postal" class="form-label">Código postal</label>
                        <input type="text" class="form-control" id="edit_codigo_postal" name="codigo_postal">
                        <div class="invalid-feedback" id="error-edit-codigo-postal"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pais" class="form-label">País</label>
                        <select class="form-select" id="edit_pais" name="pais"></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <select class="form-select" id="edit_estado" name="estado"></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_ciudad" class="form-label">Ciudad</label>
                        <select class="form-select" id="edit_ciudad" name="ciudad"></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cruzamientos" class="form-label">Cruzamientos</label>
                        <input type="text" class="form-control" id="edit_cruzamientos" name="cruzamientos">
                    </div>
                    <div class="mb-3">
                        <label for="edit_colonia" class="form-label">Colonia</label>
                        <input type="text" class="form-control" id="edit_colonia" name="colonia">
                    </div>
                    <div class="mb-3">
                        <label for="edit_foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="edit_foto" name="foto">
                        <img id="edit_foto_preview" src="../img/0.jpg" alt="Foto del Cliente" style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px;">
                        <input type="hidden" id="existing_foto" name="existing_foto">
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



<!-- Modal para mostrar los detalles del cliente -->
<div class="modal fade" id="detalle_cliente" tabindex="-1" aria-labelledby="detalleClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleClienteLabel">Detalles del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detalleClienteContenido" class="container">
                    <!-- El contenido del cliente se cargará aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal de Eliminar Cliente -->
<div class="modal fade" id="eliminar_cliente" tabindex="-1" aria-labelledby="eliminarClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarClienteLabel">Eliminar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este cliente?
                <form id="form_eliminar_cliente">
                    <input type="hidden" id="delete_id" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmar_eliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Agregar Cliente -->
<div class="modal fade" id="agregar_cliente" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registrar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    
                </button>
            </div>
            <div class="modal-body">
                <form id="form_agregar_cliente" action="Adm_index_Clientes.php?m=add_cliente_login" method="post" enctype="multipart/form-data" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id" class="form-label">ID:</label>
                            <input type="text" class="form-control" id="id" name="id" placeholder="Ingrese el ID" readonly tabindex="1">
                        </div>
                        <div class="col-md-6 text-center mb-3">
                            <img src="../img/User.JPG" alt="Cliente" class="rounded-circle" width="100" tabindex="-1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del cliente" pattern="[A-Za-z]+" title="El nombre solo puede contener letras" required tabindex="2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="primer-apellido" class="form-label">Primer apellido:</label>
                            <input type="text" class="form-control" id="primer-apellido" name="primer_apellido" placeholder="Ingrese el primer apellido" pattern="[A-Za-z]+" title="El primer apellido solo puede contener letras" required tabindex="3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="segundo-apellido" class="form-label">Segundo apellido:</label>
                            <input type="text" class="form-control" id="segundo-apellido" name="segundo_apellido" placeholder="Ingrese el segundo apellido" pattern="[A-Za-z]+" title="El segundo apellido solo puede contener letras" required tabindex="4">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Número de teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese el número de teléfono" pattern="\d{10}" title="El teléfono debe contener exactamente 10 dígitos" required tabindex="5">
                            <div class="invalid-feedback" id="error-telefono"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo electrónico:</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese el correo electrónico" required tabindex="6">
                            <div class="invalid-feedback" id="error-correo"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="calle" class="form-label">Calle:</label>
                            <input type="text" class="form-control" id="calle" name="calle" placeholder="Ingrese la calle" required tabindex="7">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero" class="form-label">Número:</label>
                            <input type="text" class="form-control" id="numero" name="numero" placeholder="Ingrese el número" required tabindex="8">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="codigo-postal" class="form-label">Código postal:</label>
                            <input type="text" class="form-control" id="codigo-postal" name="codigo_postal" placeholder="Ingrese el código postal" pattern="\d{5}" title="El código postal debe contener exactamente 5 dígitos" required tabindex="9">
                            <div class="invalid-feedback" id="error-codigo-postal"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pais" class="form-label">País:</label>
                            <select class="form-control" id="pais" name="pais" required tabindex="10">
                                <option value="">Selecciona un país</option>
                                <?php foreach ($paises as $pais): ?>
                                    <option value="<?= $pais['ID_pais']; ?>"><?= $pais['Nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado:</label>
                            <select class="form-control" id="estado" name="estado" required tabindex="11">
                                <option value="">Selecciona un estado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ciudad" class="form-label">Ciudad:</label>
                            <select class="form-control" id="ciudad" name="ciudad" required tabindex="12">
                                <option value="">Selecciona una ciudad</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cruzamientos" class="form-label">Cruzamientos:</label>
                            <input type="text" class="form-control" id="cruzamientos" name="cruzamientos" placeholder="Ingrese los cruzamientos" required tabindex="13">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="colonia" class="form-label">Colonia:</label>
                            <input type="text" class="form-control" id="colonia" name="colonia" placeholder="Ingrese la colonia" required tabindex="14">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto:</label>
                            <input type="file" class="form-control" id="foto" name="foto" tabindex="15">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="passwordd_C" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="passwordd_C" name="passwordd_C" placeholder="Ingrese la contraseña" required tabindex="14">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmarcontraseña" class="form-label">Confirmar contraseña:</label>
                            <input type="password" class="form-control" id="confirmarcontraseña" name="confirmarcontraseña" placeholder="Confirme la contraseña" required tabindex="15">
                            <div class="invalid-feedback" id="error-confirmarcontraseña"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="submit_button" disabled tabindex="16">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" tabindex="17">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>








<!-- Modal de Eliminar Cliente -->
<div class="modal fade" id="eliminar_cliente" tabindex="-1" aria-labelledby="eliminarClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarClienteLabel">Eliminar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este cliente?
                <form id="form_eliminar_cliente">
                    <input type="hidden" id="delete_id" name="id_cliente">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmar_eliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>



 


