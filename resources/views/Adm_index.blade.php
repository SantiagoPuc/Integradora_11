@extends('layout')

@section('content')

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
                @forelse ($usuarios as $usuario)
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            @php
                                $fotoPath = $usuario->foto ? asset('uploads/usuariosimg/' . $usuario->foto) : asset('img/0.jpg');
                            @endphp
                            <img src="{{ $fotoPath }}" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                            <div>
                                <h5 class="mb-1">{{ $usuario->nombre }} {{ $usuario->apellido_1 }}</h5>
                                <p class="mb-1">ID: {{ $usuario->id }}</p>
                                <p class="mb-1">Correo: {{ $usuario->correo }}</p>
                                <p class="mb-1">Teléfono: {{ $usuario->telefono }}</p>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary btn-sm me-1 btn-view-details" data-user-id="{{ $usuario->id }}"><i class="fas fa-search"></i></button>
                            <button class="btn btn-outline-warning btn-sm btn-edit-user" data-bs-toggle="modal" data-bs-target="#editar_usuario" data-user-id="{{ $usuario->id }}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-outline-danger btn-sm btn-delete-user" data-user-id="{{ $usuario->id }}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                @empty
                    <p>No hay usuarios registrados.</p>
                @endforelse
            </div>
        </div>
    </div>
</main>

<!-- Modal Agregar Usuario -->
<div class="modal fade" id="agregar_usuario" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('api/usuarios') }}" method="POST" enctype="multipart/form-data" id="addUserForm">
                    @csrf
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
                    </div>
                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha de ingreso:</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo electrónico:</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
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
                <form id="editUserForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                    </div>
                    <div class="form-group">
                        <label for="correo_edit">Correo electrónico:</label>
                        <input type="email" class="form-control" id="correo_edit" name="correo" required>
                    </div>
                    <div class="form-group">
                        <label for="username_edit">Usuario:</label>
                        <input type="text" class="form-control" id="username_edit" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordd_edit">Contraseña:</label>
                        <input type="password" class="form-control" id="passwordd_edit" name="passwordd">
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    loadUsers();

    // Buscar usuario
    $('#searchUserButton').click(function () {
        const query = $('#searchUserInput').val();
        loadUsers(query);
    });

    // Agregar usuario
    $('#addUserForm').submit(function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                loadUsers();
                $('#agregar_usuario').modal('hide');
                $('#addUserForm')[0].reset();
            },
            error: function (err) {
                console.error('Error al agregar usuario:', err);
            }
        });
    });

    // Cargar usuarios
    function loadUsers(query = '') {
        $.ajax({
            url: 'api/usuarios',
            method: 'GET',
            data: { search: query },
            success: function(data) {
                $('#userList').empty();
                data.forEach(usuario => {
                    $('#userList').append(`
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <img src="${usuario.foto ? 'uploads/usuariosimg/' + usuario.foto : '../img/0.jpg'}" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
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
            error: function(err) {
                console.error('Error al cargar usuarios:', err);
            }
        });
    }

    // Editar usuario
    $(document).on('click', '.btn-edit-user', function () {
        const userId = $(this).data('user-id');
        $.ajax({
            url: `api/usuarios/${userId}`,
            method: 'GET',
            success: function(data) {
                $('#editUserId').val(data.id);
                $('#nombre_edit').val(data.nombre);
                $('#apellido_1_edit').val(data.apellido_1);
                $('#apellido_2_edit').val(data.apellido_2);
                $('#telefono_edit').val(data.telefono);
                $('#correo_edit').val(data.correo);
                $('#username_edit').val(data.username);
                $('#existing_foto').val(data.foto);
                $('#edit_foto_preview').attr('src', data.foto ? 'uploads/usuariosimg/' + data.foto : '../img/0.jpg');
                $('#id_tipo_edit').val(data.id_tipo);
                $('#editUserForm').attr('action', `api/usuarios/${userId}`);
            },
            error: function(err) {
                console.error('Error al cargar usuario para editar:', err);
            }
        });
    });

    // Guardar cambios del usuario editado
    $('#editUserForm').submit(function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                loadUsers();
                $('#editar_usuario').modal('hide');
                $('#editUserForm')[0].reset();
            },
            error: function (err) {
                console.error('Error al editar usuario:', err);
            }
        });
    });

    // Eliminar usuario
    $(document).on('click', '.btn-delete-user', function () {
        const userId = $(this).data('user-id');
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            $.ajax({
                url: `api/usuarios/${userId}`,
                type: 'DELETE',
                success: function () {
                    loadUsers();
                },
                error: function (err) {
                    console.error('Error al eliminar usuario:', err);
                }
            });
        }
    });
});
</script>

@endsection
