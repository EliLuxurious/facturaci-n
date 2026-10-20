<?php 
use Controladores\ControladorProductos;

$item = null;
$valor = null;
$categorias =  ControladorProductos::ctrMostrarCategorias($item, $valor);
?>

<div class="row">
<div class="col-lg-12">                        
            <div class="col-md-2">
            <div class="form-group">
                <select class="form-control"  id="selectnum" name="selectnum" onchange="loadProductosV(1)">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            </div>
            <div class="col-md-3">
            <div class="form-group">
              <select class="form-control"   id="categorias" name="categorias" onchange="loadProductosV(1)">
                <option value="">Categoría</option>
                <?php 
                foreach($categorias as $k =>$v){
                  echo '<option value="'.$v['id'].'">'.$v['categoria'].'</option>';
                }
                ?>
                
              </select>
            </div>
            </div>
              <div class="col-md-7">
            <div class="form-group">
      
              <input type="search" class="form-control" id="searchProductoV" name="searchProductoV" placeholder="Buscar producto o servicio..." onkeyup="loadProductosV(1)" style="width:100%">
              
        </div>
        </div>
        </div>
</div>
 
<div class="table-responsive productosCar">
<table class="table table-bordered tablaVentas tabla-add">
                <thead>
    
                                <tr>
                                <th style="width:10px">#</th>
                                <!-- <th>Imagen</th> -->
                                <th>Código</th>
                                <th>Serie</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th class="btn-prod"></th>
                                <th class="btn-prod"></th>
                               
                                </tr>
                                </thead>
                                <?php

                                    $listaProductos = new ControladorProductos();
                                    $listaProductos-> ctrListarProductosVentas();

                                    ?>

                 
    </table>
   </div>