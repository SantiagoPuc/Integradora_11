document.addEventListener('DOMContentLoaded', (event) => {
    // Función para permitir solo letras
    function allowOnlyLetters(e) {
        var charCode = e.charCode || e.keyCode;
        if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/.test(String.fromCharCode(charCode))) {
            e.preventDefault();
        }
    }

    // Función para permitir solo números y limitar la longitud a 10 caracteres
    function allowOnlyNumbersAndLimit(e) {
        var charCode = e.charCode || e.keyCode;
        var input = e.target;
        if (!/[0-9]/.test(String.fromCharCode(charCode)) || input.value.length >= 10) {
            e.preventDefault();
        }
    }

    // Añadir eventos para los campos de solo letras
    document.getElementById("nombre").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("apellido_1").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("apellido_2").addEventListener('keypress', allowOnlyLetters);

    // Añadir eventos para el campo de solo números y limitar a 10 caracteres
    document.getElementById("telefono").addEventListener('keypress', allowOnlyNumbersAndLimit);

    // Función para mostrar mensajes de error
    function showError(elementId, message) {
        document.getElementById(elementId).innerText = message;
    }

    // Función para limpiar mensajes de error
    function clearErrors() {
        var errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(function(element) {
            element.innerText = '';
        });
    }

    // Validación en el envío del formulario
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Detener el envío del formulario inicialmente
        clearErrors();
        
        var name = document.getElementById("nombre").value.trim();
        var apellido1 = document.getElementById("apellido_1").value.trim();
        var apellido2 = document.getElementById("apellido_2").value.trim();
        var telefono = document.getElementById("telefono").value.trim();
        var correo = document.getElementById("correo").value.trim();
        
        var letterRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
        var phoneRegex = /^999\d{7}$/;
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var formValid = true;

        if (!name || !letterRegex.test(name)) {
            showError('nombreError', "El campo de nombre solo puede contener letras y no puede estar vacío.");
            formValid = false;
        }

        if (!apellido1 || !letterRegex.test(apellido1)) {
            showError('apellido1Error', "El campo de primer apellido solo puede contener letras y no puede estar vacío.");
            formValid = false;
        }

        if (!apellido2 || !letterRegex.test(apellido2)) {
            showError('apellido2Error', "El campo de segundo apellido solo puede contener letras y no puede estar vacío.");
            formValid = false;
        }

        if (!telefono || !phoneRegex.test(telefono)) {
            showError('telefonoError', "El número de teléfono debe tener 10 dígitos y comenzar con 999.");
            formValid = false;
        }

        if (!correo || !emailRegex.test(correo)) {
            showError('correoError', "El correo electrónico debe tener un formato válido.");
            formValid = false;
        }

        // Verificar que todos los campos estén llenos
        var requiredFields = document.querySelectorAll("#addUserForm input[required], #addUserForm select[required]");
        for (let i = 0; i < requiredFields.length; i++) {
            if (requiredFields[i].value.trim() === "") {
                showError(requiredFields[i].id + 'Error', "Por favor, complete este campo.");
                formValid = false;
            }
        }

        if (formValid) {
            this.submit(); // Enviar el formulario si todas las validaciones son correctas
        } else {
            return false; // Detener el envío del formulario
        }
    });
});

//VALIDACIÓN EDITAR USUARIO//
document.addEventListener('DOMContentLoaded', (event) => {
    // Función para permitir solo letras
    function allowOnlyLetters(e) {
        var charCode = e.charCode || e.keyCode;
        if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/.test(String.fromCharCode(charCode))) {
            e.preventDefault();
        }
    }

    // Función para permitir solo números y limitar la longitud a 10 caracteres
    function allowOnlyNumbersAndLimit(e) {
        var charCode = e.charCode || e.keyCode;
        var input = e.target;
        if (!/[0-9]/.test(String.fromCharCode(charCode)) || input.value.length >= 10) {
            e.preventDefault();
        }
    }

    // Añadir eventos para los campos de solo letras
    document.getElementById("nombre_edit").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("apellido_1_edit").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("apellido_2_edit").addEventListener('keypress', allowOnlyLetters);

    // Añadir eventos para el campo de solo números y limitar a 10 caracteres
    document.getElementById("telefono_edit").addEventListener('keypress', allowOnlyNumbersAndLimit);

    // Función para mostrar mensajes de error
    function showError(elementId, message) {
        document.getElementById(elementId).innerText = message;
    }

    // Función para limpiar mensajes de error
    function clearErrors() {
        var errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(function(element) {
            element.innerText = '';
        });
    }

    // Validación en el envío del formulario
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Detener el envío del formulario inicialmente
        clearErrors();
        
        var name = document.getElementById("nombre_edit").value.trim();
        var apellido1 = document.getElementById("apellido_1_edit").value.trim();
        var apellido2 = document.getElementById("apellido_2_edit").value.trim();
        var telefono = document.getElementById("telefono_edit").value.trim();
        var correo = document.getElementById("correo_edit").value.trim();
        
        var letterRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
        var phoneRegex = /^999\d{7}$/;
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var formValid = true;

        if (!name || !letterRegex.test(name)) {
            showError('nombreError', "El campo de nombre solo puede contener letras y no puede estar vacío.");
            formValid = false;
        }

        if (!apellido1 || !letterRegex.test(apellido1)) {
            showError('apellido1Error', "El campo de primer apellido solo puede contener letras y no puede estar vacío.");
            formValid = false;
        }

        if (!apellido2 || !letterRegex.test(apellido2)) {
            showError('apellido2Error', "El campo de segundo apellido solo puede contener letras y no puede estar vacío.");
            formValid = false;
        }

        if (!telefono || !phoneRegex.test(telefono)) {
            showError('telefonoError', "El número de teléfono debe tener 10 dígitos y comenzar con 999.");
            formValid = false;
        }

        if (!correo || !emailRegex.test(correo)) {
            showError('correoError', "El correo electrónico debe tener un formato válido.");
            formValid = false;
        }

        // Verificar que todos los campos estén llenos
        var requiredFields = document.querySelectorAll("#editUserForm input[required], #editUserForm select[required]");
        for (let i = 0; i < requiredFields.length; i++) {
            if (requiredFields[i].value.trim() === "") {
                showError(requiredFields[i].id + 'Error', "Por favor, complete este campo.");
                formValid = false;
            }
        }

        if (formValid) {
            this.submit(); // Enviar el formulario si todas las validaciones son correctas
        } else {
            return false; // Detener el envío del formulario
        }
    });
});
