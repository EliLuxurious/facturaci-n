<?php

class AjaxReportes{
    
   public function ajaxDescargaReporteVentasExcel(){
      $fechaini = $_POST["fechaInicial"];
      $fechaini2 = str_replace('/', '-', $fechaini);
      $fechaInicial = date('Y-m-d', strtotime($fechaini2));
      $fechafin = $_POST["fechaFinal"];
      $fechafin2 = str_replace('/', '-', $fechafin);
      $fechaFinal = date('Y-m-d', strtotime($fechafin2));
    echo '
    <a class="btn btn-success" href="vistas/modulos/descarga_reporte_ventas.php?reporte=reporte&fechainicial='.$fechaInicial.'&fechafinal='.$fechaFinal.'"><i class="far fa-file-excel fa-lg"></i> REPORTE EXCEL</a>';

   }
 

   public function ajaxDescargaReporteComprasExcel(){
      $fechaini = $_POST["fechaInicial"];
      $fechaini2 = str_replace('/', '-', $fechaini);
      $fechaInicial = date('Y-m-d', strtotime($fechaini2));
      $fechafin = $_POST["fechaFinal"];
      $fechafin2 = str_replace('/', '-', $fechafin);
      $fechaFinal = date('Y-m-d', strtotime($fechafin2));
    echo '
    <a class="btn btn-success" href="vistas/modulos/descarga_reporte_compras.php?reporte=reporte&fechainicial='.$fechaInicial.'&fechafinal='.$fechaFinal.'"><i class="far fa-file-excel fa-lg"></i> REPORTE EXCEL</a>';

   }
 
}
if(isset($_POST['excelVentas'])){
$objDescarcar = new AjaxReportes();
$objDescarcar->ajaxDescargaReporteVentasExcel();
}
if(isset($_POST['excelCompras'])){
$objDescarcarCompras = new AjaxReportes();
$objDescarcarCompras->ajaxDescargaReporteComprasExcel();
}