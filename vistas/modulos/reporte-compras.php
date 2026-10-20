<?php
use Controladores\ControladorReportes;

?>
<div class="content-wrapper panel-medio-principal">
<?php 
  if($_SESSION['perfil'] == 'Vendedor'){

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
     <button class=""><i class="fas fa-file-invoice"></i> Reporte de compras</button>
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

                <div class="box-header ">
             
       <div class="contenedor-widget">

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-fa"><i class="fass fas-money-bill"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">FACTURAS</span>
              <span class="info-box-number t-f">0.00</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-bo"><i class="fass fas-money-bill"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">BOLETAS</span>
              <span class="info-box-number t-b">0.00</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
       
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12" >
          <div class="info-box" >
            <span class="info-box-icon bg-nc"><i class="fass fas-money-bill"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">NOTAS DE CRÉDITO</span>
              <span class="info-box-number t-nc">0.00</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12" >
          <div class="info-box" >
            <span class="info-box-icon bg-nd"><i class="fass fas-money-bill"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">NOTAS DE DÉBITO</span>
              <span class="info-box-number t-nd">S/ 0.00</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12" >
          <div class="info-box" >
            <span class="info-box-icon bg-tn"><i class="fass fas-money-bill"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL NETO</span>
              <span class="info-box-number t-neto">S/ 0.00</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        
      </div> 
      <!-- /.row -->
             
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">     

            <div class="contenedor-busqueda">

        <!-- row fechas -->
         <div class="row fechas-reportes">
            <div class="col-md-3">
                <div class="input-group">
              <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
              <input type="text" class="fechareportes" id="fechaInicial" name="fechaInicial" placeholder="Fecha Inicial..." style="width:100%" value="<?php echo date("d/m/Y"); ?>" onchange="loadReportesCompras(1)">
               </div>
           </div>
          <div class="col-md-3">
                <div class="input-group">
              <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
              <input type="text" class="fechareportes" id="fechaFinal" name="fechaFinal" placeholder="Fecha Final..." style="width:100%" value="<?php echo date("d/m/Y"); ?>" onchange="loadReportesCompras(1)">
               </div>
             </div>
          </div>
          <!-- fin row fechas -->
          
             <div class="contenedor-radios">
                <label for="factura" class="btn-radios"><i class="fa fa-file-invoice"></i> Facturas</label>
                <input type="radio" id="factura" class="comp" name="tipocomp" value="01" checked>
                <label for="boleta" class="btn-radios"><i class="fa fa-file-invoice"></i> Boletas</label>
                <input type="radio" id="boleta" class="comp" name="tipocomp" value="03">
                <label for="notac" class="btn-radios"><i class="fa fa-file-invoice"></i> Notas C</label>
                <input type="radio" id="notac" class="comp" name="tipocomp" value="07">
                <label for="notad" class="btn-radios"><i class="fa fa-file-invoice"></i> Notas D</label>
                <input type="radio" id="notad" class="comp" name="tipocomp" value="08">                     
                <label for="cfb" class="btn-radios"><i class="fa fa-file-invoice"></i> Facturas y Boletas</label>
                <input type="radio" id="cfb" class="comp" name="tipocomp" value="00">
               
                    
            </div>
          <div class="form-group contenedor-btn-reportes"> 

              <button class="btn btn-primary pull-right btn-show-envio-reporte"><i class="far fa-paper-plane fa-lg"></i> ENVIAR</button>

               <div class="box-tools pull-right reporte-compras-excel" width="100%">
                        
              <!-- <a class="btn btn-success " href="vistas/modulos/descarga_reporte_compras.php?reporte=reporte"><i class="far fa-file-excel fa-lg"></i> REPORTE EXCEL</a> -->
            </div>

              <button class="btn btn-default pull-right btn-reporte-pdf-compras" data-toggle="modal" data-target="#modalImprimir"><i class="far fa-file-pdf fa-lg" style="color:red;"></i> REPORTE PDF</button>              
             
          </div>
           
          <div class="input-group-search">
       
              <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadReportesCompras(1)">
                <option value="5">5</option>
                <option value="10">10</option>               
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>

              <div class="input-search">
              <input type="search" class="search" id="searchReportes" name="searchReportes" placeholder="Buscar..." onkeyup="loadReportesCompras(1)">
              <span class="input-group-addo"><i class="fa fa-search"></i></span> 
              </div>
              </div>
              </div>

            <!-- table-bordered table-striped  -->
         <div class="table-responsive" style="">
         <table class="table table-bordered dt-responsive tablaVentas tabla-reportes" width="100%">

          <thead>
        
            <tr>
              <th style="width:10px;">#</th>
              <th>FECHA EMISIÓN</th>
              <th>COMPROBANTE</th>
              <th>PROVEEDOR</th>              
              <th>I.G.V.</th>
              <th>SUBTOTAL</th>
              <th>TOTAL</th>
              <th>PDF</th>
              <th>ACCIÓN</th>
            </tr>
          </thead>
        <?php

      $reporteventas = new ControladorReportes();
      $reporteventas->ctrReportesCompras();


        ?>

      </table>
    </div>

         </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
  
<?php } ?>

            </div>



<!-- Modal IMPRIMIR-->
<div class="modal fade bd-example-modal-lg" id="modalImprimir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header bg-info">
        
      </div> -->
      <div class="modal-body">
                       
           <div class="col-12">

           <div class="printerhere" width="100%"></div>

           </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
      <!-- FIN MODAL            -->

