<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
     <button class=""><i class="fas fa-file-invoice"></i> Nueva factura</button>
      
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

                            
                            <form role="form" method="post" class="formVenta">
                            <div class="box-body" style="border: 0px; padding-top:0px; "> 

                          <!-- PRIMERA ENTRADA FORM -->
                                <div class="box" style="border: 0px; padding-top:0px;" >

                                <!-- ENTRADA TIPO MONEDA-->
                                <div class="col-md-3 col-xs-6">
                                <div class="input-group-">
                                    <select class="form-control" id="moneda" name="moneda">
                                        <option value="PEN">Soles (S/)</option>
                                        <option value="USD">Dólares Americanos ($)</option>
                                    </select>
                                </div>
                                </div>

                                <!-- ENTRADA SERIE-->
                                <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                 <div class="input-group">
                                 <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                  <input type="text" class="form-control" name="serie" id="serie" value="F001" readonly>                              
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

                                <!-- ENTRADA CLIENTE -->
                                <div class="row">
                                    <legend class="text-bold">Cliente:</legend>

                                    <div class="col-md-3">
                                      <div class="form-group">
                                      <select class="form-control" name="tipoDoc" id="tipoDoc">
                                        <option value="6">R.U.C.</option>
                                        <option value="1">D.N.I.</option>
                                       
                                      </select>
                                      </div>
                                    </div>

                                    <!-- ENTRADA DOCUMENTO -->
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="input-group">
                                    <input type="text" class="form-control" id="docIdentidad" name="docIdentidad" placeholder="Ingrese número de documento...">
                                    <span class="input-group-addon btn buscarRuc"><i class="fa fa-search"></i></span> 
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

                                    <div class="col-lg-12">
                                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalProductosVenta" >Agregar produto</button>

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
                                      <th>Precio</th>
                                      <th>Sub.Total</th>
                                      <th></th>
                                      </tr>
                                    </thead>
                                    <tbody id="itemsP">
                                    
                                    </tbody>
                                  
                                  </table>
                                  </div>
                                  <!-- FIN ENTRADA AGREGAR PRODUCTOS  -->

                                    <!-- //ENTRADA DE REMUMMEN TOTALES  -->                                    
                                  <div class="table-responsive">
                                  <table class="table  tabla-totales">

                                  <thead>
                                  <tr>
                                  <th></th>
                                  <th>RESUMEN</th>
                                  </tr>
                                  </thead>
                                  <tbody class="totales">
                                    <tr class="op-gravadas">
                                    <td>Op.Gravadas</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-exoneradas">
                                    <td>Op.Exoneradas</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-inafectas">
                                    <td>Op.Inafectas</td><td>0.00</td>
                                    </tr>
                                    <tr class="op-descuento">
                                    <td>Descuento</td><td>0.00</td>
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
                                   
                                    <hr>

                                <!-- MÉTODO DE PAGO -->
                                <div class="row">
                                  <div class="col-xs-6">
                                  <div class="input-group">
                                    <select class="form-control rounded" id="nuevoMetodoPago" name="nuevoMetodoPago">
                                        <option value="">Seleccione método de pago</option>
                                        <option value="">Efectivo</option>
                                        <option value="">Tarjeta Crédito</option>
                                        <option value="">Tarjeta Débito</option>
                                    </select>
                                </div>
                                  </div>

                                  <div class="col-xs-6">
                                  <div class="input-group">
                                  <input type="text" class="form-control " id="nuevoCodigoTransaccion" name="nuevoCodigoTransaccion" placeholder="Código transacción">
                                  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                </div>
                                  </div>
                                </div>
                               

                                </div>
                            
                            </div>

                            <div class="box-footer">
                                <button type="button" class="btn btn-primary">Guardar Transaccion</button>
                                 
                                 <!-- BOTÓN PARA ELIMINAR CARRO-->
                                 <button type="button" class="btn btn-danger btnEliminarCarro">Cancelar</button>
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

           $crearCliente = new ControladorClientes();
           $crearCliente -> ctrCrearCliente();

        ?>

      </form>


</div>
  </div>
</div>



<!-- Modal AGGREGAR PRODUCTOS -->
<div class="modal fade bd-example-modal-lg" id="modalProductosVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cart-plus"></i> Productos y servicios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="col-12">
                       
                        <div class="col-12">
                          <div class="table-responsive">
                        <table class="table table-bordered tablaVentas">
                <thead>
                 <tr>
              <th colspan="10">
              <div class="input-group" style="width:100%">
                <select class="selectpicker show-tick" data-style="btn-primary" data-width="70px" id="selectnum" name="selectnum" onchange="loadProductosV(1)">
                <option value="5">5</option>
                <option value="10"">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>

              <input type="search" class="search" id="searchProductoV" name="searchProductoV" placeholder="Buscar..." onkeyup="loadProductosV(1)" style="width:100%">
              <span class="input-group-addon icon-search"><i class="fa fa-search"></i></span> 

              

              </div>
            </th>
          </tr>   
                                <tr>
                                <th style="width:10px">#</th>
                                <th>Imagen</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th></th>
                                </tr>
                                </thead>
                                <?php

                                    $listaProductos = new ControladorProductos();
                                    $listaProductos-> ctrListarProductosVentas();

                                    ?>

                                        <div id="reload"></div>
                            </table>
                            </div>
                        </div>
                    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
      <!-- FIN MODAL            -->
