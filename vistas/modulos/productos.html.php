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
              <h3 class="box-title">Administración de productos</h3>

              <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarProducto"><i class="fas fa-plus-square"></i>Nuevo producto <i class="fa fa-th"></i>            
            </button>
            
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         

            <!-- table-bordered table-striped  -->
         <table class="table table-bordered dt-responsive tablas" width="100%">

          <thead>
            <tr>
              <th style="width:10px;">#</th>
              <th>Imagen</th>
              <th>Código</th>
              <th>Descripción</th>
              <th>Categoría</th>
              <th>Stock</th>
              <th>Precio Compra</th>
              <th>Precio Venta</th>
              <th>Fecha add</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
          <?php
               $item = null;
               $valor = null;
              $productos = ControladorProductos::ctrMostrarProductos($item, $valor);
              
              foreach($productos as $key => $value):
              
          ?>
              <tr>
                <td><?php echo ++$key; ?></td>
                <td><img src="<?php echo $value['imagen']; ?>" alt="" class="img-thumbnail" width="40px"></td>
                <td><?php echo $value['codigo']; ?></td>
                <td><?php echo $value['descripcion']; ?></td>
                <td><?php echo $value['id_categoria']; ?></td>
                <td><?php echo $value['stock']; ?></td>
                <td><?php echo $value['precio_compra']; ?></td>
                <td><?php echo $value['precio_venta']; ?></td>
                <td><?php echo $value['fecha']; ?></td>
                <td><div class="btn-group">

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

            <!-- MODAL AGREGAR PRODUCTO-->
  <!-- Modal -->
<div id="modalAgregarProducto" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA DEL CÓDIGO -->
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoCodigo" id="nuevoCodigo" placeholder="Ingresar código" required>

              </div>

            </div>
            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDescripcion" id="nuevaDescripcion" placeholder="Ingresar descripción" required>

              </div>

            </div>
            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <select class="form-control input-lg" name="nuevaCategoria">
                  
                  <option value="">Selecionar categoría</option>

                  <option value="Administrador">Administrador</option>

                  <option value="Especial">Especial</option>

                  <option value="Vendedor">Vendedor</option>

                </select>

              </div>

            </div>
             <!-- ENTRADA PARA STOCK -->
             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                <input type="number" class="form-control input-lg" min="0" name="nuevoStock" id="nuevoStock" placeholder="Ingresar stock" required>

              </div>

            </div>
             <!-- ENTRADA PARA PRECIO COMPRA -->
             <div class="form-group row">
              <div class="col-xs-6">
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoPrecioCompra" id="nuevoPrecioCompra" placeholder="Ingresar precio de compra" required>

              </div>
                </div>
             <!-- ENTRADA PARA PRECIO VENTA-->
            
             <div class="col-xs-6">
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoPrecioVenta" id="nuevoPrecioVenta" placeholder="Ingresar precio de venta" required>

              </div>
              <br>
            <!-- CHECKBOX PARA PORCENTAJE -->
                <div class="col-xs-6">

                  <div class="form-group">
                  <label>
                  <input type="checkbox" class="minimal porcentaje" checked>
                  Utilizar porcentaje
                  </label>
                  
                  </div>

                </div>
                <div class="col-xs-6" style="padding:0;">
                  <div class="input-group">
                  <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>
                  <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                  </div>
                </div>

              </div>

            </div>
               <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaFoto" name="nuevaFoto">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div>

           

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar producto</button>

        </div>

        <?php

          // $crearUsuario = new ControladorUsuarios();
          // $crearUsuario -> ctrCrearUsuario();

        ?>

      </form>


</div>
  </div>
</div>

<!-- MODAL EDITAR PRODUCTO -->
<div id="modalEditarCategoria" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar producto</h4>

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

          <button type="submit" class="btn btn-primary">Modificar producto</button>

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