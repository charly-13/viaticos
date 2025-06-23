<!-- Modal -->
<div class="modal fade" id="modalFormValidacionCompras" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModalCompras">Compras</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="formViaticoAprobacionCompras" name="formViaticoAprobacionCompras" class="form-horizontal">
            <input type="hidden" id="idviatico_comp" name="idviatico_comp">
                 <input type="text" id="correo_solicitante_comp" name="correo_solicitante_comp">
            <!-- <input type="text" id="email_jefe_superior" name="email_jefe_superior"> -->
              <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
              <div class="row">
                <div class="col-md-12">
                                        <div class="form-group">
                        <label for="exampleSelect1">Seleccione el resultado de la revisión <span class="required">*</span></label>
                        <select class="form-control selectpicker" id="listStatus_comp" name="listStatus_comp" required="" onchange="gestionCompras(this.value)">
                          
                         <option value="0">--Seleccione--</option> 
                         <option value="10" selected>Finalizar</option>
                         <!-- <option value="9">Rechazar</option> -->
                        </select>
                    </div> 

                    <div class="form-group">
  <label for="comprobante_pago" class="form-label">Subir Comprobante de Pago (PDF o Imagen)</label>
  <input class="form-control" type="file" id="comprobante_pago" name="comprobante_pago" accept=".pdf,.jpg,.jpeg,.png" required>
  <div class="form-text">Formatos permitidos: PDF, JPG, PNG</div>
</div>

                    <div class="form-group">
                      <label class="control-label">Comentarios <span class="required">*</span></label>
                      <textarea class="form-control" id="txtComentarios_comp" name="txtComentarios_comp" rows="4" placeholder="Escribe aquí tus comentarios..." ></textarea>
                    </div>

<!-- 
                                <div class="form-group" id="divDocumentos" style="display: none;">
              <label for="documentos" class="form-label">Adjuntar Capturas o Documentos de la transferencia</label>
              <input class="form-control" type="file" id="documentos" multiple accept=".pdf,.jpg,.png,.jpeg,.doc,.docx">
              <div class="form-text">Puedes seleccionar múltiples archivos. Format: PDF, JPG, PNG, DOC, DOCX</div>
            </div> -->

              <!-- Vista previa de archivos -->
         
 
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



