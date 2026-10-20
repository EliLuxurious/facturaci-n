<?php
use Controladores\ControladorResumenDiario;
?>
<div class="content-wrapper panel-medio-principal" >


<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
  <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
     <button class=""><i class="fas fa-file-invoice"></i> Resúmen diario</button>
</div>
<div class="col-md-9  col-sm-12 btns-dash">
  <a href="crear-factura" class="btn pull-right" style="margin-left:10px"><i class="fas fa-file-invoice"></i> Emitir factura</a>
  <a href="crear-boleta" class="btn pull-right"><i class="fas fa-file-invoice"> </i> Emitir boleta</a>
</div>
</div>
   </div>   
    </section>
</section>

<section class="container-fluid panel-medio" style="margin-button:0px !important;">
          <!-- BOX INI -->
          <div class="box rounded">
          <div class="box-header" style="border: 0px;">
            <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarResumen"><i class="fas fa-plus-square"></i>Crear nuevo resúmen boletas <i class="fas fa-file-invoice fa-lg"></i>           
            </button>
            </div>
            <!-- <div class="box-body">

            </div> -->
          </div>
  </section>        

<!-- <section class="content"> -->

      <section class="container-fluid panel-medio" style="margin-top: 0px !important;">
          <!-- BOX INI -->
          <div class="box rounded">

                <div class="box-header ">
              <h3 class="box-title"><i class="fas fa-file-invoice"></i> Lista de comprobantes</h3>

            
            
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         
          <div class="contenedor-busqueda">

          <div class="input-group-search">
              <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnumR" name="selectnumR" onchange="resumenBoletasDiarios(1)">
                <option value="5">5</option>
                <option value="10">10</option>               
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              <div class="input-search">
              <input type="search" class="search" id="searchResumen" name="searchResumen" placeholder="Buscar..." onkeyup="resumenBoletasDiarios(1)">
              <span class="input-group-addo"><i class="fa fa-search"></i></span> 
              </div>

              </div>
             
          
      </div>
            <!-- table-bordered table-striped  -->
     <div class="table-responsive tabla-resumenes">
         <table class="table table-bordered dt-responsive tablaResumen" width="100%">

          <thead>
            <tr>
              <th style="width:10px;">#</th>
              <th>Fecha envío</th>
              <th>Fecha documentos</th>
              <th>Documentos</th>
              <th>Ticket</th>
              <th>PDF</th>
              <th>XML</th>
              <th>CDR</th>
              <th>SUNAT</th>
            
            </tr>
          </thead>

         <?php 

         $resumenes = ControladorResumenDiario::ctrMostrarResumenes();
        
         ?>

       
      </table>
</div>

              

            </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
            </div>


<!-- MODAL COMPROBANTES PARA EL RESUMEN -->
<div id="modalAgregarResumen" class="modal fade modal-forms" role="dialog">
  
<div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">RESUMEN DIARIO</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">  
        
        <div class="input-group resumen-diario" style="width:100%">

        <input type="hidden" name="ruta_comprobante" id="ruta_comprobante" value="<?php echo  $_GET["ruta"]?>">

        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
        <input type="text" class="fechaResumen" id="fechaResumen" name="fechaResumen" placeholder="Buscar..." style="width:100%" value="<?php echo date("d/m/Y"); ?>">
               
           </div>
    <div class="table-responsive tabla-resumenes" style="margin-top:7px;">
           <table class="table table-bordered">
             <thead>
             <tr>
               <th>Fecha</th>
               <th>Tipo</th>
               <th>Serie</th>
               <th>Correlativo</th>
               <th>Total</th>
             </tr>
            </thead>
            <tbody class="resultado-resumen">
              <?php 
              $fecha = date("Y-m-d");
              $respuesta = ControladorResumenDiario::ctrMostrarComprobantes($fecha);
                echo $respuesta
              ?>

            </tbody>
           
           </table>
      </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>

          <button type="submit" id="btnEnvioResumen" class="btn btn-primary"><i class="fas fa-calendar-plus fa-lg"></i> Crear resúmen de boletas</button>

        </div>

   

    </div>

  </div>

</div>


<!-- MODAL BOLETAS DE VENTA QUE SE HAN MANDADO EN RESUMEN-->
<div id="modalBoletas" class="modal fade modal-forms" role="dialog">
  
<div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title text-resumen">BOLETAS DEL RESÚMEN N°</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

  <div class="modal-body">  
        
<div class="contenedor-busqueda">
<div class="input-group-search">
    <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum2" name="selectnum2" onchange="loadResumenBoleta(1)">
      <option value="5">5</option>
      <option value="10">10</option>               
      <option value="20">20</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>

    <div class="input-search">
    <input type="search" class="search" id="searchBoleta" name="searchBoleta" placeholder="Buscar..." onkeyup="loadResumenBoleta(1)">
    <span class="input-group-addo"><i class="fa fa-search"></i></span> 
  </div>
    </div>
   

</div>
        <div class="table-responsive tabla-resumenes">
           <table class="table  table-bordered">
             <thead>
             <tr>
               <th>Fecha</th>
               <th>Tipo</th>
               <th>Serie</th>
               <th>Correlativo</th>
               <th>Total</th>
               <th>Estado</th>
             </tr>
            </thead>
           <?php 
           
           $resultado = ControladorResumenDiario::ctrMostrarBoletasResumenenes();
           
           ?>
           
           </table>

           </div>


        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="far fa-times-circle fa-lg"></i> Salir</button>
          <input type="hidden" name="idenvio" id="idenvio" value="">
        </div>

   

    </div>

  </div>

</div>

