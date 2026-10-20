
  <?php
  use Controladores\ControladorUsuarios;
  
  ?>
  <div class="content-wrapper panel-medio-principal">
<?php 
  if($_SESSION['perfil'] == 'Vendedorr' || $_SESSION['perfil'] == 'Especiall'){

      echo '
      <section class="container-fluid panel-medio">
      <div class="box alert-dangers text-center">
     <div><h3> Área restringida, solo el administrador puede tener acceso</h3></div>
    <div class="img-restringido"></div>
     
     </div>
     </div>';

  }else{

  
  ?>
<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
  <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
     <button class=""><i class="fas fa-file-invoice"></i> Usuarios</button>
</div>
<div class="col-md-9  col-sm-12 btns-dash">
  <a href="crear-factura" class="btn pull-right" style="margin-left:10px"><i class="fas fa-file-invoice"></i> Emitir factura</a>
  <a href="crear-boleta" class="btn pull-right"><i class="fas fa-file-invoice"> </i> Emitir boleta</a>
</div>
</div>
   </div>   
    </section>
</section>



<!-- <section class="content"> -->
      <section class="container-fluid panel-medio">
          <!-- BOX INI -->
          <div class="box rounded">

                <div class="box-header ">
              <h3 class="box-title">Administración de usuarios</h3>

              <?php
               if($_SESSION['perfil'] == 'Administrador'){
                 ?>
              <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarUsuario"><i class="fas fa-plus-square"></i>Nuevo usuario <i class="fas fa-user-plus"></i>            
            </button>
            <?php } ?>
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         
            <input type="hidden" id="perfilOculto" value="<?php echo $_SESSION['perfil'] ?>">
            <!-- table-bordered table-striped  -->
         <table class="table  dt-responsive tablas tbl-t" width="100%">

          <thead>
            <tr>
              <th style="width:10px;">#</th>
              <th>Nombre</th>
              <th>Usuario</th>
              <th>Foto</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Último login</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
          <?php
          if($_SESSION['perfil'] != 'Administrador'){
            $item = 'id';
            $valor = $_SESSION['id'];
              $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
              $value = $usuarios;
              ?>
              
              <tr>
                <td>1</td>
                <td><?php echo $value['nombre']; ?></td>
                <td><?php echo $value['usuario']; ?></td>

                <?php
                if($value['foto'] != ''){

                  echo '<td><img src="'. $value['foto'].'" alt="" class="img-thumbnail" width="40px"></td>';

                }else{

                  echo '<td><img src="vistas/img/usuarios/default/anonymous.png" alt="" class="img-thumbnail" width="40px"></td>';

                }
                ?>
                <!-- <td><img src="" alt="" class="img-thumbnail" width="40px"></td> -->
                <td><?php echo $value['perfil']; ?></td>


                <td>
                  <div class="modo-contenedor-selva">
                
                 <input type="checkbox" data-toggle="toggle" data-on="Activado" data-off="Desactivado" data-onstyle="success" data-offstyle="danger" id="" name="usuarioEstado<?php $value['estado']?>"  value="<?php $value['estado'] ?>" data-size="mini" data-width="110" idUsuario="<?php echo $value['id'] ?>"<?php if($value['estado'] == 0){ }else{?>checked <?php } ?>>
                 </div>
              </td>
                
                
                <!-- <td><button class="btn btn-success btn-xs">activo</button></td> -->
                <td><?php echo date_format(date_create($value['ultimo_login']), 'd/m/Y H:i:s'); ?></td>
                <td>
                  <div class="btn-group">

                <button class="btn btn-warning btnEditarUsuario" idUsuario="<?php echo $value['id'] ?>" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fas fa-user-edit"></i></button>
                <?php
                if($_SESSION['perfil'] == 'Administrador'){
                  ?>
                <button class="btn btn-danger btnEliminarUsuario" idUsuario="<?php echo $value['id'] ?>" fotoUsuario="<?php echo $value['foto'] ?>" usuario="<?php echo $value['usuario']?>"><i class="fas fa-trash-alt"></i></button>
                  <?php } ?>

                  </div>


                </td>

              </tr>
              
              
              <?php
          }else{
              $item = null;
              $valor = null;
              $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
         
              foreach($usuarios as $key => $value):
              
          ?>
              <tr>
                <td><?php echo ++$key; ?></td>
                <td><?php echo $value['nombre']; ?></td>
                <td><?php echo $value['usuario']; ?></td>

                <?php
                if($value['foto'] != ''){

                  echo '<td><img src="'. $value['foto'].'" alt="" class="img-thumbnail" width="40px"></td>';

                }else{

                  echo '<td><img src="vistas/img/usuarios/default/anonymous.png" alt="" class="img-thumbnail" width="40px"></td>';

                }
                ?>
                <!-- <td><img src="" alt="" class="img-thumbnail" width="40px"></td> -->
                <td><?php echo $value['perfil']; ?></td>


                <td>
                  <div class="modo-contenedor-selva">
                
                 <input type="checkbox" data-toggle="toggle" data-on="Activado" data-off="Desactivado" data-onstyle="success" data-offstyle="danger" id="usuarioEstado" name="usuarioEstado<?php $value['estado']?>"  value="<?php $value['estado'] ?>" data-size="mini" data-width="110" idUsuario="<?php echo $value['id'] ?>"<?php if($value['estado'] == 0){ }else{?>checked <?php } ?>>
                 </div>
              </td>
                
                
                <!-- <td><button class="btn btn-success btn-xs">activo</button></td> -->
                <td><?php echo date_format(date_create($value['ultimo_login']), 'd/m/Y H:i:s'); ?></td>
                <td>
                  <div class="btn-group">

                <button class="btn btn-warning btnEditarUsuario" idUsuario="<?php echo $value['id'] ?>" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fas fa-user-edit"></i></button>
                <?php
                if($_SESSION['perfil'] == 'Administrador'){
                  ?>
                <button class="btn btn-danger btnEliminarUsuario" idUsuario="<?php echo $value['id'] ?>" fotoUsuario="<?php echo $value['foto'] ?>" usuario="<?php echo $value['usuario']?>"><i class="fas fa-trash-alt"></i></button>
                  <?php } ?>

                  </div>


                </td>

              </tr>

              <?php 
              endforeach;
               }
              ?>                 
          </tbody>

      </table>


              

            </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
<?php } ?>
<!-- <button type="button" class="btn btn-primary printsave">Print</button>
<div class="printerhere" width="100%" style=""></div> -->
<!-- <embed class="printerhere" src="" type="application/pdf" width="100%" height="600px" class="printerhere" /> -->

            </div>

            <!-- MODAL AGREGAR USUARIO -->
  <!-- Modal -->
<div id="modalAgregarUsuario" class="modal fade modal-forms fullscreen-modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      

    <form role="form" id="formUser" class="form-inserta" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">AGREGAR USUARIO</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">
          <div class="col-md-8">
            <!-- ENTRADA PARA EL NOMBRE -->
            <div id="respuestaAjax"></div> 

            <div class="form-group">           

                <input type="text" class="form-control " name="nuevoDni" id="nuevoDni" placeholder="Ingresar D.N.I." required>

            </div>
            <div class="form-group">              
              
                <input type="text" class="form-control " name="nuevoNombre" id="nuevoNombre" placeholder="Ingresar nombre" required>

            </div>

            <!-- ENTRADA PARA EL USUARIO -->

             <div class="form-group">
              
                 <input type="text" class="form-control " name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar correo electrónico" required>

             </div>
             <div class="row">
               <div class="col-md-6">
             <div class="form-group">
              
                <input type="text" class="form-control nuevoUser" name="nuevoUsuario" id="nuevoUsuario" placeholder="Ingresar usuario"  required>

                </div>
                </div>
            <div class="col-md-6">
            <!-- ENTRADA PARA LA CONTRASEÑA -->
             <div class="form-group">
            
                <input type="password" class="form-control " name="nuevoPassword" id="nuevoPassword" placeholder="Ingresar contraseña" required>

            </div>
            </div>
            </div>

            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->

            <div class="form-group">
              
                <select class="form-control " name="nuevoPerfil" id="nuevoPerfil">
                  
                  <option value="">Selecionar perfil</option>

                  <option value="Administrador">Administrador</option>

                  <option value="Especial">Especial</option>

                  <option value="Vendedor">Vendedor</option>

                </select>
            </div>
            </div>
           <div class="col-md-4">
            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="img-contenedor">

              <label for="nuevaFoto"></label>
              <input type="file" class="nuevaFoto" name="nuevaFoto" id="nuevaFoto">

              <p class="help-block">Peso máximo de la foto 2MB</p>

              <img src="vistas/img/man_default.svg" class="img-thumbnail previsualizar" width="130px">

            </div>

          </div>
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary btnusuario">Guardar</button>

        </div>

        <?php

         // $crearUsuario = ControladorUsuarios::ctrCrearUsuario();

        ?>

      </form>


</div>
  </div>
</div>

<!-- MODAL EDITAR USUARIO -->
<div id="modalEditarUsuario" class="modal fade modal-forms fullscreen-modal" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">EDITAR USUARIO</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

          <div class="col-md-8">
            <!-- ENTRADA PARA EL NOMBRE -->            
            <div class="form-group">
              
                <input type="text" class="form-control " id="editarDni" name="editarDni" value="" required>

              
            </div>
            <div class="form-group">
              
                <input type="text" class="form-control " id="editarNombre" name="editarNombre" value="" required>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->

             <div class="form-group">
              
                <input type="text" class="form-control " id="editarEmail" name="editarEmail" value="">

            </div>
            <!-- ENTRADA PARA EL USUARIO -->
            <div class="row">
            <div class="col-md-6">
             <div class="form-group">
                <input type="text" class="form-control " id="editarUsuario" name="editarUsuario" value="" readonly>

              </div>
              </div>

            <!-- ENTRADA PARA LA CONTRASEÑA -->
            <div class="col-md-6">
             <div class="form-group">              
              
                <input type="password" class="form-control " name="editarPassword" placeholder="Escriba la nueva contraseña">

                <input type="hidden" id="passwordActual" name="passwordActual">

            </div>
            </div>
            </div>

            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->

            <div class="form-group">
              
                            
               
              <?php
              
              if($_SESSION['perfil'] == 'Administrador'){
              ?>
    
                <select class="form-control " name="editarPerfil">
                  
                  <option value="" id="editarPerfil"></option>

                  <option value="Administrador">Administrador</option>

                  <option value="Especial">Especial</option>

                  

                </select>
                <?php }else{ ?>

      
                <select class="form-control " name="editarPerfil">

                  <option value="<?php echo $_SESSION['perfil'] ?>"><?php echo $_SESSION['perfil'] ?></option>

                </select>
                  
                <?php }?>

              
            </div>
            </div>

            <div class="col-md-4">
            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="img-contenedor">
              <label for="editarFoto"></label>
              <input type="file" class="nuevaFoto" name="editarFoto" id="editarFoto">

              <p class="help-block">Peso máximo de la foto 2MB</p>

              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="130px">

              <input type="hidden" name="fotoActual" id="fotoActual">

            </div>
            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary">Modificar usuario</button>

        </div>

     <?php

          $editarUsuario = new ControladorUsuarios();
          $editarUsuario -> ctrEditarUsuario();

        ?> 

      </form>

    </div>

  </div>

</div>

<?php 
$borrarUsuarios =  ControladorUsuarios::ctrBorrarUsuario();


?>

<div class="resultados"></div>
