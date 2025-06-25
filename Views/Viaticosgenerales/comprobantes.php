<?php headerAdmin($data);
getModal('modalAddComprobantes', $data);
// getModal('modalValidacionJefaturaSuperior', $data);
// getModal('modalValidacionCompras', $data);
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
      <div class="tile">
        <?php if (empty($data['arrSolicitud'])) { ?>
          <div class="alert alert-warning text-center">No se encontraron datos de esta solicitud.</div>
        <?php } else {
          $usuarioSolicita = $data['arrSolicitud']['viaticos'];
          $detalle = $data['arrSolicitud']['detalle'];
          //$usuarioSolicita = $data['arrSolici tud']['viaticos'];
          //  dep($usuarioSolicita);
        ?>
          <section id="sPedido" class="invoice"> 
            <div class="row mb-4 align-items-center">
              <div class="col-6">
                <h2 class="page-header"><img src="<?= media(); ?>/images/ldr.png" style="height: 100px;"></h2>
              </div>
              <div class="col-6 text-right">
                <h5>FOLIO: <strong><?= $usuarioSolicita['codigo_solicitud'] ?></strong></h5>
              </div>
            </div>

            <h5 class="text-white p-2" style="background-color: #d16032;">
              <i class="fas fa-receipt"></i> Comprobantes de Viáticos
            </h5>

            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th>Concepto</th>
                    <th class="text-center">Comentarios</th>
                    <th class="text-center">Días</th>
                    <th class="text-center">Monto Diario</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-center col-th">Opciones</th>
                  </tr>   
                </thead>
                <tbody>
                  <?php
                  if (count($detalle) > 0) {
                    foreach ($detalle as $concepto) {
                  ?>
                      <tr>
                        <td><?= $concepto['concepto'] ?></td>
                        <td class="text-center"><?= $concepto['comentario'] ?></td>
                        <td class="text-center"><?= $concepto['dias'] ?></td>
                        <td class="text-center"><?= formatMoney($concepto['solicituddiaria']) ?></td>
                        <td class="text-right"><?= formatMoney($concepto['subtotal']) ?></td>
                        <td class="text-center">

                        <?php if ($concepto['tiene_comprobante']=="1") {   ?>

                          <!-- Botón Agregar Comprobante -->
                          <button class="btn btn-success btn-sm" title="Agregar Comprobante" onclick="modalAgregarComprobantes(<?= $concepto['idconcepto'] ?>,<?= $usuarioSolicita['idviatico'] ?>,<?= $concepto['dias'] ?>, '<?= $usuarioSolicita['fecha_salida'] ?>', '<?= htmlspecialchars($concepto['concepto']) ?>')">
                            <i class="fas fa-plus-circle"></i> Agregar
                          </button>
                            <?php 
                  } ?>
<?php if ($concepto['tiene_comprobante']=="2") {   ?>
                          <!-- Botón Ver Comprobantes -->
                          <a href="<?= base_url(); ?>/comprobantesgenerales/solicitud/<?= $usuarioSolicita['idviatico'] ?>" class="btn btn-info btn-sm" title="Ver Comprobantes">
                            <i class="fas fa-eye"></i> Ver
                          </a>
 <?php 
                  } ?>
                          

                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="4" class="text-right">TOTAL:</th>
                    <th class="text-right bg-light"><?= formatMoney($usuarioSolicita['total']) ?></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>

            <div class="row d-print-none mt-3">
              <div class="col-12 text-center">
                <a class="btn btn-secondary" href="<?= base_url(); ?>/viaticosgenerales">
                  <i class="fas fa-arrow-left"></i> Regresar
                </a>
                <!-- <button class="btn btn-primary" onclick="window.print();">
                  <i class="fa fa-print"></i> Imprimir
                </button> -->
              </div>
            </div>
          </section>
        <?php } ?>
      </div>
    </div>
  </div>
</main>
<?php footerAdmin($data); ?>