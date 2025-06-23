<!-- Modal -->
<div class="modal fade" id="modalFormViaticosGenerales" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl-custom">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nueva Categoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form id="formViaticoGeneral" name="formViaticoGeneral">
          <input type="hidden" id="idviatico" name="idviatico" value="">
          <input type="hidden" id="usuarioid" name="usuarioid" value="<?= $_SESSION['userData']['id_usuario'] ?>">
             <input type="hidden" id="" name="" value="">
          <input type="hidden" id="idjefedirecto" name="idjefedirecto" > 
          <input type="hidden" id="idjefedirectosuperior" name="idjefedirectosuperior" > 
           <input type="hidden" id="email_jefe_directo" name="email_jefe_directo" value="<?= $_SESSION['userData']['email_jefe'] ?>">
          <input class="form-control" id="fechacreacion" name="fechacreacion" type="datetime-local" readonly style="display: none;">

          <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">

              <div class="row">
                <div class="col-6 mb-3">
                  <label class="control-label">Nombre del Colaborador <span class="required">*</span></label>
                  <input class="form-control" id="nombreusuario" name="nombreusuario" type="text" required
                    value="<?= $_SESSION['userData']['nombre_1'] . ' ' . $_SESSION['userData']['apellido_paterno'] . ' ' . $_SESSION['userData']['apellido_materno'] ?>"
                    readonly>
                </div>
                <div class="col-6 mb-3">
                  <label for="listArea">Área que solicita <span class="required">*</span></label>
                  <select class="form-control" data-live-search="true" id="listArea" name="listArea"></select>
                </div>
              </div>


              <div class="row">
                <div class="col-6 mb-3">
                  <label for="listCentrosCosto">Centro de Costo <span class="required">*</span></label>
                  <select class="form-control" data-live-search="true" id="listCentrosCosto" onchange="consultarSaldo(this.value)" name="listCentrosCosto" required></select>
                </div>
                <div class="col-3 mb-3">
                  <label class="control-label">Fecha de Salida <span class="required">*</span></label>
                  <input class="form-control" id="fecha_salida" name="fecha_salida" type="date" required>
                </div>

                <div class="col-3 mb-3">
                  <label class="control-label">Fecha de Regreso <span class="required">*</span></label>
                  <input class="form-control" id="fecha_regreso" name="fecha_regreso" type="date" required>
                </div>

              </div>


              <div class="row">
                <div class="col-6 mb-3">

                </div>
                <div class="col-6 mb-3">
                  <h2 class="infoDiasSeleccionados" id="infoDias"></h2>
                  <input type="hidden" id="inputDias" name="inputDias">
                </div>



              </div>







              <div class="row">
                <div class="col-6 mb-3">
                  <label for="motivo">Motivo<span class="required">*</span></label>
                  <select class="form-control" data-live-search="true" id="motivo" name="motivo">
                    <option value="0">--Selecciona--</option>
                    <option value="Entrega de vehículos a cliente">Entrega de vehículos a cliente</option>
                    <option value="Revisión técnica en sitio">Revisión técnica en sitio</option>
                    <option value="Atención a garantía fuera de sede">Atención a garantía fuera de sede</option>
                    <option value="Participación en ferias o exposiciones automotrices">Participación en ferias o exposiciones automotrices</option>
                    <option value="Supervisión de sucursales o talleres">Supervisión de sucursales o talleres</option>
                    <option value="Negociaciones o visitas con proveedores">Negociaciones o visitas con proveedores</option>
                    <option value="Revisión o auditoría de flotilla">Revisión o auditoría de flotilla</option>
                    <option value="Soporte a ventas en otras sucursales">Soporte a ventas en otras sucursales</option>
                    <option value="Recolección o traslado de unidades">Recolección o traslado de unidades</option>
                    <option value="Visita a clientes corporativos">Visita a clientes corporativos</option>
                    <option value="Pruebas de manejo o evaluación de unidades">Pruebas de manejo o evaluación de unidades</option>
                    <option value="Trámites administrativos o legales fuera de la ciudad">Trámites administrativos o legales fuera de la ciudad</option>
                    <option value="Revisión de instalaciones (logística o seguridad)">Revisión de instalaciones (logística o seguridad)</option>
                  </select>
                </div>
                <div class="col-6 mb-3">
                  <label for="listArea">Lugar de destino <span class="required">*</span></label>
                  <input class="form-control" id="lugar_destino" name="lugar_destino" type="text" required>
                </div>
              </div>



              <div class="mb-3">
                <label class="control-label">Ingresa la descripción de las actividades a realizar. <span class="required">*</span></label>
                <textarea class="form-control" id="txtDescripcion" name="txtDescripcion"></textarea>
              </div>
            </div>

            <!-- Columna Derecha (Tabla) -->
            <div class="col-md-6">
              <label>Conceptos <span class="required">*</span></label>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Concepto</th>
                    <th>Solicitud diaria</th>
                    <th>Subtotal</th>
                    <th>Comentario</th>
                    <!-- <th>Acción</th> -->
                  </tr>
                </thead>
                <tbody id="tabla-conceptos">
                  <!-- Dinámico -->
                </tbody>
                <tfoot>
                  <tr>
                            <td>
                      <!-- <input type="text" class="form-control" readonly value="Saldo disponible:">-->
                      <!-- <input type="hidden" class="form-control mt-1" id="saldoDisponible"> -->
                    </td>
                    <td>
                      <!-- <input type="text" class="form-control" readonly value="Saldo disponible:">-->
                      <input type="hidden" class="form-control mt-1" id="saldoDisponible">
                    </td>
                    <td>
                      <input type="text" class="form-control" readonly value="Total:">
                      <input type="text" class="form-control mt-1" id="total" name="total" readonly>
                      <input type="hidden" id="total_limpio" name="total_limpio">
                    </td>
                    <td></td>

                  </tr>
                </tfoot>
              </table>





              <!-- Botones centrados en 6 columnas cada uno -->
              <div class="row text-center">
                <div class="col-6">
                  <button id="btnActionForm" class="btn btn-primary w-100" type="submit">
                    <i class="fa fa-fw fa-lg fas fa-paper-plane"></i>
                    <span id="btnText">Solicitar</span>
                  </button>
                </div>
                <div class="col-6">
                  <button class="btn btn-danger w-100" type="button" data-dismiss="modal">
                    <i class="fa fa-fw fa-lg fa-times-circle"></i>
                    Cerrar
                  </button>
                </div>
              </div>





            </div>




          </div>


        </form>




      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewCategoria" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos de la categoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>ID:</td>
              <td id="celId"></td>
            </tr>
            <tr>
              <td>Nombres:</td>
              <td id="celNombre"></td>
            </tr>a
            <tr>
              <td>Descripción:</td>
              <td id="celDescripcion"></td>
            </tr>
            <tr>
              <td>Estado:</td>
              <td id="celEstado"></td>
            </tr>
            <tr>
              <td>Foto:</td>
              <td id="imgCategoria"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>