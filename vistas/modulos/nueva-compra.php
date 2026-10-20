<?php

use Controladores\ControladorEmpresa;
use Controladores\ControladorProductos;
use Controladores\ControladorCategorias;
use Controladores\ControladorSunat;

?>
<div class="content-wrapper panel-medio-principal">
<?php 
// $emisor = ControladorEmpresa::ctrEmisor();
?>
<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
  <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px;  border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
     <button class=""><i class="fas fa-file-invoice"></i> Nueva compra</button>
</div>
<div class="col-lg-9 col-md-12 col-sm-12 btns-dash">
  
</div>
</div>
   </div>   
    </section>
</section>



<!-- <section class="content"> -->
      <section class="container-fluid panel-medio">
          <!-- BOX INI -->
          <div class="box rounded">

            <div class="box-header" style="border: 0px; padding-top:5px;">
              <!-- <h3 class="box-title">Crear venta</h3> -->
         
            </div>
            <!-- /.box-header -->
            <div class="box-body">         
                <div class="row" >


                    <!-- FORMULARIO -->
                    <div class="col-lg-12 col-xs-12">
                    
                        <div class="box box-success" style="border-top: 0px;">
                            <div class="box-header"  style="border: 0px; padding:0px;">
                            
                            </div>
             
                            
                            <form role="form" method="post" class="formCompra" id="formCompra">
                            
                            <input type="hidden" class="" id="tipo_cambio" name="tipo_cambio" value="">
                                                   
                            <div class="box-body" style="border: 0px; padding-top:0px; "> 

                            <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">DATOS DEL COMPROBANTE:</legend>
                          <!-- PRIMERA ENTRADA FORM -->
                                <div class="box" style="border: 0px; padding-top:0px;" >

                                   <!-- ENTRADA SERIE-->
                                   <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                   
                                 <span class="input-group-addon"><i class="fa fa-key"></i></span>
                               
                                  <select  class="form-control" name="tipoComprobante" id="tipoComprobante" value="" >
                                      <option value="">Tipo comprobante</option>
                                      <option value="01">Factura</option>
                                      <option value="03">Boleta</option>
                                      <option value="07">Nota de crédito</option>
                                      <option value="08">Nota de débito</option>
                                  </select>
                                                                
                                 </div>
                                </div>
                                </div>

                                <!-- ENTRADA TIPO MONEDA-->
                                <div class="col-md-3 col-xs-6">
                                  <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-money-bill"></i></span>
                                    <select class="form-control" id="moneda" name="moneda">
                                        <option value="PEN">Soles (S/)</option>
                                        <option value="USD">Dólares Americanos ($)</option>
                                    </select>
                                </div>
                                </div>
                                </div>

                             
                                       <!-- ENTRADA FECHA DOC-->
                                <div class="col-md-2 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                 <input type="text" class="form-control" name="fechaDoc" id="fechaDoc"  value="<?php echo date("d/m/Y") ?>" required>
                                  </div>                               
                                </div>
                                </div>
                                       <!-- ENTRADA SERIE DOC-->
                                <div class="col-md-2 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                 <span class="input-group-addon"><i class="fas fa-barcode"></i></span>
                                 <input type="text" class="form-control" name="serieDoc" id="serieDoc" required placeholder="Serie">
                                  </div>                               
                                </div>
                                </div>
                                       <!-- ENTRADA CORRELATIVO DOC-->
                                <div class="col-md-2 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                 <span class="input-group-addon"><i class="fa fa-file-invoice"></i></span>
                                 <input type="text" class="form-control" name="correlativoDoc" id="correlativoDoc" required placeholder="Correlativo">
                                  </div>                               
                                </div>
                                </div>
                                </div>
                                                  
                                <!-- <div class="row">
                                  <div class="col-md-12 col-xs-6">
                                  <input type="hidden" class="form-control" id="correlativo">
                              </div>
                                  </div> -->
                                <!-- ENTRADA CLIENTE -->
                                <div class="row contenedor-notascd">
                                  
                                </div>
                           
                                    <div class="row">
                                    <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">DATOS PROVEEDOR:</legend>
                                  
                                    <div class="col-md-3">
                                    <div class="form-group">
                                   <div class="input-group">
                                     <!-- ID CLIENTE -->                                   
                                     <input type="hidden" name="idProveedor" id="idProveedor">
                                     
                                    <span class="input-group-addon"><i class="fas fa-id-card"></i></span>
                                   
                                      <select class="form-control" name="tipoDoc" id="tipoDoc">
                                        <?php
                                        $item = null;
                                        $valor = null;
                                        $tipoDocumento = ControladorSunat::ctrMostrarTipoDocumento($item, $valor);
                                      foreach ($tipoDocumento as $key => $value){
                                        
                                         echo "<script>$('#tipoDoc').val(6);</script>";
                                        
                                        echo '<option value='.$value['codigo'].'>'.$value['descripcion'].'</option>';
                                      }
                                       ?>
                                      </select>
                                      
                                      </div>
                                      </div>
                                    </div>

                                    <!-- ENTRADA DOCUMENTO -->
                                    <div class="col-md-4">
                                    
                                    <div class="form-group">
                                    <div class="input-group">
                                    <div id="rucActivo"></div>
                                    <input type="text" class="form-control" id="docIdentidad" name="docIdentidad" placeholder="Ingrese número de documento...">
                                    <span class="input-group-addon btn buscarRucP"><i class="fa fa-search"></i></span> 
                                    <div id="reloadC"></div>
                                    <div class="resultadoProveedor" idCliente=""><a href="#" class="btn-add-p" ></a></div>
                                    </div>
                                    </div>
                                    </div>
                                    <!-- ENTRADA RESULTADO DOCUMENTO -->
                                    <div class="col-md-5">
                                    <div class="form-group">
                                    <div class="input-group-adddon">
                                    <input type="text" class="form-control" id="razon_social"" name="razon_social" placeholder="Ingrese nombre o razón social...">
                                    <!-- <span class="input-group-addon"></span>  -->
                                    </div>
                                    </div>
                                    </div>

                                     </div>
                                <!-- ENTRADA CLIENTE 2 -->
                                <div class="row">
                                    <!-- ENTRADA DOCUMENTO -->
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="input-group-adddon">
                                    <input type="text" class="form-control" id="direccion"" name="direccion" placeholder="Ingrese la dirección...">
                                    <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                                    </div>
                                    </div>
                                    </div>

                                    <!-- ENTRADA DOCUMENTO -->
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="input-group-adddon">
                                    <input type="text" class="form-control" id="ubigeo"" name="ubigeo" placeholder="Ingrese codigo de ubigeo...">
                                    <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span>  -->
                                    </div>
                                    </div>
                                    </div>
                                    <!-- ENTRADA RESULTADO DOCUMENTO -->
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="input-group-adddon">
                                    <input type="text" class="form-control" id="celular"" name="celular" placeholder="Ingrese su número de celular...">
                                    <!-- <span class="input-group-addon"></span>  -->
                                    </div>
                                    </div>
                                    </div>

                                     </div>
                      
                                     
                                <!-- ENTRADA PARA AGREGAR PRODUCTOS -->
                                <div class="col-lg-12 col-xs-12">
                                  <div class="row nuevoProductoC">

                                  <div class="flex">
                                  <button type="button" class="btn btn-success btn-radius pull-right btnproser" data-toggle="modal" data-target="#modalAgregarProducto"><i class="fas fa-plus-square"></i>Nuevo producto o servicio <i class="fa fa-th"></i>            
                                    </button>
                                  <button type="button" class="btn btn-primary pull-right btn-agregar-carrito" data-toggle="modal" data-target="#modalProductosVenta" ><i class="fas fa-cart-plus fa-lg"></i> Agregar Productos o Servicios</button>

                                    </div>
                                  <div class="table-responsive items-c">
                                    <!-- BOTÓN PARA AGREGAR PRODUCTO-->
                                  <table class="table tabla-items">
                                    <thead>
                                      <tr>
                                      <th>Código</th>
                                      <th>Cantidad</th>
                                      <th>Uni/medida</th>
                                      <th>Descripción</th>
                                      <th>Precio unitario</th>
                                      <th>Valor unitario</th>
                                      <th>Sub.Total</th>
                                      <th>Total</th>
                                      <th></th>
                                      </tr>
                                    </thead>
                                    <tbody id="itemsP">
                                    
                                    </tbody>
                                  
                                  </table>
                                  </div>
                                  <!-- FIN ENTRADA AGREGAR PRODUCTOS  -->

                                 
                              <div class="box">   

                              <!-- DESCUENTO GLOBAL| -->
                              <div class="col-md-6 col-sm-12">  
                             <table class="table" style="border:0px">
                               <thead>
                                 <tr>
                                   
                                   <th>DESCUENTO</th>
                                 </tr>
                               </thead>
                               <tbody>
                                 <tr>
                                

                                    <td>
                                      <div class="box">
                                        <div class="col col-lg-12 col-sm-12 col-xs-12">
                                                          
                                    <div class="form-group">
                                    <div class="input-group">

                                    <span class="input-group-addon"><i class="fas fa-money-bill-wave"></i></span>
                                                                 
                                    <input type="number" class="form-control" min="0" placeholder="0.00" id="descuentoGlobalC"" name="descuentoGlobalC" value="0"" >

                                    </div>
                                    </div>
                                  </td>
                                 </tr>
                                 <!-- MÉTODO DE PAGO ========] -->
                                 <tr>
                                   <th style="">MÉTODO DE PAGO:</th>
                                  
                                   </tr>

                                   <tr>
                                    <td>
                                 <div class="form-group">
                                    <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-money-bill-wave"></i></span>
                                    <select style="width: 100%;" class="form-control" id="metodopago" name="metodopago">
                                      <option value="009">En efectivo</option>
                                      <option value="001">Depósito en cuenta</option>
                                      <option value="005">Tarjeta de débito</option>
                                      <option value="006">Tarjeta de crédito</option>
                                      <option value="003">Transferencia bancaria</option>
                                      <option value="002">Giro</option>
                                    </select> 
                                  </div>
                                    </div>
                                  </td>
                                </tr>
                                 <!-- FIN MÉTODO DE PAGO ======== -->
                                 <!-- COMENTARIO=========== -->
                                 <tr>
                                   <th>OBSERVACIONES</th>                                  
                                 </tr> 
                                 <tr>
                                   <td colspan="2">
                                     <div class="form-group">
                                     <div class="input-group">
                                     <span class="input-group-addon"><i class="far fa-comment-dots"></i></span>
                                    <textarea class="form-control" name="comentario" id="comentario" cols="50" rows="4">

                                    </textarea>
                                    </div>
                                  </div>
                                  </td>
                                </tr>
                                
                                 
                                <!-- FIN COMENTARIO======= -->
                               </tbody>
                               
                             </table>   
                             
                            
                            </div>
                            <!-- FIN DESCUENTO GLOBAL -->

                                 <!-- //ENTRADA DE REMUMMEN TOTALES  -->  
                                <div class="col-md-6 col-sm-12">                             
                                  <div class="table-responsive">
                                  <table class="table  tabla-totales">

                                  <thead>
                                  <tr>
                                  <th></th>
                                  <th>RESUMEN</th>
                                  </tr>
                                  </thead>
                                  <tbody class="totales">

                                    <tr class="">
                                    <td>SubTotal</td><td><input type="text" class="" name="subtotalc" id="subtotalc" value="0.00" /></td></tr>
                                    </tr>
                                    <tr class="">
                                    <td>Op.Gravadas</td><td><input type="text" class="" name="op_gravadas" id="op_gravadas" value="0.00" /></td></tr>
                                    </tr>
                                    <tr class="">
                                    <td>Op.Exoneradas</td><td><input type="text" class="" name="op_exoneradas" id="op_exoneradas" value="0.00" /></td></tr>
                                    </tr>
                                    <tr class="">
                                    <td>Op.Inafectas</td><td><input type="text" class="" name="op_inafectas" id="op_inafectas" value="0.00" /></td></tr>
                                    </tr>
                                    <tr class="">
                                    <td>Op.gratuitas</td><td><input type="text" class="" name="op_gratuitas" id="op_gratuitas" value="0.00" /></td></tr>
                                    </tr>
                                    <tr class="">
                                    <td>Descuento (-)</td><td><input type="text" class="" name="descuento" id="descuento"value="0.00" /></td></tr>
                                    </tr>
                                    <tr class="">
                                    <td>ICBPER</td><td><input type="text" class="" name="icbper" id="icbper" value="0.00" /></td></tr>
                                    </tr>
                                    <tr class="">
                                    <td>IGV(18%)</td><td><input type="text" class="" name="igvc" id="igvc" value="0.00" /></td></tr>
                                    </tr>

                                    <tr class="">
                                    <td>Total</td><td><input type="text" class="" name="totalc" id="totalc" value="0.00" /></td></tr>
                                    </tr>

                                  
                                    </tbody>
                                  </table>                          
                                  </div>
                                  <!-- // FIN ENTRADA DE REMUMMEN TOTALES  -->
                                  </div>
                                  </div>   
                                 </div>
                                   
                                    <hr>

                               

                                </div>
                            
                            </div>
                  


                            <div class="box-footer contenedor-btns-carrito">
                            
                                <button type="button" class="btnGuardarCompra"><i class="far fa-save"></i></button>
                                 
                                 <!-- BOTÓN PARA ELIMINAR CARRO-->
                                 <button type="button" class="btnEliminarCarro"><i class="fas fa-trash-alt"></i></button>
                            </div>


                            </form>
                    </div>
                    </div>
                    
                   
                </div>

            </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
    </div>

                            <!-- Modal AGGREGAR PRODUCTOS -->
  <div class="modal fade bd-example-modal-lg" id="modalProductosVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     
      <div class="modal-body contenedor-pro">
          
             <form action="" method="post" name="formItems" id="formItems">
             <div class="box">
                            <!-- ENTRADA CORRELATIVO DOC-->
                <input type="hidden" name="idProductoc" id="idProductoc" value="">
                <div class="col-md-12">
                    <div class="form-group c-productos">
                     <span class="input-group-addonn">Descripción</span>
                     <input type="text" class="form-control" name="descripcion" id="descripcion" required placeholder="Descripción" autocomplete="off">
                     <div class="p-productos"></div>
                       </div>                               
                    </div>
                
              </div>

                  <div class="box">

                  <div class="col-md-4">
                    <div class="form-group">
                     
                     <span class="input-group-addonn">Tipo afectación</span>
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

                  <div class="col-md-4">
                    <div class="form-group">
                     
                     <span class="input-group-addonn">U. Medida</span>
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
                       <div class="col-md-4">
                    <div class="form-group">
                    
                     <span class="input-group-addonn">Código</span>
                     <input type="text" class="form-control" name="codigo" id="codigo" required placeholder="Codigo">
                       </div>                               
                    
                    </div>
                  </div>
                  <div class="box">

                    <div class="col-md-4">
                      <div class="form-group">
                        <span class="input-group-addonn">Precio Unitario</span>
                        <input type="text" class="form-control" name="precio_unitario" id="precio_unitario"  value="0" readonly>
                      </div>                               
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <span class="input-group-addonn">Valor Unitario</span>
                        <input type="text" class="form-control" name="valor_unitario" id="valor_unitario">
                      </div>                               
                    </div>
                    <div class="col-md-4">
                     <div class="form-group">
                      <span class="input-group-addonn">Cantidad</span>
                      <input type="number" class="form-control" name="cantidad" id="cantidad" min="1" value="1" required>
                        </div>                               
                     </div>
                     </div>
                    <div class="box">
                    <div class="col-md-4">
                    <div class="form-group">
                     <span class="input-group-addonn">Sub Total</span>
                     <input type="text" class="form-control" name="subtotal" id="subtotal" required placeholder="">
                       </div>                               
                    </div>
                    <div class="col-md-4">
                    <div class="form-group">
                     <span class="input-group-addonn">IGV de la linea</span>
                     <input type="text" class="form-control" name="igv" id="igv" required placeholder="">
                       </div>                               
                    </div>
                    <div class="col-md-4">
                    <div class="form-group">
                     <span class="input-group-addonn">Total</span>
                     <input type="text" class="form-control" name="total" id="total" required placeholder="">
                     
                       </div>                               
                    </div>
                  </div>
                  <div class="col-lg-12">
                  <div class="box">
                  <div class="col-md-2">
                  <div class="form-group">  
                  <span class="input-group-addonn">ICBPER</span>
                  <div class="modo-contenedor-icbper">                  
                  <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" data-onstyle="success" data-offstyle="danger" id="icbper" name="icbper" value="0.30" data-size="small" data-width="80">
                  </label>
                    
                    </div>
                  </div>
                  </div>
                  <div class="col-md-4 col-xs-6">
                    <div class="form-group">
                     <span class="input-group-addonn">Descuento</span>
                     <input type="text" class="form-control" name="descuento_item" id="descuento_item" required placeholder="" value="0">
                     
                       </div>                               
                    </div>
                  </div>
                  </div>
                  

                  <div class="col-12" style="text-align: center">
                    <button class="btn btn-primary btn-lg" id="btnAddItem"><i class="fas fa-plus"></i></button>

                  </div>
              </form>
      </div>
      
           
           <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
      </div>
      
    </div>
  </div>
      <!-- FIN MODAL            -->

                <!-- MODAL AGREGAR PRODUCTO-->
  <!-- Modal -->
  <div id="modalAgregarProducto" class="modal fade modal-forms fullscreen-modal" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post" id="formProductos" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">NUENO PRODUCTO O SERVICIO</h4>

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

          <button type="submit" class="btn btn-primary btn-nuevo-producto">Guardar producto</button>

        </div>

        <?php

              // $crearProducto = new ControladorProductos();
              // $crearProducto-> ctrCrearProducto();

        ?>

      </form>


</div>
  </div>
</div>