<?php headerAdmin($data);


//             echo '<pre>';
// print_r($_SESSION['userData']);
// echo '</pre>';

?>


<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i><?= $data['page_title'] ?></h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard">Dashboard</a></li>
    </ul>
  </div>


  <div class="row">

    <div class="col-md-6">
      <div class="tile">
        <h3 class="tile-title">Solicitudes Pendientes</h3>
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>FOLIO</th>
              <th>Solicita</th>
              <th>Total</th>

              <th></th>

            </tr>
          </thead>
          <tbody>
            <?php

            if (count($data['solicitudesPendientes']) > 0) {
              foreach ($data['solicitudesPendientes'] as $solicitud) {
            ?>
                <tr>
                  <td><?= $solicitud['codigo_solicitud'] ?></td>
                  <td><?= $solicitud['correo'] ?></td>
                  <td><?= formatMoney($solicitud['total']) ?></td>
                  <td><a href="<?= base_url() ?>/viaticosgenerales/solicitud/<?= $solicitud['idviatico'] ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                </tr>
              <?php
              }
            } else {
              ?>
              <tr>
                <td colspan="4" class="text-center">No hay solicitudes pendientes.</td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

 <?php

            if ($_SESSION['userData']['email_usuario'] === 'daniella.silva@ldrsolutions.com.mx' || $_SESSION['userData']['email_usuario'] === 'raul.tellez@ldrsolutions.com.mx' || $_SESSION['userData']['email_usuario'] === 'astrid.sebastian@ldrsolutions.com.mx') {
              
            ?>

    <div class="col-md-6">
      <div class="tile">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h3 class="tile-title mb-0">Listado de solicitudes aprobadas recientemente </h3>
          <a href="<?= base_url() ?>/ruta-todas-solicitudes" class="btn btn-sm btn-outline-primary ">Ver todas</a>
        </div>
        <table class="table table-striped table-sm">
          <thead>
            <tr>
           <th>FOLIO</th>
              <th>Solicita</th>
              <th>Total</th>

              <th></th> 
            </tr>
          </thead>
          <tbody>
            <?php

            if (count($data['allSolicitudesAprobadas']) > 0) {
              foreach ($data['allSolicitudesAprobadas'] as $solicitud) {
            ?>
                <tr>
                  <td><?= $solicitud['codigo_solicitud'] ?></td>
                  <td><?= $solicitud['correo'] ?></td>
                  <td><?= formatMoney($solicitud['total']) ?></td>
                  <td><a href="<?= base_url() ?>/viaticosgenerales/solicitud/<?= $solicitud['idviatico'] ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                </tr>
              <?php
              }
            } else {
              ?>
              <tr>
                <td colspan="4" class="text-center">No hay solicitudes.</td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

     <?php

           }else{
            ?>

                <div class="col-md-6">
      <div class="tile">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h3 class="tile-title mb-0">Solicitudes por área</h3>
          <a href="<?= base_url() ?>/ruta-todas-solicitudes" class="btn btn-sm btn-outline-primary ">Ver todas</a>
        </div>
        <table class="table table-striped table-sm">
          <thead>
            <tr>
           <th>FOLIO</th>
              <th>Solicita</th>
              <th>Total</th>

              <th></th> 
            </tr>
          </thead>
          <tbody>
            <?php

            if (count($data['solicitudesPorArea']) > 0) {
              foreach ($data['solicitudesPorArea'] as $solicitud) {
            ?>
                <tr>
                  <td><?= $solicitud['codigo_solicitud'] ?></td>
                  <td><?= $solicitud['correo'] ?></td>
                  <td><?= formatMoney($solicitud['total']) ?></td>
                  <td><a href="<?= base_url() ?>/viaticosgenerales/solicitud/<?= $solicitud['idviatico'] ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                </tr>
              <?php
              }
            } else {
              ?>
              <tr>
                <td colspan="4" class="text-center">No hay solicitudes.</td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

      <?php

           }
            ?>




  </div>




  <div class="row">
    <div class="col-md-6">
      <div class="tile" onclick="abrirModalYRedirigir()">
        <!-- <h3 class="tile-title">Viáticos Generales</h3> -->
        <div class="image-container">
          <img src="<?= media() ?>/images/viaticos.png" alt="Imagen 2">
        </div>
      </div>
    </div>


    <div class="col-md-6">
      <div class="tile">
        <!-- <h3 class="tile-title">Vuelos y Hoteles</h3> -->
        <div class="image-container">
          <img src="<?= media() ?>/images/vuelos.png" alt="Imagen 2">
        </div>
      </div>
    </div>
  </div>

  <div class="row">

  </div>

</main>
<?php footerAdmin($data); ?>

<script>
  Highcharts.chart('pagosMesAnio', {
    chart: {
      plotBackgroundColor: null,
      plotBorderWidth: null,
      plotShadow: false,
      type: 'pie'
    },
    title: {
      text: 'Ventas por tipo pago, <?= $data['pagosMes']['mes'] . ' ' . $data['pagosMes']['anio'] ?>'
    },
    tooltip: {
      pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
      point: {
        valueSuffix: '%'
      }
    },
    plotOptions: {
      pie: {
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
          enabled: true,
          format: '<b>{point.name}</b>: {point.percentage:.1f} %'
        }
      }
    },
    series: [{
      name: 'Brands',
      colorByPoint: true,
      data: [
        <?php
        foreach ($data['pagosMes']['tipospago'] as $pagos) {
          echo "{name:'" . $pagos['tipopago'] . "',y:" . $pagos['total'] . "},";
        }
        ?>
      ]
    }]
  });

  Highcharts.chart('graficaMes', {
    chart: {
      type: 'line'
    },
    title: {
      text: 'Ventas de <?= $data['ventasMDia']['mes'] . ' del ' . $data['ventasMDia']['anio'] ?>'
    },
    subtitle: {
      text: 'Total Ventas <?= SMONEY . '. ' . formatMoney($data['ventasMDia']['total']) ?> '
    },
    xAxis: {
      categories: [
        <?php
        foreach ($data['ventasMDia']['ventas'] as $dia) {
          echo $dia['dia'] . ",";
        }
        ?>
      ]
    },
    yAxis: {
      title: {
        text: ''
      }
    },
    plotOptions: {
      line: {
        dataLabels: {
          enabled: true
        },
        enableMouseTracking: false
      }
    },
    series: [{
      name: 'Dato',
      data: [
        <?php
        foreach ($data['ventasMDia']['ventas'] as $dia) {
          echo $dia['total'] . ",";
        }
        ?>
      ]
    }]
  });

  Highcharts.chart('graficaAnio', {
    chart: {
      type: 'column'
    },
    title: {
      text: 'Ventas del año <?= $data['ventasAnio']['anio'] ?> '
    },
    subtitle: {
      text: 'Esdística de ventas por mes'
    },
    xAxis: {
      type: 'category',
      labels: {
        rotation: -45,
        style: {
          fontSize: '13px',
          fontFamily: 'Verdana, sans-serif'
        }
      }
    },
    yAxis: {
      min: 0,
      title: {
        text: ''
      }
    },
    legend: {
      enabled: false
    },
    tooltip: {
      pointFormat: 'Population in 2017: <b>{point.y:.1f} millions</b>'
    },
    series: [{
      name: 'Population',
      data: [
        <?php
        foreach ($data['ventasAnio']['meses'] as $mes) {
          echo "['" . $mes['mes'] . "'," . $mes['venta'] . "],";
        }
        ?>
      ],
      dataLabels: {
        enabled: true,
        rotation: -90,
        color: '#FFFFFF',
        align: 'right',
        format: '{point.y:.1f}', // one decimal
        y: 10, // 10 pixels down from the top
        style: {
          fontSize: '13px',
          fontFamily: 'Verdana, sans-serif'
        }
      }
    }]
  });
</script>