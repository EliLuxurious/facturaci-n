<?php
session_start();
require_once "../vendor/autoload.php";
use Controladores\ControladorEmpresa;
class AjaxRedondeos{

 public $precio_unitario;
 public $tipo_afectacion;   
public function ajaxRedondeosPrecios(){
   $tipo_afectacion = $this->tipo_afectacion;
   $precio_unitario = $this->precio_unitario;
   $emisorigv = new ControladorEmpresa();
   $emisorigv->ctrEmisorIgv();
   if($tipo_afectacion == 10){
     
       $valor_unitario = $precio_unitario / $emisorigv->igv_uno;
       $precio_compra = ($precio_unitario * 70)/100;
       $igv_producto = $precio_unitario - $valor_unitario;


   }
   if($tipo_afectacion == 20 || $tipo_afectacion == 30){
    $igv_producto = 0;
    $valor_unitario = $precio_unitario - $igv_producto;
    $precio_compra = ($precio_unitario * 70)/100;
   }

   $datos = array(
                "valor_unitario" => round($valor_unitario,2),
                "igv_producto" => round($igv_producto,2),
                "precio_compra" => round($precio_compra,2),
   );
   echo json_encode($datos);
}
}
if(isset($_POST['precio_unitario']))
$objRedondeos = new AjaxRedondeos();
$objRedondeos->precio_unitario = $_POST['precio_unitario'];
$objRedondeos->tipo_afectacion = $_POST['tipo_afectacion'];
$objRedondeos->ajaxRedondeosPrecios();








































