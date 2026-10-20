<?php 
session_start();
require_once "../../pdf/html2pdf.class.php";
require_once "../../Controladores/cantidad_en_letras.php";
//clases de acceso a datos
require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorNotaCredito;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;

if(!empty($_REQUEST['a4'])){
    $tipoPrint = $_REQUEST['a4'];
   }
   if(!empty($_REQUEST['tk'])){
    $tipoPrint = $_REQUEST['tk'];
   }
   
$item = "id";
$valor = $_REQUEST["idCo"];
$nota = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);

$item = "id";
$valor = $nota['codcliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$emisor= ControladorEmpresa::ctrEmisor();

$item = "idnc";
$valor = $nota['id'];
$detalle = ControladorNotaCredito::ctrDetallesNotaCreditoProductos($item, $valor);



$valor = $nota['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO
$item = "tipo";
$valor = "C";
$codigo = $nota['codmotivo'];
$motivoNota = ControladorSunat::ctrMostrarTablaParametrica($item, $valor, $codigo);


ob_start();
if($tipoPrint == 'A4'){
    require_once("diseno-nota-credito.php");
    $nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  
}
if($tipoPrint == 'TK'){
require_once("diseno-notas-ticket.php");
 $nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
$html = ob_get_clean();
$html2pdf = new Html2Pdf('P', array(77.5, 300), 'fr', true, 'UTF-8', 0);
}
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