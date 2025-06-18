<!-- Modal -->
<div class="modal fade" id="modalFormAddComprobantes" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" >
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModalComprobante">Nueva Categoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form  name="formViaticoAprobacionJefaturaSuperior" class="form-horizontal" enctype="multipart/form-data" method="POST">
              <input type="hidden" id="totalGastosFactura" name="totalGastosFactura">
            <input type="hidden" id="idconcepto" name="idconcepto">
                   <input type="hidden" id="idviatico" name="idviatico">

             <div id="contenedorDias"></div>

              
   
            </form>
      </div>
                 <!-- <div class="tile-footer">
                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar Comprobantes</span></button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
              </div> -->

            <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
  <div id="resumenTotalGastos" class="fw-bold text-primary fs-5" style="color:#d16032; font-size:1.25rem;">Total: $0.00</div>
  
  <div class="d-flex gap-2">
    <button class="btn btn-success" onclick="guardarComprobantes()">Guardar comprobantes</button>
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cerrar</button>
  </div>
</div>
    </div>
  </div>
</div>



