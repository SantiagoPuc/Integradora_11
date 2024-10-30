
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeleCommunity</title>
    
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-none d-md-flex flex-column">
        <img src="{{ asset('img/logoblanco.png') }}" alt="Logo">
        <div class="section-title">Gestión</div>
        <a href="{{ asset('view/Adm_index.php') }}"><i class="fas fa-user"></i> Usuarios</a>
        <a href="{{ asset('view/Adm_index_Productos.php') }}"><i class="fas fa-box"></i> Productos</a>
        <a href="{{ asset('view/Adm_index_proveedores.php') }}"><i class="fas fa-truck"></i> Proveedores</a>
        <a href="{{ asset('view/Adm_index_Clientes.php') }}"><i class="fas fa-user-tie"></i> Clientes</a>
        <div class="section-title">Operaciones</div>
        <a href="{{ asset('view/Adm_index_compra.php') }}"><i class="fas fa-shopping-cart"></i> Compras</a>
        <a href="{{ asset('view/Adm_index_venta.php') }}"><i class="fas fa-dollar-sign"></i> Ventas</a>
        <div class="section-title">Reportes</div>
        <a href="{{ asset('view/Adm_index_reporte.php') }}"><i class="fas fa-chart-line"></i> Reportes</a>
        <a href="{{ asset('controller/login_controller.php?action=logout') }}" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Topbar // Información de Sesión -->
    <div class="topbar">
        <div class="user-info dropdown">
            <?php
            $name = 'Usuario';
            $photo = asset('img/0.jpg'); // Default photo
            if (isset($_SESSION['user'])) {
                $name = $_SESSION['user']['nombre'] . ' ' . $_SESSION['user']['apellido_1'];
                $photo = asset('uploads/usuariosimg/' . $_SESSION['user']['foto']);
            } elseif (isset($_SESSION['client'])) {
                $name = $_SESSION['client']['nombre'] . ' ' . $_SESSION['client']['apellido_1'];
                $photo = asset('uploads/' . $_SESSION['client']['foto']);
            }
            ?>
            <img src="<?= $photo ?>" alt="Profile Image" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
            <span class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $name ?></span>
            <div class="dropdown-menu" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ asset('controller/login_controller.php?action=logout') }}">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="color: white;">Panel de Administrador</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="modal" data-bs-target="#menuModal">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <!-- Modal for Mobile Menu -->
        <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="menuModalLabel">Menú</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="section-title">Gestión</div>
                        <ul>
                            <li><a href="{{ asset('view/Adm_index.php') }}"><i class="fas fa-users"></i> Usuarios</a></li>
                            <li><a href="{{ asset('view/Adm_index_Productos.php') }}"><i class="fas fa-box"></i> Productos</a></li>
                            <li><a href="{{ asset('view/Adm_index_proveedores.php') }}"><i class="fas fa-truck"></i> Proveedores</a></li>
                            <li><a href="{{ asset('view/Adm_index_Clientes.php') }}"><i class="fas fa-user-tie"></i> Clientes</a></li>
                        </ul>
                        <div class="section-title">Operaciones</div>
                        <ul>
                            <li><a href="{{ asset('view/Adm_index_compra.php') }}"><i class="fas fa-shopping-cart"></i> Compras</a></li>
                            <li><a href="{{ asset('view/Adm_index_venta.php') }}"><i class="fas fa-dollar-sign"></i> Ventas</a></li>
                        </ul>
                        <div class="section-title">Reportes</div>
                        <ul>
                            <li><a href="{{ asset('view/Adm_index_reporte.php') }}"><i class="fas fa-chart-line"></i> Reportes</a></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ asset('controller/login_controller.php?action=logout') }}" class="btn btn-danger">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aquí va el contenido de la página -->
        @yield('content')
    </div>

</body>
</html>