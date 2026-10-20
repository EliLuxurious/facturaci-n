 <?php 
  use Controladores\ControladorEmpresa;
  $emisor = ControladorEmpresa::ctrEmisor();
  
  ?>
  
  <header class="main-header cabecera-m">
 
    <!-- Logo -->
    <a href="inicio" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- <span class="logo-mini"><b>B</b>MM</span> -->
      <span class="logo-mini"><img src="vistas/img/logo/<?php echo $emisor['logo'] ?>" alt="" width="50px"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo $emisor['nombre_comercial'] ?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top cabecera-m">
      <!-- Sidebar toggle button-->
      <button class="btn btn-success  btn-menup" data-toggle="push-menu" role="button">
      <i class="fas fa-align-justify fa-lg"></i>
      </button>        
        <button class="btn-modo-sistema">PRUEBAS</button>

        <button class="btn btn-danger btn-menup dia" onclick="changep(1)" idp="claro"></button>
        <button class="btn btn-danger btn-menup noche" onclick="changep(2)" idp="oscuro"></button>
        <button class="btn btn-primary" id="tipocambio"></button>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

        <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="far fa-bell"></i>
              <span class="label label-warning no-enviados"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header no-enviados-text"></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua "></i>
                      <span class="no-enviados-items"></span>
                    </a>
                  </li>
                  <!-- <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                      page and may cause design problems
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li> -->
                </ul>
              </li>
              <li class="footer"><a href="ventas">Ver todos</a></li>
            </ul>
          </li>
          <!-- fin notification ===================== -->
    
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- <img src="vistas/img/man_default.svg" class="user-image" alt="User Image"> -->
          <?php if($_SESSION['foto'] != '') {
              echo '<img src="'.$_SESSION['foto'].'" class="user-image" alt="User Image">';
          }else{
            echo '<img src="vistas/img/man_default.svg" class="user-image" alt="User Image">';

          }
          ?>
              <span class="hidden-xs">Usuario</span>
            </a>
            <ul class="dropdown-menu menu-user" style="width: 200px; color:black;">
              
              <!-- Menu Body -->
            <li class="">
            <a href="usuarios">
            <i class="fas fa-user fa-lg" style="color:royalblue"> </i> <span class="mg-menu">Mi perfil</span>
                      
          </a>
          </li> 
          <li class="">
            <a href="empresa">
            <i class="fas fa-cog  fa-lg" style="color:royalblue"> </i> <span class="mg-menu">Configurar empresa</span>
                     
          </a>
          </li> 
              <!-- Menu Footer-->
              <li class="">
                <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->
         
                  <a href="salir" class="">
                  <i class="fas fa-sign-out-alt fa-lg" style="color:tomato"></i><span class="mg-menu"> Salir </span>
                  </a>
              
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>