(function() {
    document.addEventListener('DOMContentLoaded', function() {
        //--------------------------------------CODIGOS DEL FORMULARIO DE EDITAR---------------------------------------
        const editPaisElement = document.getElementById('edit_pais');
        const editEstadoElement = document.getElementById('edit_estado');
        const editCiudadElement = document.getElementById('edit_ciudad');
        const guardarCambiosBtn = document.getElementById('guardar_cambios');
        const formEditarProveedor = document.getElementById('form_editar_proveedor');

        if (editPaisElement) {
            editPaisElement.addEventListener('change', function() {
                const paisId = this.value;
                if (paisId) {
                    loadEstados(paisId);
                } else {
                    editEstadoElement.innerHTML = '<option value="">Selecciona un estado</option>';
                    editCiudadElement.innerHTML = '<option value="">Selecciona una ciudad</option>';
                }
            });
        }

        if (editEstadoElement) {
            editEstadoElement.addEventListener('change', function() {
                const estadoId = this.value;
                if (estadoId) {
                    loadCiudades(estadoId);
                } else {
                    editCiudadElement.innerHTML = '<option value="">Selecciona una ciudad</option>';
                }
            });
        }

        function loadMarcas(selectedMarcas) {
            fetch('../controller/Adm_Proveedores_controller.php?action=get_marcas')
                .then(response => response.json())
                .then(marcas => {
                    if (Array.isArray(marcas)) {
                        const marcasElement = document.getElementById('edit_marcas');
                        marcasElement.innerHTML = '';
                        marcas.forEach(marca => {
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'marcas[]';
                            checkbox.value = marca.ID_marca;
                            checkbox.id = 'marca_' + marca.ID_marca;
                            checkbox.checked = selectedMarcas.includes(marca.ID_marca.toString());

                            const label = document.createElement('label');
                            label.htmlFor = 'marca_' + marca.ID_marca;
                            label.textContent = marca.Nombre_marca;

                            const div = document.createElement('div');
                            div.appendChild(checkbox);
                            div.appendChild(label);

                            marcasElement.appendChild(div);
                        });
                    } else {
                        throw new Error('La respuesta no es un array.');
                    }
                })
                .catch(error => console.error('Error al cargar las marcas:', error));
        }

        function loadCiudades(estadoId, selectedCiudadId = null, targetElement = editCiudadElement) {
            console.log(`Cargando ciudades para estado ID: ${estadoId}`);
            return fetch(`../controller/Adm_Proveedores_controller.php?action=get_ciudades&id_estado=${estadoId}`)
                .then(response => response.json())
                .then(ciudades => {
                    console.log("Ciudades recibidas:", ciudades); 
                    if (Array.isArray(ciudades)) {
                        targetElement.innerHTML = '<option value="">Selecciona una ciudad</option>';
                        ciudades.forEach(ciudad => {
                            const option = document.createElement('option');
                            option.value = ciudad.ID_ciudad;
                            option.textContent = ciudad.Nombre;
                            targetElement.appendChild(option);
                        });
                        if (selectedCiudadId) {
                            targetElement.value = selectedCiudadId;
                        }
                    } else {
                        throw new Error('La respuesta no es un array.');
                    }
                })
                .catch(error => console.error('Error al cargar las ciudades:', error));
        }
        
        function loadEstados(paisId, selectedEstadoId = null, targetElement = editEstadoElement) {
            console.log(`Cargando estados para país ID: ${paisId}`);
            return fetch(`../controller/Adm_Proveedores_controller.php?action=get_estados&id_pais=${paisId}`)
                .then(response => response.json())
                .then(estados => {
                    console.log("Estados recibidos:", estados);
                    if (Array.isArray(estados)) {
                        targetElement.innerHTML = '<option value="">Selecciona un estado</option>';
                        estados.forEach(estado => {
                            const option = document.createElement('option');
                            option.value = estado.ID_estado;
                            option.textContent = estado.Nombre;
                            targetElement.appendChild(option);
                        });
                        if (selectedEstadoId) {
                            targetElement.value = selectedEstadoId;
                            return loadCiudades(selectedEstadoId, document.getElementById('edit_ciudad').dataset.selectedCiudad);
                        }
                    } else {
                        throw new Error('La respuesta no es un array.');
                    }
                })
                .catch(error => console.error('Error al cargar los estados:', error));
        }
        
        function loadPaises(selectedPaisId = null, targetElement = editPaisElement) {
            return fetch('../controller/Adm_Proveedores_controller.php?action=get_paises')
                .then(response => response.json())
                .then(paises => {
                    console.log("Paises recibidos:", paises);
                    if (Array.isArray(paises)) {
                        targetElement.innerHTML = '<option value="">Selecciona un país</option>';
                        paises.forEach(pais => {
                            const option = document.createElement('option');
                            option.value = pais.ID_pais;
                            option.textContent = pais.Nombre;
                            targetElement.appendChild(option);
                        });
                        if (selectedPaisId) {
                            targetElement.value = selectedPaisId;
                            return loadEstados(selectedPaisId, document.getElementById('edit_estado').dataset.selectedEstado);
                        }
                    } else {
                        throw new Error('La respuesta no es un array.');
                    }
                })
                .catch(error => console.error('Error al cargar los países:', error));
        }
        
        function attachEditButtons() {
            document.querySelectorAll('.btn-edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    fetch(`../controller/Adm_Proveedores_controller.php?action=get_proveedor&id=${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log("Datos del proveedor recibidos:", data);
                            if (data) {
                                document.getElementById('edit_id').value = data.id_proveedor;
                                document.getElementById('edit_nombre').value = data.nombre;
                                document.getElementById('edit_primer_apellido').value = data.apellido_1;
                                document.getElementById('edit_segundo_apellido').value = data.apellido_2;
                                document.getElementById('edit_telefono').value = data.telefono;
                                document.getElementById('edit_rfc').value = data.rfc;
                                document.getElementById('edit_calle').value = data.calle;
                                document.getElementById('edit_numero').value = data.numero;
                                document.getElementById('edit_codigo_postal').value = data.cp;
                                document.getElementById('edit_cruzamientos').value = data.cruzamientos;
                                document.getElementById('edit_colonia').value = data.colonia;
        
                                const fotoPath = data.foto ? `../uploads/proveedoresimg/${data.foto}` : "../img/0.jpg";
                                document.getElementById('edit_foto_preview').src = fotoPath;
                                document.getElementById('existing_foto').value = data.foto || '';
        
                                console.log(`Cargando datos para proveedor: ${data.id_proveedor}, país: ${data.id_pais}, estado: ${data.id_estado}, ciudad: ${data.id_ciudad}`);
        
                                // Configurar datos seleccionados
                                document.getElementById('edit_estado').dataset.selectedEstado = data.id_estado;
                                document.getElementById('edit_ciudad').dataset.selectedCiudad = data.id_ciudad;
        
                                // Cargar pais, estado y ciudad
                                loadPaises(data.id_pais);
        
                                // Cargar marcas
                                loadMarcas(data.marcas);
        
                                $('#editar_proveedor').modal('show');
                            } else {
                                throw new Error('No se encontraron datos del proveedor.');
                            }
                        })
                        .catch(error => console.error('Error fetching proveedor:', error));
                });
            });
        }

        if (guardarCambiosBtn && formEditarProveedor) {
            guardarCambiosBtn.addEventListener('click', function() {
                const formData = new FormData(formEditarProveedor);

                fetch('../controller/Adm_Proveedores_controller.php?action=update_proveedor', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Error al actualizar el proveedor: ' + result.message);
                    }
                })
                .catch(error => console.error('Error al actualizar el proveedor:', error));
            });
        }

        attachEditButtons();
        
        //----------------------CODIGOS DE HABILITAR Y DESHABILTAR PROVEEDORES---------------
        
        function attachToggleStatusButtons() {
            document.querySelectorAll('.btn-toggle-status').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const status = this.getAttribute('data-status');
                    if (confirm('¿Estás seguro de que deseas deshabilitar este proveedor?')) {
                        const url = `../controller/Adm_Proveedores_controller.php?action=toggle_estado_proveedor`;
                        const formData = new FormData();
                        formData.append('id', userId);
                        formData.append('estado', status);

                        fetch(url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error al deshabilitar el proveedor.');
                            }
                        })
                        .catch(error => console.error('Error al deshabilitar el proveedor:', error));
                    }
                });
            });
        }
    
        attachToggleStatusButtons();
    
        const habilitarProveedorModal = document.getElementById('habilitar_proveedor');
        const selectProveedor = document.getElementById('select_proveedor');
        const habilitarBtn = document.getElementById('habilitar_btn');
    
        habilitarProveedorModal.addEventListener('show.bs.modal', function() {
            fetch('../controller/Adm_Proveedores_controller.php?action=get_inactivos')
                .then(response => response.json())
                .then(proveedores => {
                    selectProveedor.innerHTML = '<option value="">Seleccione un proveedor</option>';
                    proveedores.forEach(proveedor => {
                        const option = document.createElement('option');
                        option.value = proveedor.id_proveedor;
                        option.textContent = `${proveedor.nombre} ${proveedor.apellido_1} ${proveedor.apellido_2} - ${proveedor.telefono}`;
                        selectProveedor.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar los proveedores inactivos:', error));
        });
    
        habilitarBtn.addEventListener('click', function() {
            const proveedorId = selectProveedor.value;
            if (proveedorId) {
                fetch('../controller/Adm_Proveedores_controller.php?action=habilitar_proveedor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${proveedorId}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Error al habilitar el proveedor: ' + result.message);
                    }
                })
                .catch(error => console.error('Error al habilitar el proveedor:', error));
            } else {
                alert('Seleccione un proveedor para habilitar.');
            }
        });
    
        const buscarProveedorInput = document.getElementById('buscarProveedor');
        const listaProveedores = document.getElementById('listaProveedores');
    
        if (!buscarProveedorInput || !listaProveedores) {
            console.error('Elementos necesarios no encontrados en el DOM');
            return;
        }
    
        function fetchProveedores(query) {
            const url = '../controller/Adm_Proveedores_controller.php?action=buscar_proveedor&q=' + encodeURIComponent(query);
        
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    listaProveedores.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(proveedor => {
                            const fotoPath = proveedor.foto ? `../uploads/proveedoresimg/${proveedor.foto}` : "../img/0.jpg";
                            const proveedorItem = document.createElement('div');
                            proveedorItem.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                            proveedorItem.innerHTML = `
                                <div class="d-flex">
                                    <img src="${fotoPath}" alt="User Image" class="user-img rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div>
                                        <h5 class="mb-1">${proveedor.nombre}</h5>
                                        <p class="mb-1">ID: ${proveedor.id_proveedor}</p>
                                        <p class="mb-1">RFC: ${proveedor.rfc}</p>
                                        <p class="mb-1">Teléfono: ${proveedor.telefono}</p>
                                        <p class="mb-1">Dirección: ${proveedor.calle} ${proveedor.numero}, ${proveedor.colonia}, ${proveedor.Ciudad}, ${proveedor.Estado}, ${proveedor.Pais}</p>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm me-1 btn-view-details" data-user-id="${proveedor.id_proveedor}"><i class="fas fa-search"></i></button>
                                    <button class="btn btn-outline-warning btn-sm btn-edit-user" data-bs-toggle="modal" data-bs-target="#editar_proveedor" data-user-id="${proveedor.id_proveedor}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-outline-danger btn-sm btn-toggle-status" data-user-id="${proveedor.id_proveedor}" data-status="2"><i class="fas fa-ban"></i></button>
                                </div>
                            `;
                            listaProveedores.appendChild(proveedorItem);
                        });
                        attachEditButtons(); 
                        attachViewDetailsButtons();
                        attachToggleStatusButtons();
                    } else {
                        listaProveedores.innerHTML = '<p>No hay proveedores registrados.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching provider data:', error);
                    listaProveedores.innerHTML = '<p>Hubo un error al buscar los proveedores.</p>';
                });
        }
        
        
        
    
        buscarProveedorInput.addEventListener('input', function() {
            const query = this.value.trim();
            fetchProveedores(query);
        });
    
        // Inicializar la lista completa de proveedores al cargar la página
        fetchProveedores('');
        function attachViewDetailsButtons() {
            document.querySelectorAll('.btn-view-details').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    fetch(`../controller/Adm_Proveedores_controller.php?action=get_proveedor_detalles&id=${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                const fotoPath = data.foto ? `../uploads/proveedoresimg/${data.foto}` : "../img/0.jpg";
                                const marcasList = data.marcas.map(marca => `<li>${marca}</li>`).join('');
                                const detalleContenido = `
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>ID:</strong></div>
                                        <div class="col-md-8">${data.id_proveedor}</div>
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
                                        <div class="col-md-4"><strong>RFC:</strong></div>
                                        <div class="col-md-8">${data.rfc}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>Teléfono:</strong></div>
                                        <div class="col-md-8">${data.telefono}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>Dirección:</strong></div>
                                        <div class="col-md-8">${data.calle} ${data.numero}, ${data.colonia}, ${data.Ciudad}, ${data.Estado}, ${data.Pais}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>Marcas:</strong></div>
                                        <div class="col-md-8">
                                            <ul>${marcasList}</ul>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4"><strong>Foto:</strong></div>
                                        <div class="col-md-8"><img src="${fotoPath}" alt="Foto del Proveedor" style="width: 100px; height: 100px; object-fit: cover;"></div>
                                    </div>
                                `;
                                document.getElementById('detalleProveedorContenido').innerHTML = detalleContenido;
                                $('#detalle_proveedor').modal('show');
                            } else {
                                console.error('No se encontraron datos del proveedor.');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching provider details:', error);
                        });
                });
            });
        }
        
        // Asegúrate de llamar a esta función después de cargar la lista de proveedores
        attachViewDetailsButtons();
        

       
    });




    

document.addEventListener('DOMContentLoaded', function() {

    
          
    const paisElement = document.getElementById('pais');
    const estadoElement = document.getElementById('estado');
    const ciudadElement = document.getElementById('ciudad');
    const marcasElement = document.getElementById('marcas');
    const submitButton = document.getElementById('submit_button');
    const formAgregarProveedor = document.getElementById('form_agregar_proveedor');

    function checkFormValidity() {
        let isValid = true;
        formAgregarProveedor.querySelectorAll('input[required], select[required]').forEach(input => {
            if (!input.value || (input.pattern && !new RegExp(input.pattern).test(input.value))) {
                isValid = false;
            }
        });
        submitButton.disabled = !isValid;
    }

    function loadPaises() {
        fetch('../controller/Adm_Proveedores_controller.php?action=get_paises')
            .then(response => response.json())
            .then(paises => {
                paisElement.innerHTML = '<option value="">Selecciona un país</option>';
                paises.forEach(pais => {
                    const option = document.createElement('option');
                    option.value = pais.ID_pais;
                    option.textContent = pais.Nombre;
                    paisElement.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar los países:', error));
    }

    function loadEstados(idPais) {
        fetch(`../controller/Adm_Proveedores_controller.php?action=get_estados&id_pais=${idPais}`)
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
            .catch(error => console.error('Error al cargar los estados:', error));
    }

    function loadCiudades(idEstado) {
        fetch(`../controller/Adm_Proveedores_controller.php?action=get_ciudades&id_estado=${idEstado}`)
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
            .catch(error => console.error('Error al cargar las ciudades:', error));
    }

    function loadMarcas() {
        fetch('../controller/Adm_Proveedores_controller.php?action=get_marcas')
            .then(response => response.json())
            .then(marcas => {
                marcasElement.innerHTML = '';
                marcas.forEach(marca => {
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'marcas[]';
                    checkbox.value = marca.ID_marca;
                    checkbox.id = 'marca_' + marca.ID_marca;

                    const label = document.createElement('label');
                    label.htmlFor = 'marca_' + marca.ID_marca;
                    label.textContent = marca.Nombre_marca;

                    const div = document.createElement('div');
                    div.appendChild(checkbox);
                    div.appendChild(label);

                    marcasElement.appendChild(div);
                });
            })
            .catch(error => console.error('Error al cargar las marcas:', error));
    }

    paisElement.addEventListener('change', function() {
        const idPais = this.value;
        if (idPais) {
            loadEstados(idPais);
        } else {
            estadoElement.innerHTML = '<option value="">Selecciona un estado</option>';
            ciudadElement.innerHTML = '<option value="">Selecciona una ciudad</option>';
        }
        checkFormValidity();
    });

    estadoElement.addEventListener('change', function() {
        const idEstado = this.value;
        if (idEstado) {
            loadCiudades(idEstado);
        } else {
            ciudadElement.innerHTML = '<option value="">Selecciona una ciudad</option>';
        }
        checkFormValidity();
    });

    formAgregarProveedor.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', checkFormValidity);
        input.addEventListener('change', checkFormValidity);
    });

    formAgregarProveedor.addEventListener('submit', function(event) {
        if (!formAgregarProveedor.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        formAgregarProveedor.classList.add('was-validated');
    });

    loadPaises();
    loadMarcas();
    checkFormValidity();
});




//VALIDACIONES AGREGAR Y EDITAR PROVEEDOR//
document.addEventListener('DOMContentLoaded', (event) => {
    // Función para permitir solo letras
    function allowOnlyLetters(e) {
        var charCode = e.charCode || e.keyCode;
        if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/.test(String.fromCharCode(charCode))) {
            e.preventDefault();
        }
    }

    // Función para permitir solo números y limitar la cantidad de dígitos
    function allowOnlyNumbersAndLimit(e, input, limit) {
        var charCode = e.charCode || e.keyCode;
        if (!/[0-9]/.test(String.fromCharCode(charCode)) || input.value.length >= limit) {
            e.preventDefault();
        }
    }

    // Función para validar el RFC
    function validateRFC(rfc) {
        var rfcRegex = /^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/;
        return rfcRegex.test(rfc) && rfc.length === 13;
    }

    // Añadir eventos para los campos de solo letras
    document.getElementById("nombre").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("primer_apellido").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("segundo_apellido").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("edit_nombre").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("edit_primer_apellido").addEventListener('keypress', allowOnlyLetters);
    document.getElementById("edit_segundo_apellido").addEventListener('keypress', allowOnlyLetters);

    // Añadir eventos para los campos de solo números y limitar la cantidad de dígitos
    document.getElementById("telefono").addEventListener('keypress', function(e) {
        allowOnlyNumbersAndLimit(e, this, 10);
    });
    document.getElementById("edit_telefono").addEventListener('keypress', function(e) {
        allowOnlyNumbersAndLimit(e, this, 10);
    });
    document.getElementById("codigo_postal").addEventListener('keypress', function(e) {
        allowOnlyNumbersAndLimit(e, this, 5);
    });
    document.getElementById("edit_codigo_postal").addEventListener('keypress', function(e) {
        allowOnlyNumbersAndLimit(e, this, 5);
    });

    // Función para mostrar mensajes de error
    function showError(elementId, message) {
        var errorElement = document.getElementById(elementId);
        errorElement.innerText = message;
        errorElement.style.display = 'block';
    }

    // Función para limpiar mensajes de error
    function clearErrors(formId) {
        var form = document.getElementById(formId);
        var errorElements = form.querySelectorAll('.error-message');
        errorElements.forEach(function(element) {
            element.innerText = '';
            element.style.display = 'none';
        });
    }

    // Validación en el envío del formulario de agregar proveedor
    document.getElementById('form_agregar_proveedor').addEventListener('submit', function(e) {
        e.preventDefault(); // Detener el envío del formulario inicialmente
        clearErrors('form_agregar_proveedor');
        
        var nombre = document.getElementById("nombre").value.trim();
        var primerApellido = document.getElementById("primer_apellido").value.trim();
        var segundoApellido = document.getElementById("segundo_apellido").value.trim();
        var telefono = document.getElementById("telefono").value.trim();
        var rfc = document.getElementById("rfc").value.trim();
        var calle = document.getElementById("calle").value.trim();
        var numero = document.getElementById("numero").value.trim();
        var codigoPostal = document.getElementById("codigo_postal").value.trim();
        var cruzamientos = document.getElementById("cruzamientos").value.trim();
        var colonia = document.getElementById("colonia").value.trim();
        var pais = document.getElementById("pais").value;
        var estado = document.getElementById("estado").value;
        var ciudad = document.getElementById("ciudad").value;

        var formValid = true;

        if (!nombre) {
            showError('nombreError', "El campo de nombre no puede estar vacío.");
            formValid = false;
        }

        if (!primerApellido) {
            showError('primerApellidoError', "El campo de primer apellido no puede estar vacío.");
            formValid = false;
        }

        if (!segundoApellido) {
            showError('segundoApellidoError', "El campo de segundo apellido no puede estar vacío.");
            formValid = false;
        }

        if (!telefono || !/^999\d{7}$/.test(telefono)) {
            showError('telefonoError', "El número de teléfono debe tener 10 dígitos y comenzar con 999.");
            formValid = false;
        }

        if (!rfc || !validateRFC(rfc)) {
            showError('rfcError', "El RFC debe tener un formato válido.");
            formValid = false;
        }

        if (!calle) {
            showError('calleError', "El campo de calle no puede estar vacío.");
            formValid = false;
        }

        if (!numero) {
            showError('numeroError', "El campo de número no puede estar vacío.");
            formValid = false;
        }

        if (!codigoPostal || codigoPostal.length !== 5) {
            showError('codigoPostalError', "El código postal debe tener exactamente 5 caracteres.");
            formValid = false;
        }

        if (!cruzamientos) {
            showError('cruzamientosError', "El campo de cruzamientos no puede estar vacío.");
            formValid = false;
        }

        if (!colonia) {
            showError('coloniaError', "El campo de colonia no puede estar vacío.");
            formValid = false;
        }

        if (!pais) {
            showError('paisError', "Debe seleccionar un país.");
            formValid = false;
        }

        if (!estado) {
            showError('estadoError', "Debe seleccionar un estado.");
            formValid = false;
        }

        if (!ciudad) {
            showError('ciudadError', "Debe seleccionar una ciudad.");
            formValid = false;
        }

        if (formValid) {
            this.submit(); // Enviar el formulario si todas las validaciones son correctas
        } else {
            alert("Por favor, corrija los errores antes de enviar el formulario.");
            return false; // Detener el envío del formulario
        }
    });

    // Validación en el envío del formulario de editar proveedor
    document.getElementById('guardar_cambios').addEventListener('click', function(e) {
        e.preventDefault(); // Detener el envío del formulario inicialmente
        clearErrors('form_editar_proveedor');
        
        var nombre = document.getElementById("edit_nombre").value.trim();
        var primerApellido = document.getElementById("edit_primer_apellido").value.trim();
        var segundoApellido = document.getElementById("edit_segundo_apellido").value.trim();
        var telefono = document.getElementById("edit_telefono").value.trim();
        var rfc = document.getElementById("edit_rfc").value.trim();
        var calle = document.getElementById("edit_calle").value.trim();
        var numero = document.getElementById("edit_numero").value.trim();
        var codigoPostal = document.getElementById("edit_codigo_postal").value.trim();
        var cruzamientos = document.getElementById("edit_cruzamientos").value.trim();
        var colonia = document.getElementById("edit_colonia").value.trim();
        var pais = document.getElementById("edit_pais").value;
        var estado = document.getElementById("edit_estado").value;
        var ciudad = document.getElementById("edit_ciudad").value;

        var formValid = true;

        if (!nombre) {
            showError('nombreEditError', "El campo de nombre no puede estar vacío.");
            formValid = false;
        }

        if (!primerApellido) {
            showError('primerApellidoEditError', "El campo de primer apellido no puede estar vacío.");
            formValid = false;
        }

        if (!segundoApellido) {
            showError('segundoApellidoEditError', "El campo de segundo apellido no puede estar vacío.");
            formValid = false;
        }

        if (!telefono || !/^999\d{7}$/.test(telefono)) {
            showError('telefonoEditError', "El número de teléfono debe tener 10 dígitos y comenzar con 999.");
            formValid = false;
        }

        if (!rfc || !validateRFC(rfc)) {
            showError('rfcEditError', "El RFC debe tener un formato válido.");
            formValid = false;
        }

        if (!calle) {
            showError('calleEditError', "El campo de calle no puede estar vacío.");
            formValid = false;
        }

        if (!numero) {
            showError('numeroEditError', "El campo de número no puede estar vacío.");
            formValid = false;
        }

        if (!codigoPostal || codigoPostal.length !== 5) {
            showError('codigoPostalEditError', "El código postal debe tener exactamente 5 caracteres.");
            formValid = false;
        }

        if (!cruzamientos) {
            showError('cruzamientosEditError', "El campo de cruzamientos no puede estar vacío.");
            formValid = false;
        }

        if (!colonia) {
            showError('coloniaEditError', "El campo de colonia no puede estar vacío.");
            formValid = false;
        }

        if (!pais) {
            showError('paisEditError', "Debe seleccionar un país.");
            formValid = false;
        }

        if (!estado) {
            showError('estadoEditError', "Debe seleccionar un estado.");
            formValid = false;
        }

        if (!ciudad) {
            showError('ciudadEditError', "Debe seleccionar una ciudad.");
            formValid = false;
        }

        if (formValid) {
            document.getElementById('form_editar_proveedor').submit(); // Enviar el formulario si todas las validaciones son correctas
        } else {
            alert("Por favor, corrija los errores antes de enviar el formulario.");
            return false; // Detener el envío del formulario
        }
    });
});




})();





