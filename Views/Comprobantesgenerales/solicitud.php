<?php headerAdmin($data);
getModal('modalValidacionuno', $data);
getModal('modalValidacionJefaturaSuperior', $data);
getModal('modalValidacionCompras', $data);
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

<?php
if (!empty($data['arrSolicitudComprobantes']['viaticosComprobantes'])) {
  $viaticosComprobantes = $data['arrSolicitudComprobantes']['viaticosComprobantes'];

  // Agrupar por conceptoid
  $comprobantesPorConcepto = [];
  foreach ($viaticosComprobantes as $comprobante) {
    $conceptoId = $comprobante['conceptoid'] ?? 0;
    $comprobantesPorConcepto[$conceptoId]['concepto'] = $comprobante['concepto'];
    $comprobantesPorConcepto[$conceptoId]['items'][] = $comprobante;
  }

  // VARIABLES PARA TOTAL GENERAL
  $totalGeneral = 0;
  $contadorGeneral = 0;

  foreach ($comprobantesPorConcepto as $conceptoId => $grupo) {

    // VARIABLES PARA TOTAL POR CONCEPTO/DÍA
    $totalConcepto = 0;
    $contadorConcepto = 0;
?>
      <div class="tile shadow rounded-3 p-4 mb-4">
        <section id="sPedido" class="invoice">
          <div class="row mb-4">
            <div class="col-6">
              <h2 class="page-header">
                <img src="<?= media(); ?>/images/ldr.png" style="height: 100px;">
              </h2>
            </div>
            <div class="col-6 text-end">
              <h5>FOLIO: <?= $grupo['items'][0]['codigo_solicitud'] ?? '' ?></h5>
            </div>
          </div>

          <h6 style="background-color: #d16032; color: white; font-size: 15px; padding: 25px;">
            COMPROBANTES DE GASTOS
          </h6>

          <div class="day-header fs-5 fw-bold my-3" style="background:#343a40; color:white; padding: 8px; border-radius: 4px;">
            <i class="bi bi-folder2-open"></i> <?= $grupo['concepto'] ?>
          </div>

          <div class="row g-3 mb-4">
<?php foreach ($grupo['items'] as $comprobante) { 
            $totalConcepto += floatval($comprobante['total']);
            $contadorConcepto++;
            $totalGeneral += floatval($comprobante['total']);
            $contadorGeneral++;
?>
            <div class="col-md-4 comprobante-item">
              <div class="card comprobante-card shadow-sm border" id="comp-<?= $comprobante['idcomprobante'] ?>">
                <div class="card-body">

                  <div class="comprobante-header fw-bold mb-2"><i class="bi bi-receipt-cutoff text-secondary"></i> <?= formatearFechaCompleta($comprobante['fecha']) ?></div>
                   
                  <p class="text-muted mb-1">Proveedor: <strong><?= $comprobante['rfcemisor'] ?? 'N/A' ?></strong></p>
                  <p class="mb-1"><strong>Monto:</strong> <?= formatMoney($comprobante['total']) ?></p>

                  <p class="mb-1"><i class="bi bi-file-earmark-pdf-fill text-danger icono-doc"></i> 
                    <?php if (!empty($comprobante['rutapdf'])) { ?>
                      <a href="<?= media() . '/uploads/pdf/' . $comprobante['rutapdf'] ?>" target="_blank" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
                    <?php } else { ?>
                      <button class="btn btn-outline-danger btn-sm disabled"><i class="fas fa-file-pdf"></i> Sin PDF</button>
                    <?php } ?>
                  </p>

                  <p><i class="bi bi-filetype-xml text-primary icono-doc"></i> 
                    <a href="<?= media() . '/uploads/xml/' . $comprobante['rutaxml'] ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-code"></i> XML</a>
                  </p>

                  <div class="small text-muted mb-2">
                    <div><strong>UUID:</strong> <?= $comprobante['uuid'] ?? 'N/A' ?></div>
                    <div><strong>Subtotal:</strong> <?= isset($comprobante['subtotal_comprobante']) ? formatMoney($comprobante['subtotal_comprobante']) : '$0.00' ?></div>
                    <div><strong>Fecha Factura:</strong> <?= $comprobante['fechafactura'] ?? 'N/A' ?></div>
                  </div>

                  <?php if ($comprobante['estado_documento'] == 1) { ?>
                    <div class="comprobante-status pendiente">⏳ Pendiente</div>
                  <?php } elseif ($comprobante['estado_documento'] == 2) { ?>
                    <div class="comprobante-status rechazado">❌ Rechazado</div>
                  <?php } elseif ($comprobante['estado_documento'] == 3) { ?>
                    <div class="comprobante-status aprobado">✅ Aprobado</div>
                  <?php } ?>
  <!-- <p  style="font-size: 12px;" class="text-muted mb-1">Comentario: <strong><?= $comprobante['comentarios'] ?? 'N/A' ?></strong></p> -->
                  <br><br>    
                 

                  <?php if (!in_array($comprobante['estado_documento'], ['2', '3'])) { ?>
                    <div class="status-comment"></div>
                  <?php } else { ?>
                    <div class="status-comment"><?= $comprobante['comentario_compras'] ?></div>
                  <?php } ?>

                  <?php if ($_SESSION['userData']['id_area'] == "132" || $_SESSION['userData']['email_usuario'] == "carlos.cruz@ldrsolutions.com.mx") { ?>
                    <?php if (!in_array($comprobante['estado_documento'], ['2', '3'])) { ?>
                      <div class="action-section">
                        <textarea class="form-control mb-2 comment-area" placeholder="Agregar comentario (requerido)..."></textarea>
                        <div class="d-flex justify-content-between btn-group-sm">
                          <button class="btn btn-success" onclick="evaluarComprobante(this, '3', <?= $comprobante['idcomprobante'] ?>)"><i class="bi bi-check-circle"></i> Aprobar</button>
                          <button class="btn btn-danger" onclick="evaluarComprobante(this, '2', <?= $comprobante['idcomprobante'] ?>)"><i class="bi bi-x-circle"></i> Rechazar</button>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } ?>

                </div>
              </div>
            </div>
<?php } ?>
          </div>

          <!-- TOTAL POR CONCEPTO/DÍA -->
          <div class="concept-total-container fw-bold fs-5 mt-4 text-end border-top pt-3">
            Total del concepto: <?= formatMoney($totalConcepto) ?> <br>
            Facturas: <?= $contadorConcepto ?>
          </div>

        </section>
      </div>
<?php
  } // foreach conceptos

  // TOTAL GENERAL
?>
      <div class="tile totalesGenerales shadow rounded-3 p-4 mb-4">
        <h4 class="fw-bold text-end">Total general de comprobantes: <?= formatMoney($totalGeneral) ?> (<?= $contadorGeneral ?> facturas)</h4>
      </div>

<?php
} else {
?>
      <div class="tile shadow rounded-3 p-4 text-center">
        <section id="sPedido" class="invoice">
          <i class="bi bi-folder-x fs-1 text-secondary mb-3"></i>
          <h4 class="text-muted">No se encontraron comprobantes registrados.</h4>
        </section>
      </div>
<?php
}
?>
    </div>
  </div>
</main>
<?php footerAdmin($data); ?>
