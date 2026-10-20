<?php 
use Controladores\ControladorProductos;
?>
<div class="contenedor-busqueda">
<div class="input-group-search">
                <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadProductosG(1)">
                <option value="5">5</option>
                <option value="10"">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
        <div class="input-search">
              <input type="search" class="search" id="searchProductoG" name="searchProductoG" placeholder="Buscar..." onkeyup="loadProductosG(1)" style="width:100%">
              <span class="input-group-addo"><i class="fa fa-search"></i></span> 
        </div>
</div>
 </div>
<div class="table-responsive">
<table class="table table-bordered tablaGuia tabla-add-guia">
                <thead>
    
                                <tr>
                                <th style="width:10px">#</th>
                                <!-- <th>Imagen</th> -->
                                <th>Código</th>
                                <th>Serie</th>
                                <th>Descripción</th>
                                <th>Unidad M</th>
                                <th>Cantidad</th>
                                <th class="btn-prod"></th>
                               
                                </tr>
                                </thead>
                                <?php

                                    $listaProductos = new ControladorProductos();
                                    $listaProductos-> ctrListarProductosGuia();

                                    ?>

                 
    </table>
   </div>