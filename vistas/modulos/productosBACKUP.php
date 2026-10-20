<?php 
use Controladores\ControladorEmpresa;
use Controladores\ControladorProductos;
use Controladores\ControladorCategorias;
use Controladores\ControladorSunat;
$empresa_igv = ControladorEmpresa::ctrEmisor();

?>
<div class="content-wrapper panel-medio-principal">

<input type="hidden" id="empresa_igv" name="empresa_igv" value="<?php echo $empresa_igv['afectoigv'] ?>">
<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
     
     <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
  <button class=""><i class="fas fa-box-open"></i> Productos o Servicios</button>
</div>
<div class="col-lg-9 col-md-12 col-sm-12 btns-dash">
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
              <h3 class="box-title">Administración de productos</h3>

              <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarProducto"><i class="fas fa-plus-square"></i>Nuevo producto o servicio <i class="fa fa-th"></i>            
            </button>
            
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         
   <!-- table-bordered table-striped  -->

   <div class="contenedor-busqueda">
          <div class="input-group-search">

          <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadProductos(1)">
                <option value="5">5</option>
                <option value="10"">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>

              <div class="input-search">
              <input type="search" class="search" id="searchProducto" name="searchProducto" placeholder="Buscar..." onkeyup="loadProductos(1)">
              <span class="input-group-addo"><i class="fa fa-search"></i></span> 
              <input type="hidden" id="perfilOculto" value="<?php echo $_SESSION['perfil'] ?>">
              </div>
              </div>
              </div>

          <div class="table-responsive">
            <!-- table-bordered table-striped  -->
         <table class="table  dt-responsive tablaProductos tbl-t" width="100%">

          <thead>
          
            <tr>
              <th style="width:10px;">#</th>
              <th>Imagen</th>
              <th>Código</th>
              <th>Serie</th>
              <th>Descripción</th>
              <th>Categoría</th>
              <th>Stock</th>
              <th>Precio venta</th>
              <th>Fecha add</th>
              <th width="100px">Acciones</th>
            </tr>
          </thead>
          <?php

          $listaProductos = new ControladorProductos();
          $listaProductos-> ctrListarProductos();

          ?>
        
      </table>
    </div>

              

            </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
            </div>

            <!-- MODAL AGREGAR PRODUCTO-->
  <!-- Modal -->
<div id="modalAgregarProducto" class="modal fade modal-forms fullscreen-modal" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">AGREGAR PRODUCTO</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

          
            <div class="col-md-8">
             <!-- PRIMERA SECCIÓN============= -->
                   <!-- ENTRADA PARA LA DESCRIPCIÓN -->
              <div class="row" >
              <div class="col-md-4">
              <div class="form-group">
              
        
                <select class="form-control" name="nuevaCategoria" id="nuevaCategoria" required>
                  
                  <option value="">Selecionar categoría</option>
                  <?php
                  $item = null;
                  $valor = null;
                  $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
                  foreach ($categorias as $k => $value):

                    echo '<option value="'.$value['id'].'">'.$value['categoria'].'</option>';

                  endforeach; 
                  ?>


                </select>

            </div>
            </div>
            

            <!-- ENTRADA PARA DEL CÓDIGO -->
            <div class="col-md-4">
            <div class="form-group">
              
                <input type="text" class="form-control" name="nuevoCodigo" id="nuevoCodigo" placeholder="Código" readonly required>


            </div>
            </div>
            <!-- ENTRADA PARA DEL CÓDIGO -->
            <div class="col-md-4">
            <div class="form-group">
              
                <input type="text" class="form-control" name="nuevaSerie" id="nuevaSerie" placeholder="Serie del producto">


            </div>
            </div>
            </div>
    
            <!-- FIN PRIMERA SECCIÓN======== -->
            <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
            <div class="row">

            <div class="col-md-6">
            <div class="form-group">              
          

               <select class="form-control" name="tipo_afectacion" id="tipo_afectacion">
                  <?php 
                  $item = null;
                  $valor = null;
                  $unidad_medida = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
                  foreach($unidad_medida as $k => $value){

                  
                      echo "<option value='".$value['codigo']."'>".$value['descripcion']."</option>";
                    
                  }
                  ?>
               </select>

           
            </div>
            </div>
            <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
            <div class="col-md-6">
            <div class="form-group">
              
                

               <select class="form-control" name="unidad" id="unidad">
                  <?php 
                  $item = null;
                  $valor = null;
                  $unidad_medida = ControladorSunat::ctrMostrarUnidadMedida($item, $valor);
                  foreach($unidad_medida as $k => $value){

                    if($value['activo'] == 's'){

                      echo "<option value='".$value['codigo']."'>".$value['descripcion']."</option>";
                    }
                  }
                  ?>
               </select>

              </div>

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <div class="row">
              <div class="col-md-9">
                <div class="form-group">              
            
                <input type="text" class="form-control" name="nuevaDescripcion" id="nuevaDescripcion" placeholder="Ingresar descripción" required>            

            </div>
            </div>
   
             <!-- ENTRADA PARA STOCK -->
             <div class="col-md-3">
             <div class="form-group">              

                <input type="number" class="form-control" min="0" name="nuevoStock" id="nuevoStock" placeholder="Ingresar stock" required>

            </div>
            </div>
            </div>
            <!-- ENTRADA PARA PRECIO VENTA-->
            <div class="row">
             <div class="col-md-6" style="">
             <div class="form-group">
      

                <input type="text" class="form-control" name="nuevoPrecioUnitario" id="nuevoPrecioUnitario" placeholder="Ingresar precio unitario" step="any" required>

            </div>
            </div>
            
             <!-- ENTRADA PARA PRECIO COMPRA -->
             <div class="col-md-6">
             
              <div class="form-group">
              

                <input type="text" class="form-control" name="nuevoValorUnitario" id="nuevoValorUnitario" placeholder="Valor unitario" step="any" readonly  required>

                </div>
                </div>
                </div>
            
            <!-- CHECKBOX PARA PORCENTAJE -->
             <div class="row">
            <div class="col-md-6">

              <div class="form-group">              
         

                <input type="text" class="form-control"  name="nuevoigv" id="nuevoigv" placeholder="IGV 18%" readonly>

             
                </div>  
                </div>     
              
                  <!-- ENTRADA IGV  -->
                <div class="col-md-6" style="">
                <div class="form-group">              
              

                <input type="number" class="form-control"  name="nuevoPrecioCompra" id="nuevoPrecioCompra" placeholder="Precio compra" readonly>


            </div>
                </div>
                </div>
                </div>
                <div class="col-md-4">


           
               <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="img-contenedor">
              
          
              <label for="nuevaImagen"></label>
              <input type="file" class="nuevaImagen" name="nuevaImagen" id="nuevaImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="130px">

            </div>

           

          </div>
        </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary">Guardar producto</button>

        </div>

        <?php

              $crearProducto = new ControladorProductos();
              $crearProducto-> ctrCrearProducto();

        ?>

      </form>


</div>
  </div>
</div>

<!-- MODAL EDITAR PRODUCTO-->
  <!-- Modal -->
  <div id="modalEditarProducto" class="modal fade modal-forms  fullscreen-modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">EDITAR PRODUCTO</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">
          <div class="col-md-8">  
            <div class="row">
              <div class="col-md-4">
                  <!-- ENTRADA PARA LA DESCRIPCIÓN -->
             <div class="form-group">        

                <select class="form-control " name="editarCategoria"  readonly required>
                  
                  <option value="" id="editarCategoria"></option>
                 
                </select>
              </div>
              </div>
            <!-- ENTRADA PARA DEL CÓDIGO -->
            <div class="col-md-4">
            <div class="form-group">
           

                <input type="text" class="form-control " name="editarCodigo" id="editarCodigo" readonly required>

            </div>
            </div>
            <!-- ENTRADA PARA DEL CÓDIGO -->
            <div class="col-md-4">
            <div class="form-group">
           

                <input type="text" class="form-control " name="editarSerie" id="editarSerie">

            </div>
            </div>
            </div>


              <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
              <div class="row">
                <div class="col-md-6">
               <div class="form-group">
                 
               <select class="form-control" name="editarAfectacion" id="editarAfectacion">
                 
                  <?php 
                  $item = null;
                  $valor = null;
                  $unidad_medida = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
                  foreach($unidad_medida as $k => $value){

                  
                      echo "<option value='".$value['codigo']."'>".$value['descripcion']."</option>";
                    
                  }
                  ?>
               </select>

            </div>
            </div>
            
            <!-- ENTRADA PARA UNIDAD DE MEDIDA -->
            <div class="col-md-6">
            <div class="form-group">
              
               <select class="form-control" name="editarUnidadMedida" id="editarUnidadMedida">
                 
                  <?php 
                  $item = null;
                  $valor = null;
                  $unidad_medida = ControladorSunat::ctrMostrarUnidadMedida($item, $valor);
                  foreach($unidad_medida as $k => $value){

                    if($value['activo'] == 's'){

                      echo "<option value='".$value['codigo']."'>".$value['descripcion']."</option>";
                    }
                  }
                  ?>
               </select>

              </div>
              </div>
              </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <div class="row">
            <div class="col-md-9">
            <div class="form-group">              

                <input type="text" class="form-control " name="editarDescripcion" id="editarDescripcion" required>

              </div>
              </div>

             <!-- ENTRADA PARA STOCK -->
             <div class="col-md-3">
             <div class="form-group">
              
                <input type="number" class="form-control " min="0" name="editarStock" id="editarStock"  required>

            </div>
            </div>
            </div>
        <!-- ENTRADA PARA PRECIO VENTA-->
          <div class="row">
             <div class="col-md-6" style="">
             <div class="form-group">
              

                <input type="text" class="form-control" name="editarPrecioUnitario" id="editarPrecioUnitario" placeholder="Ingresar precio de venta" step="any" required>

            </div>
            </div>
            
             <!-- ENTRADA PARA PRECIO COMPRA -->
             <div class="col-md-6">
             
              <div class="form-group">
            

                <input type="text" class="form-control" name="editarValorUnitario" id="editarValorUnitario" placeholder="Valor unitario" step="any" readonly  required>

              </div>
                </div>
                </div>
            
            <!-- CHECKBOX PARA PORCENTAJE -->
             <div class="row">
            <div class="col-md-6">

              <div class="form-group">              
              
                <input type="text" class="form-control"  name="editarigv" id="editarigv" placeholder="Ingresar IGV" readonly>

                </div>  
                </div>     
              
                  <!-- ENTRADA IGV  -->
                <div class="col-md-6" style="">
                <div class="form-group">              
              
                <input type="number" class="form-control"  name="editarPrecioCompra" id="editarPrecioCompra" placeholder="Precio compra" readonly>

              </div>
                </div>
               <!-- ENTRADA PARA SUBIR FOTO -->
            </div>
            </div>
            <div class="col-md-4">
              
            <div class="img-contenedor">

              <label for="editarImagen"></label>
              <input type="file" class="nuevaImagen" name="editarImagen" id="editarImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="130px">
              <input type="hidden" name="imagenActual" id="imagenActual">
            </div>

           

          </div>
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </div>

        <?php

              $editarProducto = new ControladorProductos();
              $editarProducto-> ctrEditarProducto();

        ?>

      </form>


</div>
  </div>
</div>
<!-- <div id="resultados"></div> -->

<?php
   $eliminarProducto = new ControladorProductos();
   $eliminarProducto -> ctrEliminarProducto();
   