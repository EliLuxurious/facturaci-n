<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class=" user-panel2">
        <div class=" image">
        <?php if($_SESSION['foto'] != '') {
          echo '<img src="'.$_SESSION['foto'].'" class="img-circle img-user" alt="User Image">';
        }else{
          echo '<img src="vistas/img/man_default.svg" class="img-circle img-user" alt="User Image">';

        }
          ?>
        </div>
        <div class=" info">
          <p><?php echo $_SESSION['nombre']; ?></p>
          <a href="#" class="btn btn-primary btn-sm boton-user"><i class="fas fa-user icon-user"></i>  <?php echo $_SESSION['perfil']; ?></a>
        </div>
      </div>
      <!-- search form -->
     
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
             
        <li class=" menu-ini-p">
          <a href="inicio">
          <i class="fas fa-home fa-lg bg-menu" ></i>
            <span class="mg-menu"> Inicio</span>
           
           
            </span>
          </a>
          
        </li>

        <li class="treeview">
          <a href="#">
          <i class="fab fa-product-hunt fa-lg"></i>
            <span class="mg-menu">Productos y Servicios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="productos"><i class="fab fa-product-hunt fa-lg"></i>Almacen de Productos</a></li>
            <li><a href="categorias"><i class="fab fa-elementor fa-lg"></i>Categorías</a></li>   
            <li><a href="unidad-medida"><i class="fa fa-cog fa-lg"></i>Unidades de Medida</a></li> 
          </ul>
        </li>        

        <li class="treeview">
          <a href="#">
          <i class="fas fa-file-invoice-dollar fa-lg"></i>
            <span class="mg-menu">Ventas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="crear-factura" ><i class="fa fa-file-invoice"></i> Emitir factura</a></li>
            <li><a href="crear-boleta"><i class="fa fa-file-invoice"></i> Emitir boleta</a></li>
            <li><a href="crear-nota"><i class="fa fa-file-invoice"></i> Emitir nota de venta</a></li>
            <li><a href="nota-credito"><i class="fa fa-file-invoice"></i> Emitir nota de crédito</a></li>
            <li><a href="nota-debito"><i class="fa fa-file-invoice"></i> Emitir nota de débito</a></li>
           
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
          <i class="fas fa-file-invoice-dollar fa-lg"></i>
            <span class="mg-menu">Compras</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="nueva-compra"><i class="fa fa-file-invoice"></i> Nueva compra</a></li>
           
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
          <i class="fas fa-file-invoice fa-lg"></i>
            <span class="mg-menu">Guía de Remisión</span>
            <!-- <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span> -->
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="crear-guia" ><i class="fa fa-file-invoice"></i> Crear Guía de Remisión</a></li>
            <li><a href="ver-guias"><i class="fa fa-file-invoice"></i> Listar Guías de Remisión</a></li>
            
          </ul>

        </li><li class="treeview">
          <a href="#">
          <i class="fas fa-file-invoice fa-lg"></i>
            <span class="mg-menu">Cotizaciones</span>
            <!-- <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span> -->
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="crear-cotizacion" ><i class="fa fa-file-invoice"></i> Crear Cotización</a></li>
            <li><a href="listar-cotizaciones"><i class="fa fa-file-invoice">              
          </i> Listar Cotizaciones</a></li>
            
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
          <i class="fas fa-file-invoice-dollar fa-lg"></i>
            <span class="mg-menu">Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="reporte-ventas"><i class="fa fa-file-invoice"></i> Reporte de ventas</a></li>           
            <li><a href="reporte-compras"><i class="fa fa-file-invoice"></i> Reporte de compras</a></li>           
          </ul>
        </li>


        <li class="">
          <a href="clientes">
            <i class="fas fa-users fa-lg"></i> <span class="mg-menu">Clientes</span>
            <span class="pull-right-container">
              <!-- <small class="label pull-right bg-orange">new</small> -->
            </span>
          </a>
        </li>
        
        <li class="">
          <a href="consulta-comprobante">
          <i class="fas fa-file-invoice-dollar fa-lg"></i> <span class="mg-menu">Consultar comprobantes</span>
            <span class="pull-right-container">
              <!-- <small class="label pull-right bg-orange">new</small> -->
            </span>
          </a>
        </li>        

        <li class="">
          <a href="ventas">
            <i class="fas fa-users fa-lg"> </i> <span class="mg-menu">Estados SUNAT</span>
            <span class="pull-right-container">
             
            </span>
          </a>
        </li>

        <li class="treeview">
          <a href="#">
          <i class="fas fa-file-invoice-dollar fa-lg"></i>
            <span class="mg-menu">Resumen diario boletas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="resumen-diario"><i class="fa fa-file-invoice"></i> Crear resúmenes de boletas</a></li>
            <li><a href="resumen-diario" ><i class="fa fa-file-invoice"></i> Ver resúmenes de boletas</a></li>
         
          </ul>
        </li>
        
        <li class="">
          <a href="usuarios">
            <i class="fas fa-users fa-lg"> </i> <span class="mg-menu">Usuarios</span>
            <span class="pull-right-container">
             
            </span>
          </a>
        </li>
        
        <li class="treeview">
          <a href="empresa">
          <i class="fa fa-cog fa-lg"></i> <span class="mg-menu">Configuración</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="empresa"><i class="fa fa-cog fa-lg"></i> Configurar empresa</a></li>
            <li><a href="unidad-medida"><i class="fa fa-cog fa-lg"></i> Configurar Unidad Medida</a></li>
            </ul>
        </li>         

        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>




  