<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style_login.css') }}">
    <link rel="icon" href="{{ asset('img/logo_azul.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.min.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <title>Login | Telecommunity</title>
</head>
<body>
    <div class="login-container">
        <div class="text-center"></div>
        <div class="login-box">
            <div class="login-header">
                <img src="{{ asset('img/logo_azul.jpg') }}" alt="Telecommunity Logo" class="logo circular-logo">
                <h1 class="h4 mb-4">¡Bienvenido a Telecommunity!</h1>
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-box">
                    <input type="text" class="input-field" name="username" placeholder="Usuario" autocomplete="off" required>
                </div>
                
                <div class="input-box">
                    <input type="password" class="input-field" name="password" placeholder="Contraseña" autocomplete="off" required>
                </div>
            
                {{-- Aquí es donde agregas el bloque para mostrar errores --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
            
                <div class="input-submit">
                    <button class="submit-btn" type="submit" id="submit">Entrar</button>
                </div>
                <div class="sign-up-link">
                    <p>¿No tienes una cuenta? <a href="#" data-toggle="modal" data-target="#agregar_cliente">Regístrate</a></p>
                </div>
            </form>
            
            
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
                    <form id="form_agregar_cliente" action="{{ route('register') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del cliente" pattern="[A-Za-z]+" title="El nombre solo puede contener letras" required tabindex="1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="primer-apellido" class="form-label">Primer apellido:</label>
                                <input type="text" class="form-control" id="primer-apellido" name="primer_apellido" placeholder="Ingrese el primer apellido" pattern="[A-Za-z]+" title="El primer apellido solo puede contener letras" required tabindex="2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="segundo-apellido" class="form-label">Segundo apellido:</label>
                                <input type="text" class="form-control" id="segundo-apellido" name="segundo_apellido" placeholder="Ingrese el segundo apellido" pattern="[A-Za-z]+" title="El segundo apellido solo puede contener letras" required tabindex="3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Número de teléfono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese el número de teléfono" pattern="\d{10}" title="El teléfono debe contener exactamente 10 dígitos" required tabindex="4">
                                <div class="invalid-feedback" id="error-telefono"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">Correo electrónico:</label>
                                <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese el correo electrónico" required tabindex="5">
                                <div class="invalid-feedback" id="error-correo"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Nombre de usuario:</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese el nombre de usuario" required tabindex="6">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contraseña" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Ingrese una contraseña" required tabindex="7">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirmar-contraseña" class="form-label">Confirmar contraseña:</label>
                                <input type="password" class="form-control" id="confirmar-contraseña" name="contraseña_confirmation" placeholder="Confirme la contraseña" required tabindex="8">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="foto" class="form-label">Foto:</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required tabindex="9">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="id_ciudad" class="form-label">Ciudad:</label>
                                <select class="form-control" id="id_ciudad" name="id_ciudad" required tabindex="10">
                                    <option value="">Selecciona una ciudad</option>
                                    <!-- Opciones de ciudades -->
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>