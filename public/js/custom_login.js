document.addEventListener('DOMContentLoaded', function () {
    // -------------------- CALCULAR ID ------------------------- //
    function calculateClientId() {
        const telefono = document.getElementById('telefono').value;
        const codigoPostal = document.getElementById('codigo-postal').value;

        if (telefono && codigoPostal) {
            const telefonoNum = parseInt(telefono.slice(-5), 10);
            const codigoPostalNum = parseInt(codigoPostal.replace(/\D/g, ''), 10);
            
            if (!isNaN(telefonoNum) && !isNaN(codigoPostalNum)) {
                const hipotenusa = Math.sqrt(Math.pow(telefonoNum, 2) + Math.pow(codigoPostalNum, 2));
                const clientId = Math.floor(hipotenusa); // Hipotenusa completa
                
                const idField = document.getElementById('id');
                idField.readOnly = false;
                idField.value = clientId;
                idField.readOnly = true;
            }
        }
    }

    document.getElementById('telefono').addEventListener('input', calculateClientId);
    document.getElementById('codigo-postal').addEventListener('input', calculateClientId);

    // Cargar países
    fetch('../controller/login_controller.php?action=getPaises')
        .then(response => response.json())
        .then(paises => {
            const paisElement = document.getElementById('pais');
            paisElement.innerHTML = '<option value="">Selecciona un país</option>';
            paises.forEach(pais => {
                const option = document.createElement('option');
                option.value = pais.ID_pais;
                option.textContent = pais.Nombre;
                paisElement.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar los países:', error);
        });

    // Validar contraseñas
    const form = document.getElementById('form_agregar_cliente');
    const passwordInput = document.getElementById('passwordd_C');
    const confirmPasswordInput = document.getElementById('confirmarcontraseña');
    const submitButton = document.getElementById('submit_button');

    function validatePasswords() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            showError(confirmPasswordInput, "Las contraseñas no coinciden.");
        } else {
            clearError(confirmPasswordInput);
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Cargar estados y ciudades
    const paisElement = document.getElementById('pais');
    const estadoElement = document.getElementById('estado');
    const ciudadElement = document.getElementById('ciudad');

    if (paisElement) {
        paisElement.addEventListener('change', function() {
            const idPais = this.value;
            if (idPais) {
                const url = `../controller/login_controller.php?action=getEstadosByPais&idPais=${idPais}`;
                fetch(url)
                    .then(response => response.json())
                    .then(estados => {
                        estadoElement.innerHTML = '<option value="">Selecciona un estado</option>';
                        estados.forEach(estado => {
                            const option = document.createElement('option');
                            option.value = estado.ID_estado;
                            option.textContent = estado.Nombre;
                            estadoElement.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar los estados:', error);
                    });
            }
        });
    }

    if (estadoElement) {
        estadoElement.addEventListener('change', function() {
            const idEstado = this.value;
            if (idEstado) {
                const url = `../controller/login_controller.php?action=getCiudadesByEstado&idEstado=${idEstado}`;
                fetch(url)
                    .then(response => response.json())
                    .then(ciudades => {
                        ciudadElement.innerHTML = '<option value="">Selecciona una ciudad</option>';
                        ciudades.forEach(ciudad => {
                            const option = document.createElement('option');
                            option.value = ciudad.ID_ciudad;
                            option.textContent = ciudad.Nombre;
                            ciudadElement.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar las ciudades:', error);
                    });
            }
        });
    }

    // --------------------- VALIDACIONES ------------------------- //
    const formAgregar = document.getElementById('form_agregar_cliente');
    const formEditar = document.getElementById('form_editar_cliente');
    const submitButtonAgregar = document.getElementById('submit_button');
    const submitButtonEditar = document.getElementById('guardar_cambios');

    function showError(input, message) {
        input.classList.add('is-invalid');
        const errorElement = document.getElementById(`error-${input.id}`);
        errorElement.textContent = message;
    }

    function clearError(input) {
        input.classList.remove('is-invalid');
        const errorElement = document.getElementById(`error-${input.id}`);
        errorElement.textContent = '';
    }

    function validateInput(input) {
        let isValid = true;
        const value = input.value;
        if (input.name === 'nombre' || input.name === 'primer_apellido' || input.name === 'segundo_apellido') {
            if (!/^[A-Za-z]+$/.test(value)) {
                isValid = false;
            } else {
                clearError(input);
            }
        } else if (input.name === 'telefono') {
            if (!/^(999\d{7})$/.test(value)) {
                showError(input, 'El teléfono debe comenzar con 999 y contener exactamente 10 dígitos');
                isValid = false;
            } else {
                clearError(input);
            }
        } else if (input.name === 'correo') {
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                showError(input, 'Por favor, ingresa una dirección de correo electrónico válida');
                isValid = false;
            } else {
                clearError(input);
            }
        } else if (input.name === 'codigo_postal') {
            if (!/^\d{5}$/.test(value)) {
                showError(input, 'El código postal debe contener exactamente 5 dígitos');
                isValid = false;
            } else {
                clearError(input);
            }
        }
        return isValid;
    }

    function checkFormValidity(form, submitButton) {
        let isValid = true;
        form.querySelectorAll('input[required], select[required]').forEach(input => {
            if (!validateInput(input)) {
                isValid = false;
            }
        });
        submitButton.disabled = !isValid;
    }

    function handleInput(event) {
        const input = event.target;
        validateInput(input);
        checkFormValidity(input.form, input.form.querySelector('button[type="submit"]'));
    }

    // Agregar errores debajo de cada campo
    formAgregar.querySelectorAll('input, select').forEach(input => {
        const errorElement = document.createElement('div');
        errorElement.id = `error-${input.id}`;
        errorElement.className = 'invalid-feedback';
        input.parentNode.appendChild(errorElement);

        input.addEventListener('input', handleInput);
        input.addEventListener('change', handleInput);
    });

    formEditar.querySelectorAll('input, select').forEach(input => {
        const errorElement = document.createElement('div');
        errorElement.id = `error-${input.id}`;
        errorElement.className = 'invalid-feedback';
        input.parentNode.appendChild(errorElement);

        input.addEventListener('input', handleInput);
        input.addEventListener('change', handleInput);
    });

    formAgregar.addEventListener('submit', function (event) {
        if (!formAgregar.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        formAgregar.classList.add('was-validated');
    });

    formEditar.addEventListener('submit', function (event) {
        if (!formEditar.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        formEditar.classList.add('was-validated');
    });

    // Evitar el envío del formulario al presionar Enter y mover el foco al siguiente campo
    formAgregar.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const formElements = Array.from(formAgregar.querySelectorAll('input, select, button'));
            const index = formElements.indexOf(document.activeElement);
            if (index > -1 && index < formElements.length - 1) {
                formElements[index + 1].focus();
            }
        }
    });

    formEditar.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const formElements = Array.from(formEditar.querySelectorAll('input, select, button'));
            const index = formElements.indexOf(document.activeElement);
            if (index > -1 && index < formElements.length - 1) {
                formElements[index + 1].focus();
            }
        }
    });

    // Inicializa el estado del botón de enviar
    checkFormValidity(formAgregar, submitButtonAgregar);
    checkFormValidity(formEditar, submitButtonEditar);
});

// Función para permitir solo letras en los campos de nombre y apellidos
function allowOnlyLetters(event) {
    const keyCode = event.keyCode || event.which;
    const keyValue = String.fromCharCode(keyCode);
    if (!/^[a-zA-Z]+$/.test(keyValue)) {
        event.preventDefault();
    }
}

// Función para permitir solo números en los campos de teléfono y código postal
function allowOnlyNumbers(event) {
    const keyCode = event.keyCode || event.which;
    const keyValue = String.fromCharCode(keyCode);
    if (!/^\d+$/.test(keyValue)) {
        event.preventDefault();
    }
}

function limitInputLength(event, maxLength) {
    if (event.target.value.length >= maxLength) {
        event.preventDefault();
    }
}

document.getElementById('nombre').addEventListener('keypress', allowOnlyLetters);
document.getElementById('primer-apellido').addEventListener('keypress', allowOnlyLetters);
document.getElementById('segundo-apellido').addEventListener('keypress', allowOnlyLetters);
document.getElementById('telefono').addEventListener('keypress', function(event) {
    allowOnlyNumbers(event);
    limitInputLength(event, 10);
});
document.getElementById('codigo-postal').addEventListener('keypress', function(event) {
    allowOnlyNumbers(event);
    limitInputLength(event, 5);
});
document.getElementById('edit_nombre').addEventListener('keypress', allowOnlyLetters);
document.getElementById('edit_primer_apellido').addEventListener('keypress', allowOnlyLetters);
document.getElementById('edit_segundo_apellido').addEventListener('keypress', allowOnlyLetters);
document.getElementById('edit_telefono').addEventListener('keypress', function(event) {
    allowOnlyNumbers(event);
    limitInputLength(event, 10);
});
document.getElementById('edit_codigo_postal').addEventListener('keypress', function(event) {
    allowOnlyNumbers(event);
    limitInputLength(event, 5);
});

function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById('toggleIcon');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    }
}
