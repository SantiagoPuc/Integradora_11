(function() {
document.addEventListener('DOMContentLoaded', function() {
    /*--------------------CALCULAR ID------------------------- */
    function calculateClientId() {
        const telefono = document.getElementById('telefono').value;
        const codigoPostal = document.getElementById('codigo-postal').value;

        if (telefono && codigoPostal) {
            const telefonoNum = parseInt(telefono.slice(-5), 10);
            const codigoPostalNum = parseInt(codigoPostal.replace(/\D/g, ''), 10);
            
            if (!isNaN(telefonoNum) && !isNaN(codigoPostalNum)) {
                const hipotenusa = Math.sqrt(Math.pow(telefonoNum, 2) + Math.pow(codigoPostalNum, 2));
                const clientId = Math.floor(hipotenusa); // Hipotenusa completa
                
                // Desbloquea el campo ID, actualiza su valor y vuelve a bloquearlo
                const idField = document.getElementById('id');
                idField.readOnly = false;
                idField.value = clientId;
                idField.readOnly = true;
            }
        }
    }

    document.getElementById('telefono').addEventListener('input', calculateClientId);
    document.getElementById('codigo-postal').addEventListener('input', calculateClientId);

    /*------------------------CARGAR CIUDADES, ESTADOS, PAISES DINAMICAMENTE--------------------- */
    const paisElement = document.getElementById('pais');
    const estadoElement = document.getElementById('estado');
    const ciudadElement = document.getElementById('ciudad');

    if (paisElement) {
        paisElement.addEventListener('change', function() {
            const idPais = this.value;
            if (idPais) {
                const url = `../controller/Adm_Clientes_controller.php?action=get_estados&id_pais=${idPais}`;
                fetch(url)
                    .then(response => {
                        console.log('HTTP status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(estados => {
                        console.log('Estados recibidos:', estados);
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
                const url = `../controller/Adm_Clientes_controller.php?action=get_ciudades&id_estado=${idEstado}`;
                fetch(url)
                    .then(response => {
                        console.log('HTTP status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(ciudades => {
                        console.log('Ciudades recibidas:', ciudades);
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

    /*-------------------EDITAR----------------- */
    const editPaisElement = document.getElementById('edit_pais');
    const editEstadoElement = document.getElementById('edit_estado');
    const editCiudadElement = document.getElementById('edit_ciudad');
    const guardarCambiosBtn = document.getElementById('guardar_cambios');
    const formEditarCliente = document.getElementById('form_editar_cliente');

    function loadPaises(selectedPaisId) {
        fetch('../controller/Adm_Clientes_controller.php?action=get_paises')
            .then(response => response.json())
            .then(paises => {
                console.log('Paises recibidos:', paises);
                if (Array.isArray(paises)) {
                    editPaisElement.innerHTML = '<option value="">Selecciona un país</option>';
                    paises.forEach(pais => {
                        const option = document.createElement('option');
                        option.value = pais.ID_pais;
                        option.textContent = pais.Nombre;
                        editPaisElement.appendChild(option);
                    });
                    if (selectedPaisId) {
                        editPaisElement.value = selectedPaisId;
                    }
                } else {
                    throw new Error('La respuesta no es un array.');
                }
            })
            .catch(error => {
                console.error('Error al cargar los países:', error);
            });
    }

    function loadEstados(paisId, selectedEstadoId) {
        fetch(`../controller/Adm_Clientes_controller.php?action=get_estados&id_pais=${paisId}`)
            .then(response => response.json())
            .then(estados => {
                console.log('Estados recibidos:', estados);
                if (Array.isArray(estados)) {
                    editEstadoElement.innerHTML = '<option value="">Selecciona un estado</option>';
                    estados.forEach(estado => {
                        const option = document.createElement('option');
                        option.value = estado.ID_estado;
                        option.textContent = estado.Nombre;
                        editEstadoElement.appendChild(option);
                    });
                    if (selectedEstadoId) {
                        editEstadoElement.value = selectedEstadoId;
                    }
                } else {
                    throw new Error('La respuesta no es un array.');
                }
            })
            .catch(error => {
                console.error('Error al cargar los estados:', error);
            });
    }

    function loadCiudades(estadoId, selectedCiudadId) {
        fetch(`../controller/Adm_Clientes_controller.php?action=get_ciudades&id_estado=${estadoId}`)
            .then(response => response.json())
            .then(ciudades => {
                console.log('Ciudades recibidas:', ciudades);
                if (Array.isArray(ciudades)) {
                    editCiudadElement.innerHTML = '<option value="">Selecciona una ciudad</option>';
                    ciudades.forEach(ciudad => {
                        const option = document.createElement('option');
                        option.value = ciudad.ID_ciudad;
                        option.textContent = ciudad.Nombre;
                        editCiudadElement.appendChild(option);
                    });
                    if (selectedCiudadId) {
                        editCiudadElement.value = selectedCiudadId;
                    }
                } else {
                    throw new Error('La respuesta no es un array.');
                }
            })
            .catch(error => {
                console.error('Error al cargar las ciudades:', error);
            });
    }

    function attachEditButtons() {
        document.querySelectorAll('.btn-edit-user').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                fetch(`../controller/Adm_Clientes_controller.php?action=get_cliente&id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Datos del cliente recibidos:', data);
                        if (data) {
                            document.getElementById('edit_id').value = data.id_cliente;
                            document.getElementById('edit_nombre').value = data.nombre;
                            document.getElementById('edit_primer_apellido').value = data.apellido_1;
                            document.getElementById('edit_segundo_apellido').value = data.apellido_2;
                            document.getElementById('edit_telefono').value = data.telefono;
                            document.getElementById('edit_correo').value = data.correo;
                            document.getElementById('edit_calle').value = data.calle;
                            document.getElementById('edit_numero').value = data.numero;
                            document.getElementById('edit_codigo_postal').value = data.cp;
                            document.getElementById('edit_cruzamientos').value = data.cruzamientos;
                            document.getElementById('edit_colonia').value = data.colonia;
                            document.getElementById('edit_pais').value = data.ID_pais;
                            document.getElementById('edit_estado').value = data.ID_estado;
                            document.getElementById('edit_ciudad').value = data.id_ciudad;
    
                            // Set the existing photo if available
                            const fotoPath = data.foto ? `../uploads/${data.foto}` : "../img/0.jpg";
                            const fotoElement = document.getElementById('edit_foto_preview');
                            if (fotoElement) {
                                fotoElement.src = fotoPath;
                            }
    
                            // Set the hidden input with the existing photo name
                            document.getElementById('existing_foto').value = data.foto || '';
    
                            loadPaises(data.ID_pais);
                            loadEstados(data.ID_pais, data.ID_estado);
                            loadCiudades(data.ID_estado, data.id_ciudad);
    
                            $('#editar_cliente').modal('show');
                        } else {
                            throw new Error('No se encontraron datos del cliente.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching client:', error);
                    });
            });
        });
    }
    
    

    editPaisElement.addEventListener('change', function () {
        const idPais = this.value;
        if (idPais) {
            loadEstados(idPais);
        }
    });

    editEstadoElement.addEventListener('change', function () {
        const idEstado = this.value;
        if (idEstado) {
            loadCiudades(idEstado);
        }
    });

    if (guardarCambiosBtn && formEditarCliente) {
        guardarCambiosBtn.addEventListener('click', function () {
            const formData = new FormData(formEditarCliente);

            fetch('../controller/Adm_Clientes_controller.php?action=update_cliente', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                console.log('Resultado de la actualización:', result);
                if (result.success) {
                    location.reload();
                } else {
                    alert('Error al actualizar el cliente: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error al actualizar el cliente:', error);
            });
        });
    } else {
        console.error('No se encontró el botón o el formulario para añadir el event listener');
    }

    // Función para manejar la eliminación de clientes
     // Función para manejar la eliminación de clientes
     function handleDeleteClient(userId) {
        const deleteIdInput = document.getElementById('delete_id');
        if (deleteIdInput) {
            deleteIdInput.value = userId;
            console.log('ID del cliente a eliminar:', userId); // Log para verificar
        } else {
            console.error('Elemento con id "delete_id" no encontrado');
        }

        const modalElement = document.getElementById('eliminar_cliente');
        if (modalElement) {
            console.log('Modal encontrado en el DOM');
            const modalEliminar = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            modalEliminar.show();
            console.log('Mostrando el modal de eliminación');
        } else {
            console.error('Elemento con id "eliminar_cliente" no encontrado');
        }
    }

    // Agregar event listeners a los botones de eliminar
    function attachDeleteButtons() {
        document.querySelectorAll('.btn-delete-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                handleDeleteClient(userId);
            });
        });
    }

    // Listener para el botón de confirmación de eliminación
    document.getElementById('confirmar_eliminar').addEventListener('click', function () {
        const formEliminarCliente = document.getElementById('form_eliminar_cliente');
        const formData = new FormData(formEliminarCliente);

        // Verificación de los datos del formulario
        for (let pair of formData.entries()) {
            console.log(pair[0]+ ': ' + pair[1]);
        }

        fetch('../controller/Adm_Clientes_controller.php?action=delete_cliente', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Resultado de la eliminación:', result);
            if (result.success) {
                location.reload();
            } else {
                alert('Error al eliminar el cliente: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar el cliente:', error);
        });
    });

    // Inicializar event listeners para la lista de clientes
    attachDeleteButtons();

    // Función para buscar clientes
    function fetchClientes(query) {
        var url = '../controller/Adm_Clientes_controller.php?action=buscar_cliente&q=' + encodeURIComponent(query);
    
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                listaClientes.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(cliente => {
                        var fotoPath = cliente.foto ? `../uploads/${cliente.foto}` : "../img/0.jpg";
                        var clienteItem = document.createElement('div');
                        clienteItem.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                        clienteItem.innerHTML = `
                            <div class="d-flex">
                                <img src="${fotoPath}" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1">${cliente.nombre}</h5>
                                    <p class="mb-1">ID: ${cliente.id_cliente}</p>
                                    <p class="mb-1">Correo: ${cliente.correo}</p>
                                    <p class="mb-1">Teléfono: ${cliente.telefono}</p>
                                    <p class="mb-1">Dirección: ${cliente.calle} ${cliente.numero}, ${cliente.colonia}, ${cliente.Ciudad}, ${cliente.Estado}, ${cliente.Pais}</p>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-outline-primary btn-sm me-1 btn-view-details" data-user-id="${cliente.id_cliente}"><i class="fas fa-search"></i></button>
                                <button class="btn btn-outline-warning btn-sm btn-edit-user" data-bs-toggle="modal" data-bs-target="#editar_cliente" data-user-id="${cliente.id_cliente}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-danger btn-sm btn-delete-user" data-user-id="${cliente.id_cliente}"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        `;
                        listaClientes.appendChild(clienteItem);
                    });
                    attachDeleteButtons(); // Adjunta los event listeners para eliminar después de cargar los resultados
                    attachEditButtons(); // Asegúrate de tener esta función definida
                    attachViewDetailsButtons(); // Asegúrate de tener esta función definida
                } else {
                    listaClientes.innerHTML = '<p>No hay clientes registrados.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching client data:', error);
                listaClientes.innerHTML = '<p>Hubo un error al buscar los clientes.</p>';
            });
    }
    

    // Inicializa la lista completa de clientes cuando se carga la página
    fetchClientes('');

    // Buscador de clientes
    const buscarClienteInput = document.getElementById('buscarCliente');
    buscarClienteInput.addEventListener('input', function() {
        var query = this.value.trim();
        fetchClientes(query);
    });

    // Función para manejar la vista de detalles de clientes Función para manejar la vista de detalles de clientes
    function attachViewDetailsButtons() {
        document.querySelectorAll('.btn-view-details').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                fetch(`../controller/Adm_Clientes_controller.php?action=get_cliente2&id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Verifica la estructura del objeto `data`
                        if (data) {
                            const fotoPath = data.foto ? `../uploads/${data.foto}` : "../img/0.jpg";
                            const detalleContenido = `
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>ID:</strong></div>
                                    <div class="col-md-8">${data.id_cliente}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Nombre:</strong></div>
                                    <div class="col-md-8">${data.nombre}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Primer Apellido:</strong></div>
                                    <div class="col-md-8">${data.apellido_1}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Segundo Apellido:</strong></div>
                                    <div class="col-md-8">${data.apellido_2}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Correo:</strong></div>
                                    <div class="col-md-8">${data.correo}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Teléfono:</strong></div>
                                    <div class="col-md-8">${data.telefono}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Dirección:</strong></div>
                                    <div class="col-md-8">${data.calle} ${data.numero}, ${data.colonia}, ${data.ciudad_nombre}, ${data.estado_nombre}, ${data.pais_nombre}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>Foto:</strong></div>
                                    <div class="col-md-8"><img src="${fotoPath}" alt="Foto del Cliente" style="width: 100px; height: 100px; object-fit: cover;"></div>
                                </div>
                            `;
                            document.getElementById('detalleClienteContenido').innerHTML = detalleContenido;
                            $('#detalle_cliente').modal('show');
                        } else {
                            console.error('No se encontraron datos del cliente.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching client details:', error);
                    });
            });
        });
    }
    
    



  
    
    
});
  /*---------------------VALIDACIONES------------------------- */
  document.addEventListener('DOMContentLoaded', function () {
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
        return isValid;
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

    const passwordInput = document.getElementById('passwordd_C');
    const confirmPasswordInput = document.getElementById('confirmarcontraseña');

    function validatePasswords() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            showError(confirmPasswordInput, "Las contraseñas no coinciden.");
            return false;
        } else {
            clearError(confirmPasswordInput);
            return true;
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    formAgregar.addEventListener('submit', function (event) {
        if (!checkFormValidity(formAgregar, submitButtonAgregar) || !validatePasswords()) {
            event.preventDefault();
            event.stopPropagation();
        }
        formAgregar.classList.add('was-validated');
    });

    formEditar.addEventListener('submit', function (event) {
        if (!checkFormValidity(formEditar, submitButtonEditar) || !validatePasswords()) {
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

})();

/*document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM completamente cargado y analizado");

    // Agregar event listener a los botones de eliminar
    document.querySelectorAll('.btn-delete-user').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            console.log('ID del cliente a eliminar:', userId);

            const deleteIdInput = document.getElementById('delete_id');
            if (deleteIdInput) {
                deleteIdInput.value = userId;
            } else {
                console.error('Elemento con id "delete_id" no encontrado');
            }

            const modalElement = document.getElementById('eliminar_cliente');
            if (modalElement) {
                console.log('Modal encontrado en el DOM');
                const modalEliminar = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                modalEliminar.show();
                console.log('Intentando mostrar el modal de eliminación');
            } else {
                console.error('Elemento con id "eliminar_cliente" no encontrado');
            }
        });
    });

    document.getElementById('confirmar_eliminar').addEventListener('click', function () {
        const formEliminarCliente = document.getElementById('form_eliminar_cliente');
        const formData = new FormData(formEliminarCliente);

        fetch('../controller/Adm_Clientes_controller.php?action=delete_cliente', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Resultado de la eliminación:', result);
            if (result.success) {
                location.reload();
            } else {
                alert('Error al eliminar el cliente: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar el cliente:', error);
        });
    });
}); */