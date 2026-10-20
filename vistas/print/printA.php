<?php 
session_start();
require_once "../../pdf/html2pdf.class.php";
require_once "../../Controladores/cantidad_en_letras.php";
ob_start();

    require_once("invoiceprueba.php");
    //$nombrexml = $ruc . '-' . 1 . '-' . $serie . '-' . $correlativo;
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

$doc = $html2pdf->output(dirname(__FILE__).'/../img/usuarios/'.$_SESSION['usuario'].'/pdf/comprobante.pdf', 'F');

 echo '<embed src="vistas/img/usuarios/'.$_SESSION['usuario'].'/pdf/comprobante.pdf"  type="application/pdf" width="100%" height="600px" />';
?>
