<?php
use Controladores\ControladorCategorias;
?>

<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
  <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
     <button class=""><i class="fas fa-file-invoice"></i> Categoría</button>
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
              <h3 class="box-title">Administración de categorias</h3>

              <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarCategoria"><i class="fas fa-plus-square"></i>Nueva categoría <i class="fa fa-th"></i>            
            </button>
     
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         

            <!-- table-bordered table-striped  -->
         <table class="table  dt-responsive tablas tbl-t" width="100%">

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
              $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
              
              foreach($categorias as $key => $value):
              
          ?>
              <tr class="id-cat<?php echo $value['id']; ?>">
                <td class="numeracion"><?php echo ++$key; ?></td>
                <td class="text-uppercase"><?php echo $value['categoria']; ?></td>
                  <td>
                  <div class="btn-group">

                <button class="btn btn-warning btnEditarCategoria" idCategoria="<?php echo $value['id']; ?>"   data-toggle="modal" data-target="#modalEditarCategoria"><i class="fas fa-user-edit"></i></button>
               
                <?php if($_SESSION['perfil'] == 'Administrador'):?>
                <button class="btn btn-danger btnEliminarCategoria" idCategoria="<?php echo $value['id']; ?>"><i class="fas fa-trash-alt"></i></button>
                  
                <?php endif; ?>

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
<div id="modalAgregarCategoria" class="modal fade modal-forms" role="dialog">
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

                <input type="text" class="form-control" name="nuevaCategoria" id="nuevaCategoria" placeholder="Ingresar categoría" required>

              </div>

            </div>

           

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary">Guardar categoría</button>

        </div>

        <?php

          $crearCategoria = new ControladorCategorias();
          $crearCategoria -> ctrCrearCategoria();

        ?>

      </form>


</div>
  </div>
</div>

<!-- MODAL EDITAR CATEGORIA -->
<div id="modalEditarCategoria" class="modal fade modal-forms" role="dialog">
  
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

                <input type="text" class="form-control" id="editarCategoria" name="editarCategoria" value="" required>
                <input type="hidden" id="idCategoria" name="idCategoria">

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary">Modificar categoría</button>

        </div>

     <?php

          $editarCategoria = new ControladorCategorias();
          $editarCategoria -> ctrEditarCategoria();

        ?> 

      </form>

    </div>

  </div>

</div>

<?php 


?>