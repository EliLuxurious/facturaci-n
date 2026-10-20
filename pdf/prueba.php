<?php 
require __DIR__.'/html2pdf.class.php';

ob_start();
require_once("factura.php");

$html = ob_get_clean();
$html2pdf = new Html2Pdf('P', array(210,290), 'fr', true, 'UTF-8', 0);
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
$html2pdf->output('FACTURA.pdf', 'I');
?>