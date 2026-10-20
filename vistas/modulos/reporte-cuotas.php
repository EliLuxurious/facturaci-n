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
     <button class=""><i class="fas fa-file-invoice"></i> Consultar pagos de cuotas</button>
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

    
        
      </div> 
      <!-- /.row -->
             
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">     

            <div class="contenedor-busqueda-cuotas">     
        
        <div class="col-md-3">
        <div class="form-group">
         <input class="form-control" type="text" name="alumno" id="alumno" value="" placeholder="D.N.I.">      

        </div>
        </div>
        <!-- ENTRADA SEMESTRE  -->
        <div class="col-md-3">
        <div class="form-group">
     
            <select class="form-control" name="semestre" id="semestre">
                                <option value="">SEMESTRE</option>
                                <option value="<?php echo date('Y')?>-I"><?php echo date('Y')?>-I</option>
                                <option value="<?php echo date('Y')?>-II"><?php echo date('Y')?>-II</option>                               
                                <option value="2021-I">2021-I</option>
                                <option value="2021-II">2021-II</option>
                                <option value="2020-I">2020-I</option>
                                <option value="2020-II">2020-II</option>
                                <option value="2019-I">2019-I</option>
                                <option value="2019-II">2019-II</option>
                                <option value="2018-I">2018-I</option>
                                <option value="2018-II">2018-II</option>
                                <option value="2017-I">2017-I</option>
                                <option value="2017-II">2017-II</option>
                                <option value="2016-I">2016-I</option>
                                <option value="2016-II">2016-II</option>
                                <option value="2015-I">2015-I</option>
                                <option value="2015-II">2015-II</option>
                                <option value="2014-I">2014-I</option>
                                <option value="2014-II">2014-II</option>
                                <option value="2013-I">2013-I</option>
                                <option value="2013-II">2013-II</option>
                                <option value="2012-I">2012-I</option>
                                <option value="2012-II">2012-II</option>
                               
                              </select>
        
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
              <th>ALUMNO</th>
              <th>CARRERA</th>
              <th>CICLO</th>
              <th>CUOTA</th>              
              <th>ESTADO</th>
      
            </tr>
          </thead>
        <tbody class="pago-cuotas">
      
            
        </tbody>

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

