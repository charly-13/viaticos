<?php headerAdmin($data);
getModal('modalValidacionuno', $data);
getModal('modalValidacionJefaturaSuperior', $data);
getModal('modalValidacionCompras', $data);
// echo '<pre>';
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


      
               <?php
        if (empty($data['arrSolicitud'])) {
      
        ?>
          <p>Datos no encontrados</p>
        <?php } else {

 $estado = $data['arrSolicitud']['viaticos'];
        ?>

  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <section id="sPedido" class="invoice">
          <div class="progress-track">
            
            <div class="step done">
              <div class="circle"><i class="fa fa-check"></i></div>
              <div class="label">Enviado</div>
            </div>
           
             
             
                <?php  if ($estado['estado_viatico'] == 2) { ?>
           
            <div class="step active">
              <div class="circle"><i class="fa fa-spinner fa-spin"></i></div>
              <div class="label">Revisión (Jefe directo)</div>
            </div>
               <?php } else if($estado['estado_viatico'] == 4){  ?>
            <div class="step bad">
              <div class="circle"><i class="fa fa-times"></i></div>
              <div class="label">Revisión (Jefe directo)</div>
            </div>

              <?php } else if($estado['estado_viatico'] == 5 || $estado['estado_viatico'] == 7 || $estado['estado_viatico'] == 8 || $estado['estado_viatico'] == 9 || $estado['estado_viatico'] == 10){  ?>
         <div class="step done">
              <div class="circle"><i class="fa fa-check"></i></div>
              <div class="label">Revisión (Jefe directo)</div>
            </div>
      <?php }  else {  ?>

      <div class="step">
              <div class="circle"><i class="fa fa-check"></i></div>
              <div class="label">Revisión (Jefe directo)</div>
            </div>


      <?php } ?>



                      <?php  if ( $estado['estado_viatico'] == 5) { ?>
           
            <div class="step active">
              <div class="circle"><i class="fa fa-spinner fa-spin"></i></div>
              <div class="label">Validación (Jefe superior)</div>
            </div>
               <?php } else if($estado['estado_viatico'] == 7){  ?>
            <div class="step bad">
              <div class="circle"><i class="fa fa-times"></i></div>
              <div class="label">Validación (Jefe superior)</div>
            </div>

              <?php } else if($estado['estado_viatico'] == 8 || $estado['estado_viatico'] == 9 || $estado['estado_viatico'] == 10){  ?>
         <div class="step done">
              <div class="circle"><i class="fa fa-check"></i></div>
              <div class="label">Validación (Jefe superior)</div>
            </div>
      <?php }  else {  ?>

      <div class="step">
              <div class="circle"><i class="fa fa-file"></i></div>
              <div class="label">Validación (Jefe superior)</div>
            </div>


      <?php } ?>


                            <?php  if ( $estado['estado_viatico'] == 8) { ?>
           
            <div class="step active">
              <div class="circle"><i class="fa fa-spinner fa-spin"></i></div>
              <div class="label">Autorización Realizada(Compras)</div>
            </div>
               <?php } else if($estado['estado_viatico'] == 9){  ?>
            <div class="step bad">
              <div class="circle"><i class="fa fa-times"></i></div>
              <div class="label">Autorización Realizada(Compras)</div>
            </div>

              <?php } else if($estado['estado_viatico'] == 10){  ?>
         <div class="step done">
              <div class="circle"><i class="fa fa-check"></i></div>
              <div class="label">Autorización Realizada(Compras)</div>
            </div>
      <?php }  else {  ?>

      <div class="step">
              <div class="circle"><i class="fa fa-file"></i></div>
              <div class="label">Autorización Realizada(Compras)</div>
            </div>


      <?php } ?>








            <!-- <div class="step">
              <div class="circle"><i class="fa fa-flag-checkered"></i></div>
              <div class="label">Autorización Realizada(Compras)</div>
            </div> -->

            
          </div>

        </section>
      </div>
    </div>
  </div>


      <?php } ?>



  <div class="row">

    <div class="col-md-12">

      <div class="tile">
        <?php
        if (empty($data['arrSolicitud'])) {


        ?>
          <p>Datos no encontrados</p>
        <?php } else {
          // $cliente = $data['arrSolicitud']['nombreusuario']; 
          // $orden = $data['arrSolicitud']['codigo_solicitud'];
          $usuarioSolicita = $data['arrSolicitud']['viaticos'];
          $detalle = $data['arrSolicitud']['detalle'];

          //dep($usuarioSolicita);


        ?>
          <section id="sPedido" class="invoice">
            <div class="row mb-4">
              <div class="col-6">
                <h2 class="page-header"><img src="<?= media(); ?>/images/ldr.png" style="height: 100px;"></h2>
              </div>
              <div class="col-6">
                <h5 class="text-right">FOLIO: <?= $usuarioSolicita['codigo_solicitud'] ?></h5>
              </div>
            </div>



            <h6 style="background-color: #d16032; color: white; font-size: 15px; padding: 6px;"><i class="fas fa-plane-departure"></i> SOLICITUD DE VIÁTICOS</h6>


            <div class="row invoice-info">
              <div class="col-4">
                <address><strong><i class="fas fa-building text-primary"></i><?= NOMBRE_EMPESA; ?></strong><br>
                  <?= DIRECCION ?><br>
                  
                   <a href="<?= WEB_EMPRESA ?>" target="_blank">www.ldrsolutions.mx</a>
                </address>
              </div>
              <div class="col-4">
                <address><strong><i class="fas fa-user text-primary"></i>Solicitante:  <?= $usuarioSolicita['nombreusuario'] ?></strong><br>
                 <i class="fas fa-phone-alt"></i> Tel: <?= $usuarioSolicita['telefono_personal'] ?><br>
                 <i class="fas fa-envelope"></i> Email: <?= $usuarioSolicita['email_corporativo'] ?>
                </address>
              </div>
              <div class="col-4"><b class="folio_solicitud"><i class="fas fa-calendar-alt text-primary"></i>Fecha: <?= $usuarioSolicita['fechacreacion'] ?></b><br>
                <!-- <b>Pago: </b><?= $usuarioSolicita['tipopago'] ?><br> -->

                <!-- <b>Estado:</b>  -->
                <?php
                       /*         $estado = $usuarioSolicita['estado_viatico'] ?? '';
                                switch ($estado) {
                                  case 2:
                                    echo '<span style="background-color: #FFA500; color: #fff; padding: 4px 8px; border-radius: 5px; font-weight: bold;">En Revisión</span>';
                                    break;
                                  case 4:
                                    echo '<span style="background-color: #dc3545; color: #fff; padding: 4px 8px; border-radius: 5px; font-weight: bold;">Rechazada por Jefatura</span>';
                                    break;
                                  case 5:
                                    echo '<span style="background-color: #28a745; color: #fff; padding: 4px 8px; border-radius: 5px; font-weight: bold;">Aprobado por Jefatura</span>';
                                    break;
                                  case 7:
                                    echo '<span style="background-color: #dc3545; color: #fff; padding: 4px 8px; border-radius: 5px; font-weight: bold;">Rechazado por Finanzas</span>';
                                    break;
                                  case 8:
                                    echo '<span style="background-color: #28a745; color: #fff; padding: 4px 8px; border-radius: 5px; font-weight: bold;">Solicitud Aprobada</span>';
                                    break;
                                  default:
                                    echo '<span style="background-color: #6c757d; color: #fff; padding: 4px 8px; border-radius: 5px;">Sin definir</span>';
                                    break;
                                }
                    */
                    ?>  
                                <br>

              </div>
            </div>


            <!-- Información adicional del viaje -->
            <div class="row mb-4">
              <div class="col-12">

                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <th scope="row" style="width: 20%;"><i class="fas fa-calendar-day"></i> Fecha de Salida</th>
                      <td><?= $usuarioSolicita['fecha_salida'] ?></td>
                    </tr>
                    <tr>
                      <th><i class="fas fa-calendar-check"></i> Fecha de Regreso</th>
                      <td><?= $usuarioSolicita['fecha_regreso'] ?></td>
                    </tr>
                    <tr>
                      <th><i class="fas fa-map-marker-alt"></i> Destino</th>
                      <td><?= $usuarioSolicita['lugar_destino'] ?></td>
                    </tr>
                    <tr>
                      <th><i class="fas fa-briefcase"></i> Motivo</th>
                      <td><?= $usuarioSolicita['motivo'] ?></td>
                    </tr>
                    <tr>
                      <th><i class="fas fa-align-left"></i> Descripción</th>
                      <td><?= $usuarioSolicita['descripcion'] ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>







            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table table-striped">
                  <thead class="thead-dark">
                    <tr>
                      <th>Concepto</th>
                      <th class="text-center">Comentarios</th>
                      <th class="text-center">Días</th>
                      <th class="text-center">Monto Diario</th>
                      <th class="text-right">Subtotal</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $subtotal = 0;
                    if (count($detalle) > 0) {
                      foreach ($detalle as $producto) {

                    ?>
                        <tr>
                          <td><?= $producto['concepto'] ?></td>
                          <td class="text-center"><?= $producto['comentario'] ?></td>
                          <td class="text-center"><?= $producto['dias'] ?></td>
                          <td class="text-center"><?= formatMoney($producto['solicituddiaria']) ?></td>
                          <td class="text-right"><?= formatMoney($producto['subtotal']) ?></td>


                        </tr>
                    <?php
                      }
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" class="text-right">TOTAL:</th>
                      <th class="text-right" style="background-color:#f2f2f2;"><?= formatMoney($usuarioSolicita['total']) ?></th>
                    </tr>

                  </tfoot>
                </table>
              </div>
            </div>




            <?php
            if ($usuarioSolicita['estado_viatico'] >= 4) {
            ?>
              <h6 style="background-color: #d16032; color: white;font-size: 15px; padding: 6px;"><i class="fas fa-user-tie"></i> Observaciones y respuesta proporcionadas por el Jefe Directo</h6>
              <!-- Información adicional del viaje -->
              <div class="row mb-4">
                <div class="col-12">

                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <th scope="row" style="width: 20%;"><i class="fas fa-clock"></i> Fecha de respuesta</th>
                        <td><?= $usuarioSolicita['fechajefatura'] ?></td>
                      </tr>
                      <tr>
                        <th><i class="fas fa-comment-alt"></i> Comentarios u observaciones</th>
                        <td><?= $usuarioSolicita['comentariosjefatura'] ?></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>

            <?php
            }
            ?>

                        <?php
            if ($usuarioSolicita['estado_viatico'] >= 6) {
            ?>
              <h6 style="background-color: #d16032; color: white;font-size: 15px; padding: 6px;"><i class="fas fa-user-check"></i> Observaciones y respuesta proporcionadas por el Jefe Superior</h6>
              <!-- Información adicional del viaje -->
              <div class="row mb-4">
                <div class="col-12">

                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <th scope="row" style="width: 20%;"><i class="fas fa-clock"></i>Fecha de respuesta</th>
                        <td><?= $usuarioSolicita['fechajefaturasup'] ?></td>
                      </tr>
                      <tr>
                        <th><i class="fas fa-comment-alt"></i> Comentarios u observaciones</th>
                        <td><?= $usuarioSolicita['comentariosjefaturasup'] ?></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>

            <?php
            }
            ?>



<div class="row d-print-none mt-2">
  <div class="col-12 d-flex justify-content-center btn-spacing-sol">

    <?php if ($usuarioSolicita['estado_viatico'] == 2) { ?>
      <button class="btn btn-success" style="width: 300px;" type="button" onclick="openModal(<?php echo $usuarioSolicita['idviatico'] ?>)">
        <i class="fa fa-tasks"></i> Gestionar
      </button>
    <?php } ?>

    <?php if ($usuarioSolicita['estado_viatico'] == 4 || $usuarioSolicita['estado_viatico'] == 5) { ?>
      <button class="btn btn-info" style="width: 300px;" type="button" onclick="openModalValidatejefeSuperior(<?php echo $usuarioSolicita['idviatico'] ?>)">
        <i class="fa fa-tasks"></i> Gestionar
      </button>
    <?php } ?>

        <?php if ($usuarioSolicita['estado_viatico'] == 7 || $usuarioSolicita['estado_viatico'] == 8) { ?>
      <button class="btn btn-success" style="width: 300px;" type="button" onclick="openModalValidateCompras(<?php echo $usuarioSolicita['idviatico'] ?>)">
        <i class="fa fa-tasks"></i> Finalizar Solicitud
      </button>
    <?php } ?>

    <a class="btn btn-primary" style="width: 300px;" href="javascript:window.print('#sPedido');">
      <i class="fa fa-print"></i> Imprimir
    </a>

  </div>
</div>





          </section>
        <?php } ?>
      </div>
    </div>
  </div>
</main>
<?php footerAdmin($data); ?>