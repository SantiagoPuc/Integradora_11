<?php
    // Evitar el almacenamiento en caché
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telecommunity Login</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="startup icon" href="../img/logo_azul.jpg">
    <link rel="stylesheet" href="../css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
<script src="../js/scriptLogin1.js"></script>
<script src="../js/scriptLogin2.js"></script>
<script src="../js/bootstrap.min.js"></script>

    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .modal-header .btn-close {
        background-color: #ffffff;
        border: none;
        font-size: 1.2rem;
        position: absolute;
        right: 1rem;
        top: 1rem;
        }

        .form-control-file {
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
        }

        .background {
            background-image: url("../img/conexiones.jpeg");
            filter: blur(8px) brightness(0.5); /* Imagen más oscura */
            -webkit-filter: blur(8px) brightness(0.5); /* Imagen más oscura */
            height: 100%; 
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            width: 100%;
            z-index: -1;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            z-index: 1;
            position: relative;
        }

        .login-card {
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            color: #FFFFFF;
            position: relative;
        }

        .login-card .logo {
            width: 100px; 
            height: 100px;
        }

        .modal-header .logo {
            width: 60px; 
            height: 60px; 
            margin-right: 10px;
        }

        .login-card label {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .login-card input::placeholder {
            color: #ffffff; /* Color blanco para el placeholder */
            font-weight: bold;
            font-size: 1rem;
        }

        .login-card .form-control {
            background-color: rgba(255, 255, 255, 0.2); /* Transparencia en las cajas de texto */
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .login-card .form-control:focus {
            background-color: rgba(255, 255, 255, 0.3); /* Más oscura al hacer foco */
            border-color: rgba(255, 255, 255, 0.7);
        }

        .error {
            color: red;
            font-size: 0.875rem;
        }

        .toggle-password {
            cursor: pointer;
            margin-left: 10px;
            font-size: 1.25rem;
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group .input-group-text {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: #fff; 
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .input-group .input-group-text i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .login-card::before {
            content: "";
            background-image: url("../img/nubes.jpeg"); 
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.5; /* Imagen del login más oscura */
            z-index: -1;
            border-radius: 8px;
        }

        .btn-link {
            color: #fff;
            border: 1px solid #fff;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .btn-link:hover {
            color: #fff;
            text-decoration: none;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .btn-full-width {
            width: 100%;
        }

        .top-left-button {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }

    </style>
</head>
<body>
    <div class="background"></div>
    <div class="top-left-button">
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Regresar</button>
    </div>
    <div class="login-container">
        <div class="login-card">
            <div class="text-center">
                <img src="../img/logoblanco.png" alt="Telecommunity Logo" class="logo">
                <h1 class="h4 mb-4">¡Bienvenido a Telecommunity!</h1>
            </div>
            <form id="loginForm" method="POST" action="../controller/login_controller.php?action=login">
                <div class="form-group">
                    <label for="usuario">Usuario</label><br>
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña</label><br>
                    <div class="input-group">
                        <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Contraseña" required>
                        <span class="input-group-text" onclick="togglePassword('contraseña')">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </span>
                    </div>
                </div><br>
                <button type="submit" class="btn btn-primary btn-block btn-full-width">Iniciar Sesión</button><br><br>
                <button type="button" class="btn btn-link btn-block btn-full-width" data-toggle="modal" data-target="#agregar_cliente">Registrarse</button><br><br>
                <button type="button" class="btn btn-link btn-block btn-full-width" data-toggle="modal" data-target="#recuperar_contraseña">Recuperar Contraseña</button><br>
            </form>
        </div>
    </div>
    

<!-- Modal Recuperar Contraseña -->
<div class="modal fade" id="recuperar_contraseña" tabindex="-1" role="dialog" aria-labelledby="recuperarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recuperarModalLabel">Recuperar Contraseña</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_recuperar_contraseña" action="../controller/login_controller.php?action=recuperar_contraseña" method="post" novalidate>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="recuperar_correo" class="form-label">Correo electrónico:</label>
                            <input type="email" class="form-control" id="recuperar_correo" name="recuperar_correo" placeholder="Ingrese su correo electrónico" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nueva_contraseña" class="form-label">Nueva Contraseña:</label>
                            <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" placeholder="Ingrese la nueva contraseña" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmar_nueva_contraseña" class="form-label">Confirmar Nueva Contraseña:</label>
                            <input type="password" class="form-control" id="confirmar_nueva_contraseña" name="confirmar_nueva_contraseña" placeholder="Confirme la nueva contraseña" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="submit_recuperar_button">Guardar Contraseña</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal Agregar Cliente -->
<div class="modal fade" id="agregar_cliente" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registro</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_agregar_cliente" action="../controller/login_controller.php?action=register" method="post" enctype="multipart/form-data" novalidate>
                    <?php
                    if (isset($_GET['error'])) {
                        $error = $_GET['error'];
                        $errorMessage = '';
                        if ($error === 'id_exists') {
                            $errorMessage = 'El número de teléfono y el código postal ya han sido usados por otro usuario.';
                        } elseif ($error === 'email_exists') {
                            $errorMessage = 'Parece que tu correo ya ha sido utilizado.';
                        } elseif ($error === 'register_failed') {
                            $errorMessage = 'Ha ocurrido un error al registrar el usuario. Inténtalo de nuevo.';
                        }
                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                    }
                    ?>    
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
                            <input type="file" class="form-control-file" id="foto" name="foto" tabindex="15">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" tabindex="17">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
    
    <script src="../js/scriptLogin3.js"></script>
    <script src="../js/scriptLogin4.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/custom_login.js"></script>



</body>
</html>
