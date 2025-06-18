<!-- Modal -->
<div class="modal fade" id="modalFormCcostos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Centro de Costo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formCcostos" name="formCcostos" class="form-horizontal">
          <input type="hidden" id="idcentro" name="idcentro" value="">
          <input type="hidden" id="creado_por" name="creado_por" value="<?= $_SESSION['userData']['id_colaborador'] ?>">
          <input type="hidden" id="actualizado_por" name="actualizado_por" value="<?= $_SESSION['userData']['id_colaborador'] ?>">
          <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Nombre<span class="required">*</span></label>
                <input class="form-control" id="txtNombre" name="txtNombre" type="text" required="" placeholder="ej. Ventas CDMX" autocomplete="off">
              </div>
              <!-- <div class="form-group" style="display: none;">
                <label class="control-label">Responsable del Centro<span class="required">*</span></label>
                <input class="form-control" id="txtResponsable" value="" name="txtResponsable" type="text" required="" autocomplete="off">
              </div> -->

              <div class="row">
                <div class="form-group col-md-6">
                  <label class="control-label">Presupuesto Mensual <span class="required">*</span></label>
                  <input class="form-control" id="txtPresupuestoMensual" name="txtPresupuestoMensual" type="text" required="" onchange="formatearMiles(this)" autocomplete="off">
                </div>
                <div class="form-group col-md-6">
                  <label class="control-label">Presupuesto Anual <span class="required">*</span></label>
                  <input class="form-control" id="txtPresupuestoAnual" name="txtPresupuestoAnual" type="text" required="" onchange="formatearMiles(this)" autocomplete="off">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label">Fecha de Creación<span class="required">*</span></label>
                <input class="form-control" id="fechacreacion" name="fechacreacion" type="datetime-local" readonly>
              </div>


              <div class="row">
                <div class="form-group col-md-12">
                  <label for="listStatus">Estado <span class="required">*</span></label>
                  <select class="form-control " id="listStatus" name="listStatus" required="" disabled>
                    <option value="1" selected>Activo</option>
                    <option value="2">Inactivo</option>
                  </select>
                </div>  
              </div>

            </div>
            



            <div class="col-md-6">
              <div class="form-group ">
                <label for="listEmpresa">Empresa <span class="required">*</span></label>
                <select class="form-control" data-live-search="true" id="listEmpresa" name="listEmpresa" required=""></select>
              </div>

              
                       <div class="form-group " id="groupDireccion" style="display: none;">
                <label for="listDireccion">Dirección <span class="required">*</span></label>
                <select class="form-control" data-live-search="true" id="listDireccion" name="listDireccion" required=""></select>
              </div>


                       <div class="form-group " id="groupArea" style="display: none;">
                <label for="listArea">Área asignada <span class="required">*</span></label>
                <select class="form-control" data-live-search="true" id="listArea" name="listArea" required=""></select>
              </div>



            </div>



          </div>


          <div class="tile-footer">
            <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewCcosto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Detalle del Centro de Costo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Nombre:</td>
              <td id="celNombre"></td>
            </tr>
            <tr>
              <td>Empresa asignada:</td>
              <td id="celEmpresa"></td>
            </tr>
                        <tr>
              <td>Direccion asignada:</td>
              <td id="celDireccion"></td>
            </tr>

                        <tr>
              <td>Área asignada:</td>
              <td id="celArea"></td>
            </tr>
            <!-- <tr>
              <td>Responsable:</td>
              <td id="celResponsable"></td>
            </tr> -->
            <tr>
              <td>Presupuesto Mensual:</td>
              <td id="celPresupuestoanual"></td>
            </tr>

            <tr>
              <td>Presupuesto Anual:</td>
              <td id="celPresupuestomensual"></td>
            </tr>

            <tr>
              <td>Fecha de Registro:</td>
              <td id="celFechacreacion"></td>
            </tr>

            <tr>
              <td>Creado Por:</td>
              <td id="celCreado_por"></td>
            </tr>

            <!-- <tr>
              <td>Actualizado Por:</td>
              <td id="celActualizado_por"></td>
            </tr> -->



          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>