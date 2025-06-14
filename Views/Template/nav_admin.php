    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="https://ldrhsys.ldrhumanresources.com/Cliente/img/avatars/<?= $_SESSION['userData']['avatar']; ?>.png" alt="User Image">
        <div>
          <p class="app-sidebar__user-name"><?= $_SESSION['userData']['nombre_1']; ?></p>
        </div>
      </div>
      <ul class="app-menu">
        <li>
            <a class="app-menu__item" href="https://ldrhsys.ldrhumanresources.com/Cliente/interfaces/Inicio.php?resultado=ingreso">
                <i class="app-menu__icon fas fa-arrow-left" aria-hidden="true"></i>
                <span class="app-menu__label">Regresar</span>
            </a>
        </li>
    
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
    
        <?php if(!empty($_SESSION['permisos'][2]['r'])){ ?>
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-users" aria-hidden="true"></i>
                <span class="app-menu__label">Usuarios</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="<?= base_url(); ?>/usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
                <li><a class="treeview-item" href="<?= base_url(); ?>/roles"><i class="icon fa fa-circle-o"></i> Roles</a></li>
            </ul>
        </li>
        <?php } ?>

        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-archive" aria-hidden="true"></i>
                <span class="app-menu__label">Solicitudes de Viáticos</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
               
                <li><a class="treeview-item" href="<?= base_url(); ?>/viaticosgenerales"><i class="icon fa fa-circle-o"></i> Viáticos Generales</a></li>
               
              
                <li><a class="treeview-item" href="<?= base_url(); ?>/categorias"><i class="icon fa fa-circle-o"></i> Vuelos y Hoteles</a></li>
               
            </ul>
        </li>
   
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/ccostos">
                <i class="app-menu__icon fas fa-chart-pie" aria-hidden="true"></i>
                <span class="app-menu__label">Centros de Costo</span>
            </a>
        </li>
      
       

  
        <?php if(!empty($_SESSION['permisos'][5]['r'])){ ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/pedidos">
                <i class="app-menu__icon fa fa-shopping-cart" aria-hidden="true"></i>
                <span class="app-menu__label">Pedidos</span>
            </a>
        </li>
         <?php } ?>

        <?php if(!empty($_SESSION['permisos'][MSUSCRIPTORES]['r'])){ ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/suscriptores">
                <i class="app-menu__icon fas fa-user-tie" aria-hidden="true"></i>
                <span class="app-menu__label">Suscriptores</span>
            </a>
        </li>
         <?php } ?>

         <?php if(!empty($_SESSION['permisos'][MDCONTACTOS]['r'])){ ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/contactos">
                <i class="app-menu__icon fas fa-envelope" aria-hidden="true"></i>
                <span class="app-menu__label">Mensajes</span>
            </a>
        </li>
         <?php } ?>

         <?php if(!empty($_SESSION['permisos'][MDPAGINAS]['r'])){ ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/paginas">
                <i class="app-menu__icon fas fa-file-alt" aria-hidden="true"></i>
                <span class="app-menu__label">Páginas</span>
            </a>
        </li>
         <?php } ?>

        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/logout">
                <i class="app-menu__icon fa fa-sign-out" aria-hidden="true"></i>
                <span class="app-menu__label">Logout</span>
            </a>
        </li>
      </ul>
    </aside>