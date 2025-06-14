<?php 
    headerAdmin($data); 
    getModal('modalCcosto',$data);

?>
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-chart-pie"></i> <?= $data['page_title'] ?>
              
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Nuevo</button>
        
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/ccostos"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>
        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableCcostos">
                      <thead>
                        <tr>
                          <th>Nombre</th>
                          <th>Área</th>
                          <th>Responsable</th>
                          <th>Presup. Mensual</th>
                          <th>Presup. Anual</th>
                          <th>F. Registro</th>
                          <!--<th>Creado Por</th>-->
                          <th>Modificado Por</th>
                          <th class="col-opciones">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </main>
<?php footerAdmin($data); ?>
    