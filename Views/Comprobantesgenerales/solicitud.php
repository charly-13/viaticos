<?php headerAdmin($data);
getModal('modalValidacionuno', $data);
getModal('modalValidacionJefaturaSuperior', $data);
getModal('modalValidacionCompras', $data);

//     echo '<pre>';
// print_r($_SESSION['userData']);
// echo '</pre>';
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-file-text-o"></i> <?= $data['page_title'] ?></h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/viaticosgenerales"> Viáticos</a></li>
    </ul>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="tile shadow rounded-3 p-4">

        <section id="sPedido" class="invoice">
          <div class="row mb-4">
            <div class="col-6">
              <h2 class="page-header">
                <img src="<?= media(); ?>/images/ldr.png" style="height: 100px;">
              </h2>
            </div>
            <div class="col-6 text-end">
              <h5>FOLIO: 101010</h5>
            </div>
          </div>

          <h6 style="background-color: #d16032; color: white; font-size: 15px; padding: 25px;">
            COMPROBANTES DE GASTOS
          </h6>

          <br>
          <?php
          if (!empty($data['arrSolicitudComprobantes'])) {
            $viaticosComprobantes = $data['arrSolicitudComprobantes']['viaticosComprobantes'];

            // Agrupar por conceptoid
            $comprobantesPorConcepto = [];
            foreach ($viaticosComprobantes as $comprobante) {
              $conceptoId = $comprobante['conceptoid'] ?? 0;
              $comprobantesPorConcepto[$conceptoId]['concepto'] = $comprobante['concepto'];
              $comprobantesPorConcepto[$conceptoId]['items'][] = $comprobante;
            }
          ?>

            <?php foreach ($comprobantesPorConcepto as $conceptoId => $grupo) {  ?>
              <div class="concept-header my-4 fw-bold fs-5" style="color:#ffffff; background: #343a40;">
                <i class="fas fa-folder-open"></i> Concepto: <?= $grupo['concepto'] ?>
              </div>

              <?php foreach ($grupo['items'] as $comprobante) { ?>
                <div class="concept-content mb-4 p-3 border rounded">

                  <div class="day-title fw-bold mb-2" style="font-size: 18px; background: #ce5e3a33;">
                    DÍA SOLICITADO: <?= $comprobante['fecha'] ?? 'Fecha no disponible' ?>
                  </div>

                  <div class="comprobante-item" data-amount="<?= $comprobante['total'] ?>">
                    <div class="comprobante-info d-flex justify-content-between flex-wrap">

                      <div class="info-left mb-2">
                        <div><strong style="font-size: 18px;">Monto Total: <?= formatMoney($comprobante['total']) ?></strong></div>
                        <div class="comentarios"><?= $comprobante['comentarios'] ?? 'Sin descripción' ?></div>
                        <?php if ($comprobante['estado_documento'] == 1) { ?>
                          <div class="comprobante-status pendiente">⏳ Pendiente</div>

                        <?php } else if ($comprobante['estado_documento'] == 2) { ?>
                          <div class="comprobante-status rechazado">❌ Rechazado</div>
                        <?php } else if ($comprobante['estado_documento'] == 3) { ?>
                          <div class="comprobante-status aprobado">✅ Aprobado</div>
                        <?php } ?>


                   <?php if (!in_array($comprobante['estado_documento'], ['2', '3'])) { ?>
                          <div class="status-comment"> </div>
                         <?php }else{ ?>

                  <div class="status-comment"> <?= $comprobante['comentario_compras'] ?></div>
                          <?php } ?>
                      </div>

                      <div class="document-links mb-2">

                        <?php if (!empty($comprobante['rutapdf'])) { ?>
                          <a href="<?= media() . '/uploads/pdf/' . $comprobante['rutapdf'] ?>" target="_blank" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
                        <?php } else { ?>
                          <button class="btn btn-outline-danger btn-sm disabled"><i class="fas fa-file-pdf"></i> Sin PDF</button>
                        <?php } ?>


                        <a href="<?= media() . '/uploads/xml/' . $comprobante['rutaxml'] ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-code"></i> XML</a>
                      </div>
                    </div>

                    <div class="factura-datos bg-light p-2 rounded border mt-2 mb-3 small">
                      <div><strong>UUID:</strong> <?= $comprobante['uuid'] ?? 'N/A' ?></div>
                      <div><strong>RFC Emisor:</strong> <?= $comprobante['rfcemisor'] ?? 'N/A' ?></div>
                      <!-- <div><strong>RFC Receptor:</strong> <?= $comprobante['rfcreceptor'] ?? 'N/A' ?></div> -->
                      <div><strong>Subtotal:</strong> <?= isset($comprobante['subtotal_comprobante']) ? formatMoney($comprobante['subtotal_comprobante']) : '$0.00' ?></div>
                      <div><strong>Total:</strong> <?= isset($comprobante['total']) ? formatMoney($comprobante['total']) : '$0.00' ?></div>
                      <div><strong>Fecha factura:</strong> <?= $comprobante['fechafactura'] ?? 'N/A' ?></div>
                    </div>

<?php  if($_SESSION['userData']['id_area']=="32" || $_SESSION['userData']['email_usuario']=="carlos.cruz@ldrsolutions.com.mx") {?>
                    <?php if (!in_array($comprobante['estado_documento'], ['2', '3'])) { ?>
                      <div class="action-section">
                        <textarea class="form-control mb-2 comment-area" placeholder="Agregar un comentario (opcional)..."></textarea>
                        <div>

                          <button class="btn btn-success btn-sm me-2" onclick="evaluarComprobante(this, '3', <?= $comprobante['idcomprobante'] ?>)">
                            <i class="fas fa-check"></i> Aprobar
                          </button>
                          <button class="btn btn-danger btn-sm" onclick="evaluarComprobante(this, '2', <?= $comprobante['idcomprobante'] ?>)">
                            <i class="fas fa-times"></i> Rechazar
                          </button>

                        </div>
                      </div>

                    <?php } ?>
                     <?php } ?>

                  </div>
                </div>
              <?php } ?>
            <?php } ?>

          <?php } else {
            echo '<p>Datos no encontrados</p>';
          } ?>


          <div class="concept-total-container fw-bold fs-5 mt-4">
            Total de facturas: <span id="total-alimentacion">$0.00</span>
          </div>

        </section>

      </div>
    </div>
  </div>
</main>
<?php footerAdmin($data); ?>