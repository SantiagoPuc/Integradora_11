document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM completamente cargado y analizado");

    // Manejar la eliminación de cliente
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
                console.log('Contenido del modal:', modalElement.innerHTML);
                // Utilizar Bootstrap JS para mostrar el modal
                const modalEliminar = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                modalEliminar.show();
                console.log('Intentando mostrar el modal de eliminación');
                console.log('Clases del modal:', modalElement.classList);
                console.log('Estilo display del modal:', modalElement.style.display);
            } else {
                console.error('Elemento con id "eliminar_cliente" no encontrado');
            }
        });
    });

    document.getElementById('confirmar_eliminar').addEventListener('click', function() {
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
});
