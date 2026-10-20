<?php
 use Controladores\ControladorCotizaciones;
 ?>
<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
  <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
     <button class=""><i class="fas fa-file-invoice"></i> Cotizaciones</button>
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
              <h3 class="box-title">Administración de Cotizaciones</h3>
              
              <!-- DATARANGEPICKER  -->
              <button type="button" class="btn btn-primary pull-right" id="daterange-btn">
              <span>
              <i class="fa fa-calendar"></i> Rango de fecha
              </span>
              <i class="fa fa-caret-down"></i>
              </button>
             <input type="hidden" id="fechaInicial" name="fechaInicial" value="">
             <input type="hidden" id="fechaFinal" name="fechaFinal" value="">

             
            </div>
            <!-- /.box-header -->
            <!-- <div class="box-body table-user">     
            <div class="contenedor-estados-sunat">
              <div class="sunat-estado"><label class="estadosunat"></label>Estado SUNAT:</div>
              <div><label class="aceptado"></label>Aceptado</div>
              <div><label class="rechazado"></label>Rechazado</div>
              <div><label class="noenviado"></label>Pendiente de envío</div>
              <div><label class="baja"></label>Comunicación de baja(Anulado)</div>
            </div> -->
            <div class="contenedor-busqueda">

          <div class="input-group-search">

              <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadCotizaciones(1)">
                <option value="5">5</option>
                <option value="10">10</option>               
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>

              <div class="input-search">
              <input type="search" class="search" id="searchCotiza" name="searchCotiza" placeholder="Buscar..." onkeyup="loadCotizaciones(1)">
              <span class="input-group-addo"><i class="fa fa-search"></i></span> 
              </div>
              </div>
              </div>

            <!-- table-bordered table-striped  -->
            <div class="table-responsive">
         <table class="table table-bordered  tablaCotizaciones" width="100%">

          <thead>
        
            <tr>
              <th style="width:10px;">#</th>
              <th>FECHA EMISIÓN</th>
              <th>COTIZACIÓN N°</th>
              <th>CLIENTE</th>              
              <!-- <th>I.G.V.</th> -->
              <th>TOTAL</th>
              <th width="118px">PDF</th>
            
              <th>ENVIAR</th>
              <th>CREAR COMPROBANTE</th>
            </tr>
          </thead>
        <?php

          $listaguias = new ControladorCotizaciones();
          $listaguias->ctrListarCotizaciones();


        ?>

      </table>
    </div>

         </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
            </div>
