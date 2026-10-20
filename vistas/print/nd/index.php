<?php 
session_start();
require_once (dirname (__FILE__) ."/../../../pdf/html2pdf.class.php");

require_once(dirname (__FILE__) ."/../../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorNotaDebito;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
require_once (dirname (__FILE__) ."/../../../Controladores/cantidad_en_letras.php");

if(!empty($_REQUEST['a4'])){
    $tipoPrint = $_REQUEST['a4'];
   }
   if(!empty($_REQUEST['tk'])){
    $tipoPrint = $_REQUEST['tk'];
   }
 $empresa = ControladorEmpresa::ctrEmisor();
$item = "id";
$valor = $_REQUEST["idCo"];
$nota = ControladorNotaDebito::ctrMostrarNotaDebito($item, $valor);

$item = "id";
$valor = $nota['codcliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$emisor= ControladorEmpresa::ctrEmisor();

$item = "idnd";
$valor = $nota['id'];
$detalle = ControladorNotaDebito::ctrDetallesNotaDebitoProductos($item, $valor);



$valor = $nota['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO
$item = "tipo";
$valor = "D";
$codigo = $nota['codmotivo'];
$motivoNota = ControladorSunat::ctrMostrarTablaParametrica($item, $valor, $codigo);


ob_start();
if($tipoPrint == 'A4'){
    require_once("../diseno-nota-credito.php");
    $nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  
}
if($tipoPrint == 'TK'){
require_once("../diseno-notas-ticket.php");
 $nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
$html = ob_get_clean();
$html2pdf = new Html2Pdf('P', array(77.5, 300), 'fr', true, 'UTF-8', 0);
}
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
$html2pdf->output($nombrexml.'.pdf', 'I');
?>