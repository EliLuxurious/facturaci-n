<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
</section>



<!-- <section class="content"> -->
      <section class="container-fluid panel-medio">
          <!-- BOX INI -->
          <div class="box rounded">

                <div class="box-header ">
              <h3 class="box-title">Administración de categorias</h3>

              <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarCategoria"><i class="fas fa-plus-square"></i>Nueva categoría <i class="fa fa-th"></i>            
            </button>
            
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         

            <!-- table-bordered table-striped  -->
         <table class="table table-bordered dt-responsive tablas" width="100%">

          <thead>
            <tr>
              <th style="width:10px;">#</th>
              <th>Categoría</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
          <?php
               $item = null;
               $valor = null;
              $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
              
              foreach($usuarios as $key => $value):
              
          ?>
              <tr>
                <td><?php echo ++$key; ?></td>
                <td><?php echo $value['nombre']; ?></td>
                          <td>
                  <div class="btn-group">

                <button class="btn btn-warning btnEditarUsuario"  data-toggle="modal" data-target="#modalEditarCategoria"><i class="fas fa-user-edit"></i></button>

                <button class="btn btn-danger btnEliminarCategoria" ><i class="fas fa-trash-alt"></i></button>


                  </div>


                </td>

              </tr>

              <?php 
              endforeach;
              
              ?>                 
          </tbody>

      </table>


              

            </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
            </div>

            <!-- MODAL AGREGAR CATEGORIAS-->
  <!-- Modal -->
<div id="modalAgregarCategoria" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar categoría</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            <div id="respuestaAjax"></div> 

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaCategoria" id="nuevaCategoria" placeholder="Ingresar categoría" required>

              </div>

            </div>

           

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar categoría</button>

        </div>

        <?php

          $crearUsuario = new ControladorUsuarios();
          $crearUsuario -> ctrCrearUsuario();

        ?>

      </form>


</div>
  </div>
</div>

<!-- MODAL EDITAR USUARIO -->
<div id="modalEditarCategoria" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar categoría</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <input type="text" class="form-control input-lg" id="editarCategoria" name="editarCategoria" value="" required>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Modificar categoría</button>

        </div>

     <?php

          // $editarUsuario = new ControladorUsuarios();
          // $editarUsuario -> ctrEditarUsuario();

        ?> 

      </form>

    </div>

  </div>

</div>

<?php 
// $borrarUsuarios =  ControladorUsuarios::ctrBorrarUsuario();


?>