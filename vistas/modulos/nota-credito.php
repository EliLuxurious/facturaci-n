<?php

use Controladores\ControladorSunat;

?>
<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
  <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
     <button class=""><i class="fas fa-file-invoice"></i> Nueva nota de crédito</button>
</div>
<div class="col-md-9  col-sm-12 btns-dash">
  
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
                            <?php 
                            $serieresultado = '';
                            $tipoComprobante = '';
                            if(isset($_POST['resultadoSerie'])){
                              $serieresultado = $_POST['resultadoSerie'];
                              $serieresultado;
                              $tipoComprobante = $_POST['tipoComprobante'];
                              $tipoComprobante;
                            }
                            ?>
                            
                            <form role="form" method="post" class="formVenta" id="formVenta">
                            <input type="hidden" name="ruta_comprobante" id="ruta_comprobante" value="<?php echo  $_GET["ruta"]?>">
                            <input type="hidden" class="" id="tipo_cambio" name="tipo_cambio" value="">
                            <input type="hidden" class="" id="fecha" name="fecha" value="<?php echo date("Y-m-d")?>">
                            <input type="hidden" class="" id="tipoComp" name="tipoComp" value="<?php echo $tipoComprobante ?>">
                            <div class="box-body" style="border: 0px; padding-top:0px; "> 

                          <!-- PRIMERA ENTRADA FORM -->
                                <div class="box" style="border: 0px; padding-top:0px;" >

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

                                <!-- ENTRADA SERIE-->
                                <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                   
                                 <span class="input-group-addon"><i class="fa fa-key"></i></span>
                               
                                  <select  class="form-control" name="serie" id="serie" value="" >
                                  <?php 
                                  if($_GET["ruta"] == "nota-credito"){

                                  $valor = "07";
                                  $serieComprobante = ControladorSunat::ctrMostrarSerie($valor);
                                  foreach ($serieComprobante as $key => $value){
                                    echo '<option value='.$value['id'].' id="idSerie">'.$value['serie'].'</option>';
                                  }
                                  
                                }
                                 ?>
                                  </select>
                                                                
                                 </div>
                                </div>
                                </div>

                                       <!-- ENTRADA FECHA DOC-->
                                <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                 <input type="text" class="form-control" name="fechaDoc" id="fechaDoc"  value="<?php echo date("d/m/Y") ?>" required>
                                  </div>                               
                                </div>
                                </div>

                                       <!-- ENTRADA FECHA VENCIMIENTO-->
                                <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                 <input type="text" class="form-control" name="fechaVence" id="fechaVence"  value="<?php echo date("d/m/Y") ?>" required>
                                  </div>                               
                                </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12 col-xs-6">
                                  <input type="hidden" class="form-control" id="correlativo">
                              </div>
                                  </div>
                                <!-- ENTRADA DOCUMENTO A MODIFICAR -->
                                <div class="row">
                                    <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">Documento a modificar:</legend>
                                  
                                    <div class="col-md-3">
                                    <div class="form-group">
                                   <div class="input-group">
                                     <!-- ID CLIENTE -->                                   
                                     <input type="hidden" name="idCliente" id="idCliente">
                                     
                                    <span class="input-group-addon"><i class="fas fa-id-card"></i></span>
                                   
                                      <select class="form-control" name="tipoComprobante" id="tipoComprobante">
                                        <option value="01">Factura</option>
                                        <option value="03">Boleta</option>
                                      </select>
                                      
                                      </div>
                                      </div>
                                    </div>

                                    <!-- ENTRADA DOCUMENTO SERIE CORRELATIVO-->
                                    <div class="col-md-4">
                                    <div id="reloadC"></div>
                                    <div class="form-group">
                                    <div class="input-group">
                                    <!-- SERIE Y CORRELATIVO HIDDEN -->
                                    <input type="hidden" name="serieComprobante" id="serieComprobante">
                                    <input type="hidden" name="numeroComprobante" id="numeroComprobante">
                                    <!-- FIN SERIE Y CORRELATIVO HIDDEN -->
                                   <!-- FIN SERIE Y CORRELATIVO HIDDEN -->
                                   <input type="text" class="form-control" id="serieNumero" name="serieNumero" placeholder="Ingrese la serie y numero..." value="<?php echo $serieresultado; ?>">

                                <span class="input-group-addon btn buscarSerie"><i class="fa fa-search"></i></span> 

                                <div id="reloadC"></div>
                                <!-- RESULTADO DE LA BUSQUEDA DE SERIE Y CORRELATIVO -->

                                <div class="resultadoSerie" serieCorrelativo="<?php echo $serieresultado; ?>"><a href="#" class="btn-add-serie" ></a></div>
                                </div>
                                     <!--FIN RESULTADO DE LA BUSQUEDA DE SERIE Y CORRELATIVO -->
                                    </div>
                                    </div>
                                    <!-- ENTRADA RESULTADO MOTIVOS -->
                                    <div class="col-md-5">
                                    <div class="form-group">
                                    <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-id-card"></i></span>
                                    
                                    <select class="form-control" name="motivo" id="motivo">
                                        <?php
                                        $item = "tipo";
                                        $valor = 'C';
                                        $codigo = null;
                                        $motivo = ControladorSunat::ctrMostrarTablaParametrica($item, $valor, $codigo);
                                      foreach ($motivo as $key => $value){
                                                                              
                                        echo '<option value='.$value['codigo'].'>'.$value['descripcion'].'</option>';
                                      }
                                       ?>
                                      </select>
                                      
                                    </div>
                                    </div>
                                    </div>

                                     </div>
                                <!-- ENTRADA CLIENTE -->
                                <div class="row">
                                    <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">Cliente:</legend>
                                  
                                    <div class="col-md-3">
                                    <div class="form-group">
                                   <div class="input-group">
                                     <!-- ID CLIENTE -->                                   
                                     <input type="hidden" name="idCliente" id="idCliente">
                                     
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
                                    <div id="reloadC"></div>
                                    <div class="form-group">
                                    <div class="input-group">
                                    
                                    <input type="text" class="form-control" id="docIdentidad" name="docIdentidad" placeholder="Ingrese número de documento...">

                                    <span class="input-group-addon"><i class="fas fa-id-card"></i></span>
                                    
                                    <div id="reloadC"></div>
                                    <div class="resultadoCliente" idCliente=""><a href="#" class="btn-add" ></a></div>
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
                                  <div class="row nuevoProducto">

                                    <div class="flex">
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
                                        <div class="contenedor-tipo-descuento">  
                                    <label for="soles" id="sol" class="">S/</label>
                                    <input type="radio" id="soles" class="tipo_desc" name="tipo_desc" value="S/" checked>
                                    <label for="porcen" id="por" class="">%</label>
                                    <input type="radio" id="porcen" class="tipo_desc" name="tipo_desc" value="%">
                                    </div>
                                      </div> 
                                      </div>                             
                                    <div class="form-group">
                                    <div class="input-group">

                                    <span class="input-group-addon"><i class="fas fa-money-bill-wave"></i></span>
                                    <input type="number" class="form-control" min="0" placeholder="0.00" id="descuentoGlobalP"" name="descuentoGlobalP" value="0.00"" placeholder="Ingrese descuento..." style="display: none">
                                    
                                    <input type="number" class="form-control" min="0" placeholder="0.00" id="descuentoGlobal"" name="descuentoGlobal" value="0"" placeholder="Ingrese descuento...">

                                    </div>
                                    </div>
                                  </td>
                                 </tr>
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
                                  <tr class="op-subt">
                                    <td>SubTotal</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-descuento-item">
                                    <td>Descuento pot item (-)</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-gravadas">
                                    <td>Op.Gravadas</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-exoneradas">
                                    <td>Op.Exoneradas</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-inafectas">
                                    <td>Op.Inafectas</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-gratuitas">
                                    <td>Op.gratuitas</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-descuento">
                                    <td>Descuento (-)</td><td>0</td>
                                    </tr>
                                    <tr class="op-igv">
                                    <td>IGV(18%)</td><td>0.00</td>
                                    </tr>

                                    <tr class="op-total">
                                    <td>Total</td><td>0.00</td>
                                    </tr>

                                  
                                    </tbody>
                                  </table>                          
                                  </div>
                                  <!-- // FIN ENTRADA DE REMUMMEN TOTALES  -->
                                  </div>
                                  </div>   
                                 </div>
                                   
                                    <hr>

                                <!-- MÉTODO DE PAGO -->
                                <!-- <div class="row">
                                  <div class="col-xs-6">
                                  <div class="input-group">
                                    <select class="form-control rounded" id="nuevoMetodoPago" name="nuevoMetodoPago">
                                        <option value="">Seleccione método de pago</option>
                                        <option value="">Efectivo</option>
                                        <option value="">Tarjeta Crédito</option>
                                        <option value="">Tarjeta Débito</option>
                                    </select>
                                </div>
                                  </div> -->
<!-- 
                                  <div class="col-xs-6">
                                  <div class="input-group">
                                  <input type="text" class="form-control " id="nuevoCodigoTransaccion" name="nuevoCodigoTransaccion" placeholder="Código transacción">
                                  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                </div>
                                  </div> -->
                               
                                <!-- </div> -->
                               

                                </div>
                            
                            </div>

                            <div class="box-footer contenedor-btns-carrito">
                                <button type="button" class="btnGuardarNC"><i class="far fa-save"></i></button>
                                                               
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






            <!-- MODAL AGREGAR CLIENTE-->
  <!-- Modal -->
  <div id="modalAgregarCliente" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoCliente" id="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DNI -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-address-card""></i></span> 

                <input type="number" maxlength="8" class="form-control input-lg" name="nuevoDni" id="nuevoDni" placeholder="Ingresar DNI" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL EMAIL -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-at"></i></span> 

                <input type="email"  class="form-control input-lg" name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask='"mask": "(999) 999-999-999"' data-mask required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DIRECCIÓN -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>
            <!-- ENTRADA PARA FECHA NACIMIENTO -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="nuevaFechaNacimiento" id="nuevaFechaNacimiento" placeholder="Ingresar fecha naciminento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>

           

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

        <?php

          //  $crearCliente = new ControladorClientes();
          //  $crearCliente -> ctrCrearCliente();

        ?>

      </form>


</div>
  </div>
</div>



<!-- Modal AGGREGAR PRODUCTOS -->
<div class="modal fade bd-example-modal-lg" id="modalProductosVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header bg-info">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cart-plus"></i> Productos y servicios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body">
      <div class="col-12">
              <!-- SE INCLUYE LA TABLA PRODUCTOS PARA EL CARRITO -->

              <?php 
                    
                    include_once "table-productos.php";
                    
                ?>
           </div>
           
           <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
      </div>
    
    </div>
  </div>
</div>
      <!-- FIN MODAL            -->
      <div id="reloadFull" style="display: none;"></div>