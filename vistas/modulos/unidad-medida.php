<?php 
use Controladores\ControladorProductos;

?>
<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
  <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
  <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px;  border-radius:10px;">

  <div class="col-md-3 hidden-sm hidden-xs">
     <button class=""><i class="fas fa-file-invoice"></i> Unidad de medida</button>
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

                <div class="box-header ">
              <h3 class="box-title">Administración de unidades</h3>
<!-- 
              <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarUsuario"><i class="fas fa-plus-square"></i>Nuevo usuario <i class="fas fa-user-plus"></i>            
            </button> -->
            
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         

            <!-- table-bordered table-striped  -->
            <div class="table-responsive">
                
            <table class="table  dt-responsive tablas unidad-me" width="100%">

          <thead>
            <tr>
              <th style="width:10px;">#</th>
              <th>CÓDIGO</th>
              <th>DESCRIPCIÓN</th>
              <th>ACTIVO</th>
            </tr>
          </thead>

          <tbody>
               
            <?php 
            $item = null;
            $valor = null;
            $unidades = ControladorProductos::ctrMostrarUnidade($item, $valor);
            foreach ($unidades as $k => $v){
            
               echo ' <tr>
                        <td>'.++$k.'</td>
                        <td>'.$v['codigo'].'</td>
                        <td>'.$v['descripcion'].'</td>
                        <td>  
                     
                        <div class="modo-contenedor-unidad">';
                        if($v['activo'] == 's'){
                        echo '<label for="si" id="sie'.$v['id'].'" class="siu unidadsi" idm="'.$v['id'].'" >Sí</label>                       
                        <input type="radio" class="unidadmedida" id="si" name="unidadmedida" value="s" idu="'.$v['id'].'">                        
                        <label for="no" id="noe'.$v['id'].'" class="nou alterno2" idm="'.$v['id'].'" >||</label>
                        <input type="radio" class="unidadmedidan" id="no" name="unidadmedida" value="n" checked="checked" >';
                        }
                        if($v['activo'] == 'n'){
                        echo '<label for="si" id="sie'.$v['id'].'" class="siu alterno2" idm="'.$v['id'].'" >||</label>                       
                        <input type="radio" class="unidadmedida" id="si" name="unidadmedida" value="s" idu="'.$v['id'].'">                        
                        <label for="no" id="noe'.$v['id'].'" class="nou unidadno" idm="'.$v['id'].'" >No</label>
                        <input type="radio" class="unidadmedidan" id="no" name="unidadmedida" value="n" checked="checked" >';
                        }
                        echo '</div>
                        </td>
                    </tr>';
                        
            
            }; 
            ?>           
            
          </tbody>

      </table>
</div>


              

            </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
            </div>




