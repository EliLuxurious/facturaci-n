<?php 
session_start();
require_once (dirname (__FILE__) ."/../../../pdf/html2pdf.class.php");
require_once (dirname (__FILE__) ."/../../../Controladores/cantidad_en_letras.php");
//clases de acceso a datos
require_once(dirname (__FILE__) ."/../../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorCotizaciones;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;



$empresa = ControladorEmpresa::ctrEmisor();

$item = "id";
$valor = $_REQUEST["idCo"];

$venta = ControladorCotizaciones::ctrMostrarCotizacion($item, $valor);

$item = 'idenvio';
$valor = $venta['idbaja'];
$ticket = ControladorEnvioSunat::ctrMostrarBaja($item, $valor);

$item = "id";
$valor = $venta['codcliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$emisor= ControladorEmpresa::ctrEmisor();

$item = "idventa";
$valor = $venta['id'];
$detalle = ControladorCotizaciones::ctrMostrarDetallesProductos($item, $valor);

$item = "id_venta";
$valor = $venta['id'];
$ventaCredito = ControladorCotizaciones::ctrMostrarVentasCredito($item, $valor);

$valor = $venta['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);

$item = "codigo";
$valor = $venta['metodopago'];
$metodo_pago = ControladorSunat::ctrMostrarMetodoPago($item, $valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO
$item = 'id';
$valor = $_SESSION['id'];
$vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);



ob_start();

    require_once("../invoiceCotizacionA4.php");
    $nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  

$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
$html2pdf->output($nombrexml.'.pdf', 'I');
?>