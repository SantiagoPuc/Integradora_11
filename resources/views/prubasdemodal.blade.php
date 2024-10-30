<!-- Modal Agregar Cliente -->
<div class="modal fade" id="agregar_cliente" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="registerModalLabel">Registrar Cliente</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group row">
                <label for="id" class="col-sm-2 col-form-label">ID:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="id" placeholder="Ingrese el ID">
                </div>
              </div>
              <div class="text-center mb-4">
                <img src="/img/User.JPG" alt="Cliente" class="rounded-circle" width="100">
              </div>
              <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="nombre" placeholder="Ingrese el nombre del cliente">
                </div>
              </div>
              <div class="form-group row">
                <label for="primer-apellido" class="col-sm-2 col-form-label">Primer apellido:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="primer-apellido" placeholder="Ingrese el primer apellido">
                </div>
              </div>
              <div class="form-group row">
                <label for="segundo-apellido" class="col-sm-2 col-form-label">Segundo apellido:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="segundo-apellido" placeholder="Ingrese el segundo apellido">
                </div>
              </div>
              <div class="form-group row">
                <label for="telefono" class="col-sm-2 col-form-label">Número de teléfono:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="telefono" placeholder="Ingrese el número de teléfono">
                </div>
              </div>
              <div class="form-group row">
                <label for="correo" class="col-sm-2 col-form-label">Correo electrónico:</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="correo" placeholder="Ingrese el correo electrónico">
                </div>
              </div>
              <div class="form-group row">
                <label for="direccion" class="col-sm-2 col-form-label">Dirección:</label>
              </div>
              <div class="form-group row">
                <label for="calle" class="col-sm-2 col-form-label">Calle:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="calle" placeholder="Ingrese la calle">
                </div>
                <label for="numero" class="col-sm-2 col-form-label">Número:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="numero" placeholder="Ingrese el número">
                </div>
              </div>
              <div class="form-group row">
                <label for="codigo-postal" class="col-sm-2 col-form-label">Código postal:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="codigo-postal" placeholder="Ingrese el código postal">
                </div>
                <label for="pais" class="col-sm-2 col-form-label">País:</label>
                <div class="col-sm-4">
                  <select class="form-control" id="pais">
                    <option>México</option>
                    <option>Otro</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="estado" class="col-sm-2 col-form-label">Estado:</label>
                <div class="col-sm-4">
                  <select class="form-control" id="estado">
                    <option>Yucatán</option>
                    <option>Otro</option>
                  </select>
                </div>
                <label for="ciudad" class="col-sm-2 col-form-label">Ciudad:</label>
                <div class="col-sm-4">
                  <select class="form-control" id="ciudad">
                    <option>Mérida</option>
                    <option>Otra</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="cruzamientos" class="col-sm-2 col-form-label">Cruzamientos:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="cruzamientos" placeholder="Ingrese los cruzamientos">
                </div>
                <label for="colonia" class="col-sm-2 col-form-label">Colonia:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="colonia" placeholder="Ingrese la colonia">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Registrar</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  <!-- Modal Actualizar Cliente -->
  <div class="modal fade" id="actualizar_cliente" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="registerModalLabel2">Actualizar Cliente</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group row">
                <label for="id" class="col-sm-2 col-form-label">ID:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="id2" placeholder="Ingrese el ID">
                </div>
              </div>
              <div class="text-center mb-4">
                <img src="/img/User.JPG" alt="Cliente" class="rounded-circle" width="100">
              </div>
              <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="nombre2" placeholder="Ingrese el nombre del cliente">
                </div>
              </div>
              <div class="form-group row">
                <label for="primer-apellido" class="col-sm-2 col-form-label">Primer apellido:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="primer-apellido2" placeholder="Ingrese el primer apellido">
                </div>
              </div>
              <div class="form-group row">
                <label for="segundo-apellido" class="col-sm-2 col-form-label">Segundo apellido:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="segundo-apellido2" placeholder="Ingrese el segundo apellido">
                </div>
              </div>
              <div class="form-group row">
                <label for="telefono" class="col-sm-2 col-form-label">Número de teléfono:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="telefono2" placeholder="Ingrese el número de teléfono">
                </div>
              </div>
              <div class="form-group row">
                <label for="correo" class="col-sm-2 col-form-label">Correo electrónico:</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="correo2" placeholder="Ingrese el correo electrónico">
                </div>
              </div>
              <div class="form-group row">
                <label for="direccion" class="col-sm-2 col-form-label">Dirección:</label>
              </div>
              <div class="form-group row">
                <label for="calle" class="col-sm-2 col-form-label">Calle:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="calle2" placeholder="Ingrese la calle">
                </div>
                <label for="numero" class="col-sm-2 col-form-label">Número:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="numero2" placeholder="Ingrese el número">
                </div>
              </div>
              <div class="form-group row">
                <label for="codigo-postal" class="col-sm-2 col-form-label">Código postal:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="codigo-postal2" placeholder="Ingrese el código postal">
                </div>
                <label for="pais" class="col-sm-2 col-form-label">País:</label>
                <div class="col-sm-4">
                  <select class="form-control" id="pais2">
                    <option>México</option>
                    <option>Otro</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="estado" class="col-sm-2 col-form-label">Estado:</label>
                <div class="col-sm-4">
                  <select class="form-control" id="estado2">
                    <option>Yucatán</option>
                    <option>Otro</option>
                  </select>
                </div>
                <label for="ciudad" class="col-sm-2 col-form-label">Ciudad:</label>
                <div class="col-sm-4">
                  <select class="form-control" id="ciudad2">
                    <option>Mérida</option>
                    <option>Otra</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="cruzamientos" class="col-sm-2 col-form-label">Cruzamientos:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="cruzamientos2" placeholder="Ingrese los cruzamientos">
                </div>
                <label for="colonia" class="col-sm-2 col-form-label">Colonia:</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="colonia2" placeholder="Ingrese la colonia">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  </div>

  <!-- Modal Eliminar Cliente -->
  <div class="modal fade" id="eliminar_cliente" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteUserModalLabel">Confirmar Eliminación</h5>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>¿Estás seguro de que deseas eliminar este cliente?</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-danger">Eliminar</button>
              </div>
          </div>
      </div>
  </div>