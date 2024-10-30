

        <main class="mt-3">
    <div class="card">
        <div class="card-header">
            <h2 class="h5 mb-0">Lista de usuarios</h2>
        </div>
        <div class="card-body">
            <div class="d-flex mb-3">
                <input type="text" id="searchUserInput" class="form-control me-2" placeholder="Buscar...">
                <button class="btn btn-primary" id="searchUserButton">
                    <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#agregar_usuario">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="list-group" id="userList">
    <?php if (isset($usuarios) && is_array($usuarios)): ?>
        <?php foreach ($usuarios as $usuario): ?>
            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <?php $fotoPath = $usuario['foto'] ? "../uploads/usuariosimg/" . htmlspecialchars($usuario['foto']) : "../img/0.jpg"; ?>
                    <img src="<?= $fotoPath ?>" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <div>
                        <h5 class="mb-1"><?= htmlspecialchars($usuario['nombre']) . ' ' . htmlspecialchars($usuario['apellido_1']) ?></h5>
                        <p class="mb-1">ID: <?= htmlspecialchars($usuario['id']) ?></p>
                        <p class="mb-1">Correo: <?= htmlspecialchars($usuario['correo']) ?></p>
                        <p class="mb-1">Teléfono: <?= htmlspecialchars($usuario['telefono']) ?></p>
                    </div>
                </div>
                <div>
                    <button class="btn btn-outline-primary btn-sm me-1 btn-view-details" data-user-id="<?= $usuario['id'] ?>"><i class="fas fa-search"></i></button>
                    <button class="btn btn-outline-warning btn-sm btn-edit-user" data-bs-toggle="modal" data-bs-target="#editar_usuario" data-user-id="<?= $usuario['id'] ?>"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-outline-danger btn-sm btn-delete-user" data-user-id="<?= $usuario['id'] ?>"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay usuarios registrados.</p>
    <?php endif; ?>
</div>

        </div>
    </div>
</main>



    </div>

   <!-- Modal Agregar Usuario -->
<div class="modal fade" id="agregar_usuario" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="addUserForm" action="Adm_index.php?m=create" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <img src="../img/User.JPG" alt="Producto" class="rounded-circle" width="100">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_1">Primer apellido:</label>
                        <input type="text" class="form-control" id="apellido_1" name="apellido_1" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_2">Segundo apellido:</label>
                        <input type="text" class="form-control" id="apellido_2" name="apellido_2" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Número de teléfono:</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                        <div id="telefonoError" class="error-message" style="color: red;"></div>

                    </div>
                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha de ingreso:</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo electrónico:</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                        <div id="correoError" class="error-message" style="color: red;"></div>
                    </div>
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordd">Contraseña:</label>
                        <input type="password" class="form-control" id="passwordd" name="passwordd" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto:</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                    <div class="form-group">
                        <label for="id_tipo">Tipo de Usuario:</label>
                        <select class="form-control" id="id_tipo" name="id_tipo">
                            <option value="1">Administrador</option>
                            <option value="2">Empleado</option>
                        </select>
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



   <!-- Modal Editar Usuario -->
   <div class="modal fade" id="editar_usuario" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="Adm_index.php?action=update" enctype="multipart/form-data" method="POST">
                    <div class="text-center mb-4">
                        <img id="edit_foto_preview" src="../img/0.jpg" alt="Foto del usuario" class="rounded-circle" width="100">
                    </div>
                    <input type="hidden" id="editUserId" name="id">
                    <input type="hidden" id="existing_foto" name="existing_foto">
                    <div class="form-group">
                        <label for="nombre_edit">Nombre:</label>
                        <input type="text" class="form-control" id="nombre_edit" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_1_edit">Primer apellido:</label>
                        <input type="text" class="form-control" id="apellido_1_edit" name="apellido_1" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_2_edit">Segundo apellido:</label>
                        <input type="text" class="form-control" id="apellido_2_edit" name="apellido_2" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono_edit">Número de teléfono:</label>
                        <input type="text" class="form-control" id="telefono_edit" name="telefono" required>
                        <div id="telefonoError" class="error-message" style="color: red;"></div>
                    </div>
                    <div class="form-group">
                        <label for="correo_edit">Correo electrónico:</label>
                        <input type="email" class="form-control" id="correo_edit" name="correo" required>
                        <div id="correoError" class="error-message" style="color: red;"></div>
                    </div>
                    <div class="form-group">
                        <label for="username_edit">Usuario:</label>
                        <input type="text" class="form-control" id="username_edit" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordd_edit">Contraseña:</label>
                        <input type="password" class="form-control" id="passwordd_edit" name="passwordd" required>
                    </div>
                    <div class="form-group">
                        <label for="foto_edit">Foto:</label>
                        <input type="file" class="form-control" id="foto_edit" name="foto">
                    </div>
                    <div class="form-group">
                        <label for="id_tipo_edit">Tipo de Usuario:</label>
                        <select class="form-control" id="id_tipo_edit" name="id_tipo">
                            <option value="1">Administrador</option>
                            <option value="2">Empleado</option>
                        </select>
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


<!-- Modal Eliminar Usuario -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este usuario?
                <input type="hidden" id="deleteUserId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles Usuario -->
<div class="modal fade" id="detalles_usuario" tabindex="-1" aria-labelledby="detallesUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detallesUserModalLabel">Detalles del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="detalles_foto" src="../img/User.JPG" alt="Foto del usuario" class="rounded-circle" width="100">
                </div>
                <div class="form-group">
                    <label>Nombre:</label>
                    <p id="detalles_nombre"></p>
                </div>
                <div class="form-group">
                    <label>Primer apellido:</label>
                    <p id="detalles_apellido_1"></p>
                </div>
                <div class="form-group">
                    <label>Segundo apellido:</label>
                    <p id="detalles_apellido_2"></p>
                </div>
                <div class="form-group">
                    <label>Número de teléfono:</label>
                    <p id="detalles_telefono"></p>
                </div>
                <div class="form-group">
                    <label>Fecha de ingreso:</label>
                    <p id="detalles_fecha_ingreso"></p>
                </div>
                <div class="form-group">
                    <label>Correo electrónico:</label>
                    <p id="detalles_correo"></p>
                </div>
                <div class="form-group">
                    <label>Usuario:</label>
                    <p id="detalles_username"></p>
                </div>
                <div class="form-group">
                    <label>Tipo de Usuario:</label>
                    <p id="detalles_id_tipo"></p>
                </div>
            </div>
        </div>
    </div>
</div>




    <!-- Bootstrap JS and dependencies -->
    <script src="../js/scriptUsuarios1.js"></script>
    <script src="../js/scriptUsuarios2.js"></script>
    <script src="../js/scriptUsuarios3.js"></script>
    <script src="../js/custom_usuarios.js"></script>   
    <script>
        //AGREGAR USUARIO//
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('addUserForm').addEventListener('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                fetch('index.php?action=create', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Error al agregar usuario: " + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            
        });

        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            fetch('../view/Adm_index.php?action=create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Puedes cambiar esto para manejar la respuesta
                if (data.includes('success')) {
                    // Si la respuesta incluye 'success', recarga la lista de usuarios
                    $('#agregar_usuario').modal('hide');
                    window.location.reload(); // Recarga la página para ver los cambios
                } else {
                    alert('Error al agregar usuario');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        
    </script>

    <script>
        //EDITAR USUARIO
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-user').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');

            fetch(`Adm_index.php?action=get_user&id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('editUserId').value = data.id;
                        document.getElementById('nombre_edit').value = data.nombre;
                        document.getElementById('apellido_1_edit').value = data.apellido_1;
                        document.getElementById('apellido_2_edit').value = data.apellido_2;
                        document.getElementById('telefono_edit').value = data.telefono;
                        document.getElementById('correo_edit').value = data.correo;
                        document.getElementById('username_edit').value = data.username;
                        document.getElementById('passwordd_edit').value = data.passwordd;
                        document.getElementById('id_tipo_edit').value = data.id_tipo;
                        document.getElementById('existing_foto').value = data.foto;

                        if (data.foto) {
                            document.getElementById('edit_foto_preview').src = `../uploads/usuariosimg/${data.foto}`;
                        } else {
                            document.getElementById('edit_foto_preview').src = '../img/0.jpg';
                        }

                        $('#editar_usuario').modal('show');
                    } else {
                        alert('Error al cargar los datos del usuario');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    document.getElementById('editUserForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch('Adm_index.php?action=update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al actualizar el usuario: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

<script>
    //DETALLES USUARIOS//
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-view-details').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');

                fetch(`Adm_index.php?action=get_user&id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('detalles_nombre').innerText = data.nombre;
                            document.getElementById('detalles_apellido_1').innerText = data.apellido_1;
                            document.getElementById('detalles_apellido_2').innerText = data.apellido_2;
                            document.getElementById('detalles_telefono').innerText = data.telefono;
                            document.getElementById('detalles_fecha_ingreso').innerText = data.fecha_ingreso;
                            document.getElementById('detalles_correo').innerText = data.correo;
                            document.getElementById('detalles_username').innerText = data.username;
                            document.getElementById('detalles_id_tipo').innerText = data.id_tipo == 1 ? 'Administrador' : 'Empleado';

                            if (data.foto) {
                                document.getElementById('detalles_foto').src = `../uploads/usuariosimg/${data.foto}`;
                            } else {
                                document.getElementById('detalles_foto').src = '../img/0.jpg';
                            }

                            $('#detalles_usuario').modal('show');
                        } else {
                            alert('Error al cargar los datos del usuario');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>

<script>
    //ELIMINAR USUARIO//
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-user').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            document.getElementById('deleteUserId').value = userId;
            $('#confirmDeleteModal').modal('show');
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        const userId = document.getElementById('deleteUserId').value;

        fetch('Adm_index.php?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar el usuario: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

<script>
    //BUSCAR USUARIO//
    document.addEventListener('DOMContentLoaded', function () {
    // Logica de busqueda en tiempo real para usuarios
    const searchUserInput = document.getElementById('searchUserInput');
    const userList = document.getElementById('userList');

    searchUserInput.addEventListener('input', function (e) {
        const searchTerm = e.target.value.toLowerCase();

        fetch(`Adm_index.php?action=search_user&query=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    userList.innerHTML = '';
                    data.forEach(usuario => {
                        const fotoPath = usuario.foto ? `../uploads/usuariosimg/${usuario.foto}` : "../img/0.jpg";
                        const userItem = `
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div class="d-flex">
                                    <img src="${fotoPath}" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div>
                                        <h5 class="mb-1">${usuario.nombre} ${usuario.apellido_1}</h5>
                                        <p class="mb-1">ID: ${usuario.id}</p>
                                        <p class="mb-1">Correo: ${usuario.correo}</p>
                                        <p class="mb-1">Teléfono: ${usuario.telefono}</p>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm me-1 btn-view-details" data-user-id="${usuario.id}"><i class="fas fa-search"></i></button>
                                    <button class="btn btn-outline-warning btn-sm btn-edit-user" data-bs-toggle="modal" data-bs-target="#editar_usuario" data-user-id="${usuario.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-outline-danger btn-sm btn-delete-user" data-user-id="${usuario.id}"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>`;
                        userList.innerHTML += userItem;
                    });
                    // Reasignar event listeners después de actualizar la lista
                    assignEventListeners();
                } else {
                    userList.innerHTML = '<p>No se encontraron usuarios.</p>';
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Función para asignar los event listeners
    function assignEventListeners() {
        // Ver detalles del usuario
        document.querySelectorAll('.btn-view-details').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');

                fetch(`Adm_index.php?action=get_user&id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('detalles_nombre').innerText = data.nombre;
                            document.getElementById('detalles_apellido_1').innerText = data.apellido_1;
                            document.getElementById('detalles_apellido_2').innerText = data.apellido_2;
                            document.getElementById('detalles_telefono').innerText = data.telefono;
                            document.getElementById('detalles_fecha_ingreso').innerText = data.fecha_ingreso;
                            document.getElementById('detalles_correo').innerText = data.correo;
                            document.getElementById('detalles_username').innerText = data.username;
                            document.getElementById('detalles_id_tipo').innerText = data.id_tipo == 1 ? 'Administrador' : 'Empleado';

                            if (data.foto) {
                                document.getElementById('detalles_foto').src = `../uploads/usuariosimg/${data.foto}`;
                            } else {
                                document.getElementById('detalles_foto').src = '../img/User.JPG';
                            }

                            $('#detalles_usuario').modal('show');
                        } else {
                            alert('Error al cargar los datos del usuario');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Editar usuario
        document.querySelectorAll('.btn-edit-user').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');

                fetch(`Adm_index.php?action=get_user&id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('editUserId').value = data.id;
                            document.getElementById('nombre_edit').value = data.nombre;
                            document.getElementById('apellido_1_edit').value = data.apellido_1;
                            document.getElementById('apellido_2_edit').value = data.apellido_2;
                            document.getElementById('telefono_edit').value = data.telefono;
                            document.getElementById('correo_edit').value = data.correo;
                            document.getElementById('username_edit').value = data.username;
                            document.getElementById('passwordd_edit').value = data.passwordd;
                            document.getElementById('id_tipo_edit').value = data.id_tipo;
                            document.getElementById('existing_foto').value = data.foto;

                            if (data.foto) {
                                document.getElementById('edit_foto_preview').src = `../uploads/usuariosimg/${data.foto}`;
                            } 
                            $('#editar_usuario').modal('show');
                        } else {
                            alert('Error al cargar los datos del usuario');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // Eliminar usuario
        document.querySelectorAll('.btn-delete-user').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                document.getElementById('deleteUserId').value = userId;
                $('#confirmDeleteModal').modal('show');
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            const userId = document.getElementById('deleteUserId').value;

            fetch('Adm_index.php?action=delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${userId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al eliminar el usuario: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Inicialmente asignar event listeners
    assignEventListeners();
});

</script>

