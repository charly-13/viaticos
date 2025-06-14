<!-- Modal -->
<div class="modal fade" id="modalFormValidacionuno" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nueva Categoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="formViaticoAprobacionuno" name="formViaticoAprobacionuno" class="form-horizontal">
            <input type="hidden" id="idviatico" name="idviatico">
            <input type="hidden" id="correo_solicitante" name="correo_solicitante">
            <input type="hidden" id="email_jefe_superior" name="email_jefe_superior">
              <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
              <div class="row">
                <div class="col-md-12">
                                        <div class="form-group">
                        <label for="exampleSelect1">Seleccione el resultado de la revisión <span class="required">*</span></label>
                        <select class="form-control selectpicker" id="listStatus" name="listStatus" required="">
                          
                         <option value="0">--Seleccione--</option>
                         <option value="5">Aprobar</option>
                         <option value="4">Rechazar</option>
                        </select>
                    </div> 

                    <div class="form-group">
                      <label class="control-label">Comentarios <span class="required">*</span></label>
                      <textarea class="form-control" id="txtComentarios" name="txtComentarios" rows="4" placeholder="Escriba aquí observaciones o motivos de su decisión" required=""></textarea>
                    </div>
 
                </div>

              </div>
              
              <div class="tile-footer">
                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Enviar decisión</span></button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
              </div>
            </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewCategoria" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" >
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
            </tr>
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

