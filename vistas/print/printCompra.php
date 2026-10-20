<?php 
session_start();
require_once "../../pdf/html2pdf.class.php";

//clases de acceso a datos
require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorProveedores;
use Controladores\ControladorCompras;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;

require_once "../../Controladores/cantidad_en_letras.php";

$item = "id";
$valor = $_REQUEST["idCompra"];

$compra = ControladorCompras::ctrMostrarCompras($item, $valor);

$item = "id";
$valor = $compra['codproveedor'];
$proveedor = ControladorProveedores::ctrMostrarProveedores($item, $valor);

$emisor= ControladorEmpresa::ctrEmisor();

$item = "idcompra";
$valor = $compra['id'];
$detalle = ControladorCompras::ctrMostrarDetallesCompras($item, $valor);

$item = 'id';
$valor = $_SESSION['id'];
$vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);



$valor = $compra['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);

$item = "codigo";
$valor = $compra['metodopago'];
$metodo_pago = ControladorSunat::ctrMostrarMetodoPago($item, $valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO




ob_start();

    require_once("invoiceCompraA4.php");
    $nombrexml = $proveedor['ruc'] . '-' . 1 . '-' . $compra['serie'] . '-' . $compra['correlativo'];
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  

$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
header('Content-type: application/pdf');

$carpeta = dirname(__FILE__).'/../img/usuarios/'.$_SESSION['usuario'].'/pdf';
$files = glob(dirname(__FILE__).'/../img/usuarios/'.$_SESSION['usuario'].'/pdf/*'); //obtenemos todos los nombres de los ficheros
foreach($files as $file){
    if(is_file($file))
    unlink($file); //elimino el fichero
}

if (!file_exists($carpeta)) {
    mkdir($carpeta, 0755, true);
}

$doc = $html2pdf->output(dirname(__FILE__).'/../img/usuarios/'.$_SESSION['usuario'].'/pdf/'.$nombrexml.'.pdf', 'F');

$rand = rand(22,99999);
echo'<iframe 
title="PDF" 
src="vistas/print/viewpdf/public/pdfjs-2.5.207-es5-dist/web/viewer.html?file=../../../../../img/usuarios/'.$_SESSION['usuario'].'/pdf/'.$nombrexml.'.pdf?n='.$rand.'"
width="100%"
height="650px">
  
</iframe>';
?>