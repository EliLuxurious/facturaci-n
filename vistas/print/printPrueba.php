<?php 
session_start();
require_once "../../pdf/html2pdf.class.php";

//clases de acceso a datos
require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;

require_once (dirname (__FILE__) ."/../../Controladores/cantidad_en_letras.php");


$item = "id";
$valor = $_POST["idcompro"];

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
//Consultar los datos necesarios para mostrar en el PDF - INICIO
if($venta['codmoneda'] == 'PEN'){
    $nombreMoneda = "SOLES";
}else{
      $nombreMoneda = "DÓLARES";
}
$cantidad_en_letras = CantidadEnLetra($venta['total'], $nombreMoneda);

$ip_add = $_SERVER['REMOTE_ADDR'];


$todos = array(
    'detalles' => $detalle,
    "cliente" => $cliente,
    "venta" => $venta,
    "emisor" => $emisor,
    "comprobante" => $tipo_comprobante,
    "mediodepago" => $metodo_pago,
    "cantidadLetras" => $cantidad_en_letras,
    "ip" => $ip_add,
);

$fields = http_build_query($todos);
var_dump($todos);
$curl = curl_init();
curl_setopt_array($curl, array(
   CURLOPT_URL => 'http://printbmm.ddns.net/impresiontermica/print/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS  => $fields,
  CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_CAINFO => dirname(__FILE__)."/../../api/cacert.pem" //Comentar si sube a un hosting 
     //para ejecutar los procesos de forma local en windows
    //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
));

 $response = curl_exec($curl);

curl_close($curl);

if (!$response) {
     var_dump( curl_error($curl) );  
} 
else { 
    var_dump(json_decode($response)); 
}
$empresa = json_decode($response);
var_dump($empresa);




?>
