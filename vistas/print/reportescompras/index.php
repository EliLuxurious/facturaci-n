<?php 
session_start();
require_once "../../../pdf/html2pdf.class.php";

//clases de acceso a datos
require_once("../../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorProveedores;
use Controladores\ControladorProductos;
use Controladores\ControladorReportes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;

// require_once "../../Controladores/cantidad_en_letras.php";
if(isset($_POST['fechaInicial'])){
$tipocomp = $_POST['tipocomp'];
$fechaini = $_POST["fechaInicial"];
$fechaini2 = str_replace('/', '-', $fechaini);
$fechaInicial = date('Y-m-d', strtotime($fechaini2));
$fechafin = $_POST["fechaFinal"];
$fechafin2 = str_replace('/', '-', $fechafin);
$fechaFinal = date('Y-m-d', strtotime($fechafin2));

    $tabla = 'compra';



$resultado = ControladorReportes::ctrReporteComprasPDF($tabla, $fechaInicial, $fechaFinal, $tipocomp);
// var_dump($resultado);
$emisor = ControladorEmpresa::ctrEmisor();

ob_start();

    require_once("report.php");

    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  

$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
header('Content-type: application/pdf');

$carpeta = dirname(__FILE__).'/../../img/usuarios/'.$_SESSION['usuario'].'/pdf';
$files = glob(dirname(__FILE__).'/../../img/usuarios/'.$_SESSION['usuario'].'/pdf/*'); //obtenemos todos los nombres de los ficheros
foreach($files as $file){
    if(is_file($file))
    unlink($file); //elimino el fichero
}

if (!file_exists($carpeta)) {
    mkdir($carpeta, 0755, true);
}
$nombrexml = 'reporte';

$doc = $html2pdf->output(dirname(__FILE__).'/../../img/usuarios/'.$_SESSION['usuario'].'/pdf/'.$nombrexml.'.pdf', 'F');

$rand = rand(22,99999);
echo'<iframe 
title="PDF" 
src="vistas/print/viewpdf/public/pdfjs-2.5.207-es5-dist/web/viewer.html?file=../../../../../img/usuarios/'.$_SESSION['usuario'].'/pdf/'.$nombrexml.'.pdf?n='.$rand.'"
width="100%"
height="650px">
  
</iframe>';
}
?>