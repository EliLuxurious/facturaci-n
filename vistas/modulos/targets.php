<?php
require_once("../../vendor/autoload.php");
use Controladores\ControladorReportes;
// widgets-----------
$moneda = 'S/ ';
    $fechaini = $_REQUEST['fechaini'];
    $fechafin = $_REQUEST['fechafin'];
    $fechai = str_replace('/', '-', $fechaini);
    $fechaInicial = date('Y-m-d', strtotime($fechai));

    $fechaf = str_replace('/', '-', $fechafin);
    $fechaFinal = date('Y-m-d', strtotime($fechaf));
$tabla = 'venta'; 
$tipoc = '01';      
 $facturas = ControladorReportes::ctrSumaFacturas($tabla, $tipoc, $fechaInicial, $fechaFinal);
  @$totalf = $moneda. number_format($facturas['total'],2);

     

$tabla = 'venta'; 
$tipoc = '03';     
 $boletas = ControladorReportes::ctrSumaFacturas($tabla, $tipoc, $fechaInicial, $fechaFinal);
  @$totalb = $moneda. number_format($boletas['total'],2);

$tabla = 'venta'; 
$tipoc = '02';     
 $notaventas = ControladorReportes::ctrSumaFacturas($tabla, $tipoc, $fechaInicial, $fechaFinal);
  @$totalnv = $moneda. number_format($notaventas['total'],2);


$tabla = 'nota_credito'; 
$tipoc = '07';      
 $notac= ControladorReportes::ctrSumaNotas($tabla, $tipoc, $fechaInicial, $fechaFinal);
  @$totalnc = $moneda. number_format($notac['total'],2);
  

$tabla = 'nota_debito'; 
$tipoc = '08';      
 $notad= ControladorReportes::ctrSumaNotas($tabla, $tipoc, $fechaInicial, $fechaFinal);
  @$totalnd = $moneda. number_format($notad['total'],2);
  
  $sub_total = ($boletas['total']+$facturas['total']+$notad['total']+$notaventas['total']);
  $totalneto = $sub_total - $notac['total'];
  $totalneto = number_format($totalneto,2);
// fin widgets ---------------
?>
<div class="contenedor-widget" style="margin-top:15px;">

<div class="col-md-3 col-sm-6 col-xs-12">

<div class="info-box" >
<span class="info-box-icon bg-fa"><i class="fass fas-money-bill"></i></span>

<div class="info-box-content">
<span class="info-box-text">FACTURAS</span>
<span class="info-box-number t-f"><?php echo $totalf?></span>
</div>
<!-- /.info-box-content -->
</div>
<!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12">
<div class="info-box">
<span class="info-box-icon bg-bo"><i class="fass fas-money-bill"></i></span>

<div class="info-box-content">
<span class="info-box-text">BOLETAS</span>
<span class="info-box-number t-b"><?php echo $totalb?></span>
</div>
<!-- /.info-box-content -->
</div>
<!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-nv"><i class="fass fas-money-bill"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">NOTAS DE VENTA</span>
        <span class="info-box-number t-nv"><?php echo $totalnv ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12" >
<div class="info-box" >
<span class="info-box-icon bg-nc"><i class="fass fas-money-bill"></i></span>

<div class="info-box-content">
<span class="info-box-text">NOTAS DE CRÉDITO</span>
<span class="info-box-number t-nc"><?php echo $totalnc?></span>
</div>
<!-- /.info-box-content -->
</div>
<!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12" >
<div class="info-box" >
<span class="info-box-icon bg-nd"><i class="fass fas-money-bill"></i></span>

<div class="info-box-content">
<span class="info-box-text">NOTAS DE DÉBITO</span>
<span class="info-box-number t-nd"><?php echo $totalnd?></span>
</div>
<!-- /.info-box-content -->
</div>
<!-- /.info-box -->
</div>
<!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12" >
<div class="info-box" >
<span class="info-box-icon bg-tn"><i class="fass fas-money-bill"></i></span>

<div class="info-box-content">
<span class="info-box-text">TOTAL NETO</span>
<span class="info-box-number t-neto"><?php echo $totalneto ?></span>
</div>
<!-- /.info-box-content -->
</div>
<!-- /.info-box -->
</div>

</div>       