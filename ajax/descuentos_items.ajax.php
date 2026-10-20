<?php
session_start();
require_once "../vendor/autoload.php";
use Controladores\ControladorEmpresa;
class ajaxDescuentos{

public  function ajaxDescuentoItem(){
$valor_unitario = $_POST['valor_unitario'];
$precio_unitario = $_POST['precio_unitario'];
$descuento_item = $_POST['descuento_item'];
$cantidad = $_POST['cantidad'];
$tipo_afectacion = $_POST['tipo_afectacion'];

$emisorigv = new ControladorEmpresa();
$emisorigv->ctrEmisorIgv();

if($tipo_afectacion == '10'){
    
$valor_unitario = $precio_unitario / $emisorigv->igv_uno;
$sub_total = $valor_unitario * $cantidad - $descuento_item;

$igv = $sub_total * $emisorigv->igv_dos;


}

if($tipo_afectacion == '11' || $tipo_afectacion == '12' || $tipo_afectacion == '13' || $tipo_afectacion == '14' || $tipo_afectacion == '15' || $tipo_afectacion == '16'){
    
$sub_total = $valor_unitario * $cantidad;
$igv = 0.00;
}

if($tipo_afectacion == '31' || $tipo_afectacion == '32' || $tipo_afectacion == '33' || $tipo_afectacion == '34' || $tipo_afectacion == '35' || $tipo_afectacion == '36'){

$sub_total = $valor_unitario * $cantidad;
$igv = 0.00;

}

if($tipo_afectacion == '20'){
$sub_total = $precio_unitario * $cantidad - $descuento_item;
$igv = 0.00;
$valor_unitario = $precio_unitario;

}
if($tipo_afectacion == '30'){
$sub_total =$precio_unitario * $cantidad - $descuento_item;
$igv = 0.00;

}
if(isset($_POST['modoIcbper'])){
    if($_POST['modoIcbper'] == 's'){
        
        $icbper = round(0.30 * $cantidad,2);
    }else{
        $icbper = '';
    }

}
$total = $sub_total + $igv;
$datos = array(
    "precio_unitario" => round($precio_unitario,2),
    "valor_unitario" => round($valor_unitario,2),
    "subtotal" => round($sub_total,2),
    "igv" => round($igv,2),
    "total" => round($total,2),
    "icbper" => $icbper
);
echo json_encode($datos);
}
}
if(isset($_POST['idProducto'])){
    $objDescuento = new ajaxDescuentos;
    $objDescuento ->ajaxDescuentoItem();
}