<?php 
session_start();
require_once (dirname (__FILE__) ."/../../../pdf/html2pdf.class.php");
require_once (dirname (__FILE__) ."/../../../Controladores/cantidad_en_letras.php");
//clases de acceso a datos
require_once(dirname (__FILE__) ."/../../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorGuiaRemision;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;





$emisor= ControladorEmpresa::ctrEmisor();
$tabla = "guia";
$item = "id";
$valor = $_REQUEST["idCo"];
$guia = ControladorGuiaRemision::ctrMostrar($tabla, $item, $valor);

$tabla = "motivo_traslado";
$item = "codigo";
$valor = $guia["cod_traslado"];
$motivo_traslado = ControladorGuiaRemision::ctrMostrar($tabla, $item, $valor);

$tabla = "modalidad_transporte";
$item = "codigo";
$valor = $guia["modTraslado"];
$modalidad_traslado = ControladorGuiaRemision::ctrMostrar($tabla, $item, $valor);

$item = 'id';
$valor = $guia["ubigeoPartida"];
$ubigeoPartida = ControladorGuiaRemision::ctrMostrarUbigeoSolo($item, $valor);

$item = 'id';
$valor = $guia["ubigeoLlegada"];
$ubigeoLlegada = ControladorGuiaRemision::ctrMostrarUbigeoSolo($item, $valor);

$item = "id";
$valor = $guia['id_cliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$item = "id_guia";
$valor = $guia['id'];
$detalle = ControladorGuiaRemision::ctrMostrarDetallesProductosGuia($item, $valor);

//Consultar los datos necesarios para mostrar en el PDF - INICIO
$item = 'id';
$valor = $_SESSION['id'];
$vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

$ruc = $emisor['ruc'];
$serie = $guia['serie'];
$correlativo = $guia['correlativo'];


ob_start();
// if($tipoPrint == 'A4'){
    require_once("../guiaA4.php");
    $nombrexml = $ruc . '-' . '09' . '-' . $serie . '-' . $correlativo;
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  
// }
// if($tipoPrint == 'TK'){
// require_once("../guia-ticket.php");
//  $nombrexml = $ruc . '-' . '09' . '-' . $serie . '-' . $correlativo;
// $html = ob_get_clean();
// $html2pdf = new Html2Pdf('P', array(77.5, 300), 'fr', true, 'UTF-8', 0);
// }
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
$html2pdf->output($nombrexml.'.pdf', 'I');
?>