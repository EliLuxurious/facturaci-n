<?php 
session_start();
require_once "../../pdf/html2pdf.class.php";
require_once "../../controladores/cantidad_en_letras.php";
//clases de acceso a datos
require_once("../../vendor/autoload.php");
require_once("mike/vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;




$item = "id";
$valor = 1;

$venta = ControladorVentas::ctrMostrarVentas($item, $valor);

$item = 'idenvio';
$valor = $venta['idbaja'];
$ticket = ControladorEnvioSunat::ctrMostrarBaja($item, $valor);

$item = "id";
$valor = $venta['codcliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$emisor= ControladorEmpresa::ctrEmisor();

$item = "idventa";
$valor = $venta['id'];
$detalle = ControladorVentas::ctrMostrarDetallesProductos($item, $valor);



$valor = $venta['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);

$item = "codigo";
$valor = $venta['metodopago'];
$metodo_pago = ControladorSunat::ctrMostrarMetodoPago($item, $valor);

$item = 'id';
$valor = $_SESSION['id'];
$vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO
if($venta['codmoneda'] == 'PEN'){
    $tipoMoneda = 'S/ ';
}else {
    $tipoMoneda = '$USD ';
}
if($venta['tipocomp'] == '01'){
    $rucdni = $cliente['ruc'];
    $razons_nombre = $cliente['razon_social'];
    $tipodoccliente = 6;
    $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'ELECTRÓNICA';
}
 if($venta['tipocomp'] == '03'){
    $rucdni = $cliente['documento'];
    $razons_nombre = $cliente['nombre'];
    $tipodoccliente = 1;
    $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'DE VENTA ELECTRÓNICA';
}
 if($venta['tipocomp'] == '02'){
    $rucdni = $cliente['documento'];
    $razons_nombre = $cliente['nombre'];
    $tipodoccliente = 1;
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}


$connector = new WindowsPrintConnector("EPSON");
$printer = new Printer($connector);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setTextSize(2,2);
if(PRINTIMAGES) $logo = EscposImage::load('../img/'.$emisor['logo'].'');
$printer->setJustification(Printer::JUSTIFY_CENTER);
if(PRINTIMAGES) $printer->bitImage($logo); //bitImage
$printer -> feed();
$printer -> text($emisor['razon_social']."\n");
$printer -> selectPrintMode();
$printer -> feed();
$printer -> setTextSize(2,1);
$printer -> setEmphasis(true);
$printer -> text("RUC: ".$emisor['ruc']."\n");
$printer -> setEmphasis(false);
$printer -> selectPrintMode();
$printer -> text($emisor['direccion']."\n");
$printer -> text("TEL.: ".$emisor['telefono']."\n");
$printer -> feed();
$printer -> setTextSize(1,1); 
$printer -> selectPrintMode($printer::MODE_EMPHASIZED);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setEmphasis(true);

    $printer -> text($nombre_comprobante.' '.$venta['serie'].'-'.str_pad( $venta['correlativo'],6,"0",STR_PAD_LEFT)."\n");

$printer -> setEmphasis(false);
$printer -> feed();
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> text("FECHA    : ".date("d-m-Y", strtotime($venta['fecha_emision']))."\n");
$printer -> text("CLIENTE  : ".$razons_nombre."\n");
$printer -> text("DNI/RUC  : ".$rucdni."\n");
$printer -> text("DIRECCION: ".$cliente['direccion']."\n");
$printer -> text("PAGO     : ".$metodo_pago['descripcion']."\n");
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setTextSize(1,1);
$printer -> text("------------------------------------------------\n");

  $printer -> setEmphasis(true);
  $printer -> text("CANT  DETALLE          PU    PT\n");
  $printer -> setEmphasis(false);
  $printer -> selectPrintMode($printer::MODE_FONT_A);
    $printer -> setTextSize(1,1);

    $descuentosItems= 0;
  foreach ($detalle as $key => $value) {
      $importe = ($value['valor_unitario'] > 0) ? $value['importe_total']: $value['valor_unitario'];

    $printer -> text("  ".$value['cantidad']."   ".$value['descripcion']."       ".$value['valor_unitario']."   ".$importe."\n");

    $descuentosItems += $value['descuento'];  
  }
  $descuentosItems;  

  $descuentos = round($venta['descuento'] + $descuentosItems,2);
$printer -> text("------------------------------------------------\n");
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
// $printer -> text("Sub Total      : ".$data['venta']['ped_precio_sub']."\n");
$printer -> text("Op. gravadas   : ".$venta['op_gravadas']."\n");
$printer -> text("Op. Exoneradas : ".$venta['op_exoneradas']."\n");
$printer -> text("Op. Inafectas  : ".$venta['op_inafectas']."\n");
$printer -> text("Op. Gratuitas  : ".$venta['op_gratuitas']."\n");
$printer -> text("DESCUENTO (-)  : ".number_format($descuentos,2)."\n");
$printer -> text("IGV            : ".$venta['igv']."\n");
$printer -> setEmphasis(true);
$printer -> text("TOTAL    $tipoMoneda.    ".$venta['total']."\n");
$printer -> setEmphasis(false);
$printer -> feed();
// $printer -> text("PAGO CON          : $tipoMoneda ".$data['venta']['ped_importe']."\n");
// $printer -> text("VUELTO            : $tipoMoneda ".$data['venta']['ped_vuelto']."\n");
$printer -> feed();
$printer -> selectPrintMode();
$printer -> setTextSize(1,1);
$printer -> feed();
$printer -> setJustification(Printer::JUSTIFY_LEFT);

    $printer -> text(" SON :".CantidadEnLetra($venta['total'])."\n");
    // $printer -> text(" RESUMEN :".$data['venta']['ped_hash']."\n");
    $printer -> feed();
    if(PRINTQR and PRINTIMAGES){
 $ruc = $emisor['ruc'];
$tipo_documento = $venta['tipocomp'];
$serie = $venta['serie'];
$correlativo = $venta['correlativo'];
$igv = $venta['igv'];
$total = $venta['total'];
$fecha = $venta['fecha_emision'];
$tipodoccliente = $venta['tipodoc'];
$nro_doc_cliente = $rucdni;

        \PHPQRCode\QRcode::png($ruc . '|' . $tipo_documento . '|' . $serie . '|' . $correlativo . '|' . $igv . '|' . $total . '|' . $fecha . '|' . $tipodoccliente . '|' . $nro_doc_cliente,'images/qr.png','L', 8, 4);
        $qr = EscposImage::load('images/qr.png',false);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->bitImage($qr);
        unlink('images/qr.png');
    }

$printer -> feed();
$printer -> text("ATENDIDO POR: ".$vendedor['id'].'-'.substr($vendedor['perfil'],0,2)."\n");
// $printer -> feed();
// $printer -> text($data['venta']['ped_obs']."\n");
$printer -> feed();
$printer -> text(LEGEND."\n");
$printer -> feed();
$printer -> text(FRASEFOOTER."\n");
$printer -> text("------------------------------------------\n");
$printer->cut();
$printer ->close();