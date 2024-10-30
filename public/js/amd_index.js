$(document).ready(function () {
    loadUsers();

    function loadUsers() {
        $.ajax({
            url: '/api/usuarios',
            type: 'GET',
            success: function (response) {
                $('#userList').empty();
                response.forEach(usuario => {
                    $('#userList').append(`
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <img src="${usuario.foto ? '/uploads/usuariosimg/' + usuario.foto : '/img/0.jpg'}" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
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
                        </div>
                    `);
                });
            },
            error: function (xhr) {
                alert('Error ' + xhr.status + ': ' + xhr.statusText);
            }
        });
    }

    // Manejo del formulario para agregar usuario
    $('#addUserForm').on('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert('Usuario agregado exitosamente');
                loadUsers();
                $('#agregar_usuario').modal('hide');
            },
            error: function (xhr) {
                alert('Error al agregar usuario: ' + xhr.responseText);
            }
        });
    });

    // Manejo del formulario para editar usuario
    $('#editUserForm').on('submit', function (event) {
        event.preventDefault();

        let userId = $('#editUserId').val();
        let formData = new FormData(this);

        $.ajax({
            url: '/api/usuarios/' + userId,
            type: 'PUT',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                alert('Usuario actualizado exitosamente');
                loadUsers();
                $('#editar_usuario').modal('hide');
            },
            error: function (xhr) {
                alert('Error al actualizar usuario: ' + xhr.responseText);
            }
        });
    });

    // Llenar los campos del formulario de edición
    $(document).on('click', '.btn-edit-user', function () {
        let userId = $(this).data('user-id');

        $.ajax({
            url: '/api/usuarios/' + userId,
            type: 'GET',
            success: function (usuario) {
                $('#editUserId').val(usuario.id);
                $('#nombre_edit').val(usuario.nombre);
                $('#apellido_1_edit').val(usuario.apellido_1);
                $('#apellido_2_edit').val(usuario.apellido_2);
                $('#telefono_edit').val(usuario.telefono);
                $('#correo_edit').val(usuario.correo);
                $('#username_edit').val(usuario.username);
                $('#existing_foto').val(usuario.foto);
                $('#edit_foto_preview').attr('src', usuario.foto ? '/uploads/usuariosimg/' + usuario.foto : '/img/0.jpg');
                $('#id_tipo_edit').val(usuario.id_tipo);
            },
            error: function (xhr) {
                alert('Error al cargar datos del usuario: ' + xhr.responseText);
            }
        });
    });

    // Manejo de eliminación de usuario
    $(document).on('click', '.btn-delete-user', function () {
        let userId = $(this).data('user-id');
        $('#deleteUserId').val(userId);
        $('#confirmDeleteModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function () {
        let userId = $('#deleteUserId').val();

        $.ajax({
            url: '/api/usuarios/' + userId,
            type: 'DELETE',
            success: function (response) {
                alert('Usuario eliminado exitosamente');
                loadUsers();
                $('#confirmDeleteModal').modal('hide');
            },
            error: function (xhr) {
                alert('Error al eliminar usuario: ' + xhr.responseText);
            }
        });
    });

    // Manejo del botón de búsqueda
    $('#searchUserButton').on('click', function () {
        let query = $('#searchUserInput').val();
        $.ajax({
            url: '/api/usuarios/search?query=' + query,
            type: 'GET',
            success: function (response) {
                $('#userList').empty();
                response.forEach(usuario => {
                    $('#userList').append(`
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <img src="${usuario.foto ? '/uploads/usuariosimg/' + usuario.foto : '/img/0.jpg'}" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
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
                        </div>
                    `);
                });
            },
            error: function (xhr) {
                alert('Error al buscar usuarios: ' + xhr.responseText);
            }
        });
    });
});
