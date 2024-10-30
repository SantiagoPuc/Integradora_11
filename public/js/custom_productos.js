//VALIDACIÓN AGREGAR PRODUCTO//
document.addEventListener('DOMContentLoaded', (event) => {
    // Función para permitir solo números
    function allowOnlyNumbers(e) {
        var charCode = e.charCode || e.keyCode;
        if (!/[0-9]/.test(String.fromCharCode(charCode))) {
            e.preventDefault();
        }
    }

    // Añadir eventos para los campos de solo números
    document.getElementById("precio").addEventListener('keypress', allowOnlyNumbers);
    document.getElementById("precio_compra").addEventListener('keypress', allowOnlyNumbers);
    document.getElementById("precio_edit").addEventListener('keypress', allowOnlyNumbers);
    document.getElementById("precio_compra_edit").addEventListener('keypress', allowOnlyNumbers);

    // Función para mostrar mensajes de error
    function showError(elementId, message) {
        document.getElementById(elementId).innerText = message;
    }

    // Función para limpiar mensajes de error
    function clearErrors(formId) {
        var form = document.getElementById(formId);
        var errorElements = form.querySelectorAll('.error-message');
        errorElements.forEach(function(element) {
            element.innerText = '';
        });
    }

    // Validación en el envío del formulario de agregar producto
    document.getElementById('addProductForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Detener el envío del formulario inicialmente
        clearErrors('addProductForm');
        
        var nombre = document.getElementById("nombre").value.trim();
        var marca = document.getElementById("id_marca").value;
        var categoria = document.getElementById("id_categoria").value;
        var no_serie = document.getElementById("no_serie").value.trim();
        var modelo = document.getElementById("modelo").value.trim();
        var precio = document.getElementById("precio").value.trim();
        var precio_compra = document.getElementById("precio_compra").value.trim();
        var descripcion = document.getElementById("descripcion").value.trim();

        var formValid = true;

        if (!nombre) {
            showError('nombreError', "El campo de nombre no puede estar vacío.");
            formValid = false;
        }

        if (!marca) {
            showError('marcaError', "Por favor, seleccione una marca.");
            formValid = false;
        }

        if (!categoria) {
            showError('categoriaError', "Por favor, seleccione una categoría.");
            formValid = false;
        }

        if (!no_serie) {
            showError('no_serieError', "El campo de número serial no puede estar vacío.");
            formValid = false;
        }

        if (!modelo) {
            showError('modeloError', "El campo de modelo no puede estar vacío.");
            formValid = false;
        }

        if (!precio || isNaN(precio)) {
            showError('precioError', "El campo de precio de venta debe ser un número y no puede estar vacío.");
            formValid = false;
        }

        if (!precio_compra || isNaN(precio_compra)) {
            showError('precio_compraError', "El campo de precio de compra debe ser un número y no puede estar vacío.");
            formValid = false;
        }

        if (!descripcion) {
            showError('descripcionError', "El campo de descripción no puede estar vacío.");
            formValid = false;
        }

        if (formValid) {
            this.submit(); // Enviar el formulario si todas las validaciones son correctas
        } else {
            return false; // Detener el envío del formulario
        }
    });

    // VALIDACIÓN EDITAR PRODUCTO//
    document.getElementById('editProductForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Detener el envío del formulario inicialmente
        clearErrors('editProductForm');
        
        var nombre = document.getElementById("nombre_edit").value.trim();
        var marca = document.getElementById("id_marca_edit").value;
        var categoria = document.getElementById("id_categoria_edit").value;
        var no_serie = document.getElementById("no_serie_edit").value.trim();
        var modelo = document.getElementById("modelo_edit").value.trim();
        var precio = document.getElementById("precio_edit").value.trim();
        var precio_compra = document.getElementById("precio_compra_edit").value.trim();
        var descripcion = document.getElementById("descripcion_edit").value.trim();

        var formValid = true;

        if (!nombre) {
            showError('nombreEditError', "El campo de nombre no puede estar vacío.");
            formValid = false;
        }

        if (!marca) {
            showError('marcaEditError', "Por favor, seleccione una marca.");
            formValid = false;
        }

        if (!categoria) {
            showError('categoriaEditError', "Por favor, Seleccione una categoría.");
            formValid = false;
        }

        if (!no_serie) {
            showError('no_serieEditError', "El campo de número serial no puede estar vacío.");
            formValid = false;
        }

        if (!modelo) {
            showError('modeloEditError', "El campo de modelo no puede estar vacío.");
            formValid = false;
        }

        if (!precio || isNaN(precio)) {
            showError('precioEditError', "El campo de precio de venta debe ser un número y no puede estar vacío.");
            formValid = false;
        }

        if (!precio_compra || isNaN(precio_compra)) {
            showError('precio_compraEditError', "El campo de precio de compra debe ser un número y no puede estar vacío.");
            formValid = false;
        }

        if (!descripcion) {
            showError('descripcionEditError', "El campo de descripción no puede estar vacío.");
            formValid = false;
        }

        if (formValid) {
            this.submit(); // Enviar el formulario si todas las validaciones son correctas
        } else {
            return false; // Detener el envío del formulario
        }
    });
});
