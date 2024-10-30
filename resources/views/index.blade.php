<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Telecommunity</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('img/logo_azul.jpg') }}">

  <!-- Fonts -->
<!-- Preconnect to Google Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>

<!-- Custom CSS File -->
<link href="{{ asset('css/estiloIndex1.css') }}" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

<!-- Main CSS File -->
<link href="{{ asset('css/main.css') }}" rel="stylesheet">


  <script>
    function filtrarProductos(categoriaId) {
      $.ajax({
        url: './controller/index_controller.php',
        type: 'GET',
        data: {
          action: 'filtrarPorCategoria',
          categoriaId: categoriaId
        },
        success: function(response) {
          var productos = JSON.parse(response);
          var productHTML = '';

          productos.forEach(function(producto) {
            var imgSrc = producto.foto ? `./uploads/productosimg/${producto.foto}` : './img/producto-sin-imagen.jpg';
            productHTML += `
              <div class="product-item filter-${producto.categoria.toLowerCase()}">
                <img src="${imgSrc}" alt="${producto.nombre}" class="img-fluid">
                <h3>${producto.nombre}</h3>
                <p>$${parseFloat(producto.precio).toFixed(2)}</p>
                <button class="btn btn-primary" onclick="window.location.href='./view/login_registro.php'">Añadir al carrito</button>
              </div>`;
          });

          $('#product-list').html(productHTML);
          $('.isotope-container').isotope('arrange'); // Vuelve a arreglar los elementos
        }
      });
    }

    $(document).ready(function() {
      var $container = $('.isotope-container').isotope({
        itemSelector: '.product-item',
        layoutMode: 'fitRows'
      });

      $('.portfolio-filters li').on('click', function() {
        var filterValue = $(this).attr('data-filter');
        $container.isotope({ filter: filterValue });
      });
    });
  </script>


</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="./img/logo me.png" alt="">
        <h1 class="sitename">Telecommunity</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Inicio<br></a></li>
          <li><a href="#about">¿Quiénes Somos?</a></li>
          <li><a href="#services">Servicios</a></li>
          <li><a href="#portfolio">Ofertas</a></li>
          <li><a href="#team">Nuestro Equipo</a></li>
          <li><a href="#contact">Contacto</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted flex-md-shrink-0" href="{{ route('login') }}">Iniciar Sesión</a>

    </div>
  </header>

  <main class="main">

    <br><br><br>
    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section">

      <!-- Section Title -->

      <div class="container section-title" data-aos="fade-up">
        <h2>Compra ya</h2>
        <p>¡Dale un vistazo a nuestros productos en oferta!</p>
      </div><!-- End Section Title -->

      
      <div class="container product-list isotope-container" id="product-list">
        <?php if (isset($productos) && is_array($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 product-item filter-<?php echo strtolower($producto['categoria']); ?>">
                    <?php
                        $imgSrc = !empty($producto['foto']) ? "./uploads/productosimg/{$producto['foto']}" : "./img/producto-sin-imagen.jpg";
                    ?>
                    <img src="<?php echo $imgSrc; ?>" alt="<?php echo $producto['nombre']; ?>" class="img-fluid">
                    <h3><?php echo $producto['nombre']; ?></h3>
                    <p><?php echo '$' . number_format($producto['precio'], 2); ?></p>
                    <button class="btn btn-primary" onclick="window.location.href='./controller/login_controller.php?action=index'">Ir a la tienda</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
      </div>

      

    </section><!-- /Portfolio Section -->
    
    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h1 data-aos="fade-up">Ofrecemos soluciones de innovación para su negocio</h1>
            <p data-aos="fade-up" data-aos-delay="100">Somos un equipo de desarrolladores a su disposición</p>
            <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
              <a href="./view/login_registro.php" class="btn-get-started">Iniciar Sesión<i class="bi bi-arrow-right"></i></a>
              <a href="https://youtu.be/9pPn8_2EJoE" class="glightbox btn-watch-video d-flex align-items-center justify-content-center ms-0 ms-md-4 mt-4 mt-md-0"><i class="bi bi-play-circle"></i><span>Conoce un poco más</span></a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out">
            <img src="{{ asset('img/logo me.png') }}" class="img-fluid animated" alt="Logo">

          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up">
        <div class="row gx-0">

          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="content">
              <h3>¿Quienes Somos?</h3>
              <h2>Somos un grupo de desarrolladores que busca innovar la forma en la que realiza sus compras</h2>
              <p>
                Queremos brindarle a nuestros clientes una experiencia de compra fácil, intuitiva y satisfactoria
              </p>
              <div class="text-center text-lg-start">
                <a href="./view/login_registro.php" class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                  <span>Comienza</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
            <img src="./assets/img/about.jpg" class="img-fluid" alt="">
          </div>

        </div>
      </div>

    </section><!-- /About Section -->

    <!-- Values Section -->
    <section id="values" class="values section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Misión y Visión</h2>
        <p>¿Qué nos caracteriza?<br></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card">
              <img src="./assets/img/values-1.png" class="img-fluid" alt="">
              <h3>Empresa líder del mercado</h3>
              <p>Buscamos ser la empresa #! en el mercado para ello tenemos una clientela que nos respalda.</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card">
              <img src="./assets/img/values-2.png" class="img-fluid" alt="">
              <h3>Ética y Profesionalismo</h3>
              <p>Trabajamos apegándonos a la ética profesional, laborando con honestidad, capacidad y seguridad.</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card">
              <img src="./assets/img/values-3.png" class="img-fluid" alt="">
              <h3>Adaptación</h3>
              <p>Nos adaptamos a las necesidades de nuestros clientes, ofreciéndoles en todo momento un servicio de excelente calidad para su satisfacción.</p>
            </div>
          </div><!-- End Card Item -->

        </div>

      </div>

    </section><!-- /Values Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Servicios</h2>
        <p>Checa lo que le ofrecemos<br></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item item-cyan position-relative">
              <i class="bi bi-clipboard2-data icon"></i>
              <h3>Administración</h3>
              <p>Interfaces intuitivas dirigidas a usuarios para la administración de los elementos del negocio.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item item-orange position-relative">
              <i class="bi bi-person-check-fill icon"></i>
              <h3>Accesibilidad</h3>
              <p>Los clientes tienen facilidad para poder realizar sus compras desde cualquier lugar al poder crearse una cuenta ellos mismos.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item item-teal position-relative">
              <i class="bi bi-graph-up-arrow icon"></i>
              <h3>Análisis</h3>
              <p>Con la integración de los reportes y análisis, puedes obtener un panorama de tus ventas que ayuda al crecimiento del negocio.</p>
            </div>
          </div><!-- End Service Item -->

          

        </div>

      </div>

    </section><!-- /Services Section -->


    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Valoraciones</h2>
        <p>Comentarios de nuestros clientes<br></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 1
                }
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  La facilidad y accesibilidad de la parte de gestión de la tienda, me ha ayudado a poder administrar mejor mi negocio.
                </p>
                <div class="profile mt-auto">
                  <img src="./assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                  <h3>Jehu Sarmiento</h3>
                  <h4>Usuario</h4>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  La variedad de productos cada vez es mayor lo que me permite comprar exactamente lo que busco.
                </p>
                <div class="profile mt-auto">
                  <img src="./assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                  <h3>Naidelyn Pantoja</h3>
                  <h4>Cliente</h4>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  Ahora yo misma puedo crearme una cuenta y comprar en la tienda lo cual me ahorra el ir hasta la tienda física, bien por la mejora!
                </p>
                <div class="profile mt-auto">
                  <img src="./assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                  <h3>Juliana Calan</h3>
                  <h4>Cliente</h4>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  Gracias a los reportes que genera el apartado de administración, puedo tener un mejor panorama de en que productos invertir más para generar mejores ingresos.
                </p>
                <div class="profile mt-auto">
                  <img src="./assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                  <h3>Picardo Mex</h3>
                  <h4>Usuario</h4>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  Los productos siempre son de buena calidad y a buenos precios, me gusta que cada vez existe más variedad de productos y la forma de realizar una compra es muy sencilla.
                </p>
                <div class="profile mt-auto">
                  <img src="./assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
                  <h3>Guillermo Chin</h3>
                  <h4>Cliente</h4>
                </div>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Testimonials Section -->

    <!-- Team Section -->
    <section id="team" class="team section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Equipo</h2>
        <p>Nuestro talentoso equipo</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
            <div class="team-member">
              <div class="member-img">
                <img src="./img/FotoPerfil.jpeg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Ricardo García</h4>
                <span>Desarrollador</span>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
            <div class="team-member">
              <div class="member-img">
                <img src="./img/FotoPerfil.jpeg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Ivan Guerra</h4>
                <span>Desarrollador</span>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
            <div class="team-member">
              <div class="member-img">
                <img src="./img/FotoPerfil.jpeg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Isaí Moo</h4>
                <span>Desarrollador Jr.</span>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="400">
            <div class="team-member">
              <div class="member-img">
                <img src="./img/FotoPerfil.jpeg" class="img-fluid" alt="">
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Abner Jehu</h4>
                <span>Desarrollador</span>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Team Section -->

    <!-- Clients Section -->
    <section id="clients" class="clients section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Colaboraciones</h2>
        <p>Trabajamos con las mejores marcas<br></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "spaceBetween": 40
                },
                "480": {
                  "slidesPerView": 3,
                  "spaceBetween": 60
                },
                "640": {
                  "slidesPerView": 4,
                  "spaceBetween": 80
                },
                "992": {
                  "slidesPerView": 6,
                  "spaceBetween": 120
                }
              }
            }
          </script>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><img src="{{ asset('img/cisco.png') }}" class="img-fluid" alt="Cisco"></div>
            <div class="swiper-slide"><img src="{{ asset('img/tplink.png') }}" class="img-fluid" alt="TP-Link"></div>
            <div class="swiper-slide"><img src="{{ asset('img/belden.png') }}" class="img-fluid" alt="Belden"></div>
            <div class="swiper-slide"><img src="{{ asset('img/cober.png') }}" class="img-fluid" alt="Cober"></div>
            <div class="swiper-slide"><img src="{{ asset('img/netgear.png') }}" class="img-fluid" alt="Netgear"></div>
            <div class="swiper-slide"><img src="{{ asset('img/mikrotik.png') }}" class="img-fluid" alt="Mikrotik"></div>
            <div class="swiper-slide"><img src="{{ asset('img/huawei.png') }}" class="img-fluid" alt="Huawei"></div>
            <div class="swiper-slide"><img src="{{ asset('img/fortinet.png') }}" class="img-fluid" alt="Fortinet"></div>
        </div>
        
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Clients Section -->

    
    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contacto</h2>
        <p>Información de Contacto</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-6">

            <div class="row gy-4">
              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="200">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Dirección</h3>
                  <p>C. 111 #315</p>
                  <p>Mérida, Yucatán 97279</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Teléfono de contacto</h3>
                  <p>+1 5589 55488 55</p>
                  <p>+1 6678 254445 41</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Envíamos un correo</h3>
                  <p>telecommunity@gmail.com</p>
                  <p>telecommunity@outlook.com</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item" data-aos="fade" data-aos-delay="500">
                  <i class="bi bi-clock"></i>
                  <h3>Horario</h3>
                  <p>Lunes - Viernes</p>
                  <p>9:00AM - 05:00PM</p>
                </div>
              </div><!-- End Info Item -->

            </div>

          </div>

          <div class="col-lg-6">
            <form action="../controller/comentario_controller.php?m=create" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input id="nombre" name="name" class="form-control" placeholder="Nombre" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" id="email" class="form-control" name="email" placeholder="Email" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" id="asunto" name="subject" placeholder="Asunto" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" id="mensaje" rows="6" placeholder="Mensaje" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Cargando</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Gracias por sus comentarios!</div>

                  <button type="submit">Enviar</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="d-flex align-items-center">
            <span class="sitename">Telecommunity</span>
          </a>
          <div class="footer-contact pt-3">
            <p>A108 Adam Street</p>
            <p>New York, NY 535022</p>
            <p class="mt-3"><strong>Teléfono:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>telecommunity@gmail.com</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Accesibilidad</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Inicio</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Quiénes Somos</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Servicio</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Términos de servicio</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4>Síguenos en nuestras redes sociales</h4>
          <p></p>
          <div class="social-links d-flex">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Telecommunity</strong> <span>Todos los derechos reservados</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Diseñado por <a href="#hero">Telecommunity</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>