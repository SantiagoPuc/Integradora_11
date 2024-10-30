<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeleCommunity</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/logo_azul.jpg') }}">

    <!-- Hojas de estilo -->
    <link href="{{ asset('css/estiloHeader1.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estiloHeader2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menubar.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script src="{{ asset('js/am_index.js') }}"></script>
    <script src="{{ asset('js/scriptHeader1.js') }}"></script>
    <script src="{{ asset('js/scriptHeader2.js') }}"></script>
    <script src="{{ asset('js/scriptHeader3.js') }}"></script>
    <script src="{{ asset('js/custom_proveedores.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/scriptHeader4.js') }}"></script>
</head>
<body>

    <!-- Header -->
    @include('header')



    <!-- Footer -->
    @include('footer')

</body>
</html>
