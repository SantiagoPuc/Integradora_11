<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) && !isset($_SESSION['client'])) {
    header('Location: ../view/login_registro.php');
    exit();
}

// Evitar el almacenamiento en caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeleCommunity</title>
    
    
    <link rel="shortcut icon" href="../img/logo_azul.jpg">
    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Asegúrate de que esta es la primera referencia a jQuery -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    


    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    
    <!-- Hojas de estilo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/styles2.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/menubar.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">







    <script src="../js/custom_proveedores.js"></script>
    <script src="../js/custom.js"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const path = window.location.pathname;
            if (path.includes('Adm_Proveedores')) {
                const script = document.createElement('script');
                script.src = '../js/custom_proveedores.js';
                document.head.appendChild(script);
            } else if (path.includes('Adm_Clientes')) {
                const script = document.createElement('script');
                script.src = '../js/custom.js';
                document.head.appendChild(script);
            }
        });
    </script>


</head>
<body>
    <!-- Contenido de tu página -->
    

<head>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
    
    <!-- Sidebar -->
    <div class="sidebar d-none d-md-flex flex-column">
        <img src="../img/logoblanco.png" alt="Logo">
        <div class="section-title">Gestión</div>
        <a href="../view/Emp_index_Productos.php"><i class="fas fa-box"></i> Productos</a>
        <a href="../view/Emp_index_clientes.php"><i class="fas fa-user-tie"></i> Clientes</a>
        <div class="section-title">Operaciones</div>
        <a href="../view/Emp_index_venta.php"><i class="fas fa-dollar-sign"></i> Ventas</a>
        <a href="../controller/login_controller.php?action=logout" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Topbar // Información de Sesión -->
    <div class="topbar">
        <div class="user-info dropdown">
            <?php
            $name = 'Usuario';
            $photo = '../img/0.jpg'; // Default photo
            if (isset($_SESSION['user'])) {
                $name = $_SESSION['user']['nombre'] . ' ' . $_SESSION['user']['apellido_1'];
                $photo = '../uploads/usuariosimg/' . $_SESSION['user']['foto'];
            } elseif (isset($_SESSION['client'])) {
                $name = $_SESSION['client']['nombre'] . ' ' . $_SESSION['client']['apellido_1'];
                $photo = '../uploads/' . $_SESSION['client']['foto'];
            }
            ?>
            <img src="<?= $photo ?>" alt="Profile Image" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
            <span class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $name ?></span>
            <div class="dropdown-menu" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../controller/login_controller.php?action=logout">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="color: white;">Panel de Empleado</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="modal" data-bs-target="#menuModal">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    
        <!-- Modal for Mobile Menu  <script>
document.querySelector('.navbar-toggler').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('d-md-flex');
    document.querySelector('.sidebar').classList.toggle('d-none');
});

document.querySelector('.nav__menu').addEventListener('click', function() {
    document.querySelector('.dropdown').style.display = 'grid';
    document.querySelector('.nav__menu--second').style.display = 'block';
    this.style.display = 'none';
});

document.querySelector('.nav__menu--second').addEventListener('click', function() {
    document.querySelector('.dropdown').style.display = 'none';
    document.querySelector('.nav__menu').style.display = 'block';
    this.style.display = 'none';
});
</script>-->
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
                            <li><a href="../view/Emp_index_productos.php"><i class="fas fa-box"></i> Productos</a></li>
                            <li><a href="../view/Emp_index_clientes.php"><i class="fas fa-user-tie"></i> Clientes</a></li>
                        </ul>
                        <div class="section-title">Operaciones</div>
                        <ul>
                            <li><a href="../view/Emp_index_venta.php"><i class="fas fa-dollar-sign"></i> Ventas</a></li>
                        </ul>
                        <div class="logout">
                            <a href="../controller/login_controller.php?action=logout" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
       