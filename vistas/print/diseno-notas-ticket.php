<?php
//Consultar los datos necesarios para mostrar en el PDF - FIN
if($nota['codmoneda'] == 'PEN'){
    $tipoMoneda = 'S/ ';
    $nombreMoneda = 'SOLES';
}else {
    $tipoMoneda = '$USD ';
    $nombreMoneda = 'DÓLARES AMERICANOS';
}

    if($nota['tipocomp_ref'] == '01'){
        $rucdni = $cliente['ruc'];
        $razons_nombre = $cliente['razon_social'];
        $tipodoccliente = 6;
        $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'ELECTRÓNICA';
    }
     if($nota['tipocomp_ref'] == '03'){
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;
        $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'ELECTRÓNICA';
    }
     if($nota['tipocomp_ref'] == '02'){
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;
        $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'DE VENTA';
    }
   ?>
<style>
.logo{
    text-align: center;
}
.logo img{
    width: 40mm;
}
.empresa{
    text-align: center;
    // background-color: red;
     
}
.empresa h3{
font-size: 14px;
margin:0;
padding: 0;
color: #3d4b43;
}
.empresa h3 span{
font-size: 11px;
font-weight: normal;
}
.numero-ruc{
    top: 30mm;
    text-align: center;
    //background-color: green;
}
.numero-ruc h3{
font-size: 14px;
margin: 0.7mm;
padding: 0;
color: #3d4b43;
}

.tabledesc{
    width: 77.5mm;
   // background-color: green;
}
.tabledesc .border{
    margin: 0mm;
    padding: 0mm;
    border-bottom: 0.1mm  dotted #3d4b43;
}
.tabledesc tr{
    width: 77.5mm;
}
.tabledesc tr th{
   font-size: 14px;
   padding: 0.2mm;
   margin: 1mm;
   padding-bottom:0mm;
   color: #3d4b43;
   
 
}
.tabledesc tr td{
   font-size: 12px;
   padding: 0.2mm;
   margin: 0;
  
}
.conte-detalles{
    margin-top: 1mm;
    border-bottom: 0.1mm  dotted #3d4b43;
}
.table-totales{
    width: 100%;   
    margin-top: 1mm; 
    border-bottom: 0.1mm  dotted #3d4b43;
}
.table-totales tr td{
    padding: 0.4mm;
    margin: 0.4mm;
    font-size: 13px;
    padding-bottom: 1mm
}
.table-cliente{
   width: 100%;
}
.table-cliente tr td{
    font-size: 12px;
   padding: 0.2mm;
   margin: 0;
}
.bar-code {
    margin-top: 1.5mm;
    text-align: center;
}
.en-letras {
    margin-top: 1mm;
    font-size: 9px;
}
.en-letras span {
    margin-top: 1mm;
    font-size: 9px;
}
.impresa{
    text-align: center;
    margin-top: 1.5mm;
    font-size: 9px; 
}
</style>
   
   <page backtop="1.5mm" backbottom="1.5mm" backleft="1.5mm" backright="1.5mm"> 
    <page_header> 
         
     
    </page_header> 
    <page_footer> 
         
    </page_footer>
    <?php if($emisor['logo'] == ''){ 

}else
   echo '<div class="logo">
    <img  src="'.dirname(__FILE__).'/../img/logo/'.$emisor['logo'].'" alt="">
    </div>';   
?>
 <div class="empresa">
<h3>
<?php
echo $emisor['razon_social'];
?>
<br>
<span>
<?php
echo $emisor['direccion'];
?>
</span>
<br>
<span>Telf. <?php echo $emisor['telefono']; ?></span>
</h3>

 </div>
 <div class="numero-ruc">
 <h3 class=""><?php echo $emisor['ruc'];?></h3>
 <h3><?php echo $nombre_comprobante; ?></h3>
 <h3><?php echo $nota['serie'] . ' - ' .str_pad( $nota['correlativo'],6,"0",STR_PAD_LEFT); ?></h3>
 </div>
 <div class="conte-cliente">
 <div style="width:100%; ">
  <?php
  if($nota['tipocomp_ref'] == "01"){
      $nombreComprobante = "FACTURA";
  }
  if($nota['tipocomp_ref'] == "03"){
      $nombreComprobante = "BOLETA";
  }
  echo 'Motivo de emisión: '.$motivoNota['codigo'].' - '.$motivoNota['descripcion'].'<br>';
  echo 'Documento relacionado: '.$nombreComprobante.' '.$nota['serie_ref'].'-'.str_pad($nota['correlativo_ref'], 6, '0', STR_PAD_LEFT);
  
  ?>
</div> 
 <table class="table-cliente">
 <tr>
 
 <td style="width:29mm; text-align:right;">Fecha de Emisión: </td>
 <td style="width:42mm; text-align:left;"><?php 
             echo  date("d-m-Y", strtotime($nota['fecha_emision']));
             ?></td>
 </tr>

 </table>

 <table class="table-cliente">
 <tr>
 <td style="width:15mm; text-align:left;">Cliente: </td>
 <td style="width:56mm; text-align:left;"><?php echo $razons_nombre ?></td>
 </tr>
 <tr>
 <td style="width:15mm; text-align:left;">RUC/DNI: </td>
 <td style="width:56mm; text-align:left;"><?php echo $rucdni ?></td>
 </tr>
 <tr>
 <td style="width:15mm; text-align:left;">Direc: </td>
 <td style="width:56mm; text-align:left;"><?php echo $cliente['direccion'] ?></td>
 </tr>
 <tr>
 <td style="width:15mm; text-align:left;">Tipo Moneda: </td>
 <td style="width:56mm; text-align:left;"><?php echo $nombreMoneda ?></td>
 </tr>

 </table>
 
 </div>
 <div class="conte-detalles">
 <table class="tabledesc">
<thead class="header">

 <tr class="tr-head">
 <th  style="text-align:center; width:10mm;">Cant.</th>
 <th  style="text-align:center; width:30mm;">Descripción</th>
 <th  style="text-align:center; width:13mm;">Precio</th>
 <th  style="text-align:center; width:13mm;">Importe</th>
 </tr>
 <tr><th colspan="4" class="border"></th></tr>

 </thead>
 <tbody>
 <?php 
  $descuentosItems = 0.00;
 foreach($detalle as $key => $fila){ 
          $importe_total = ($fila['valor_unitario'] > 0) ? $fila['importe_total'] : number_format(0.00,2);           
     ?>
 <tr> 
 <td style="width:10mm; text-align:center;"><?php echo   $fila['cantidad']; ?></td>
 <td style="width:30mm; text-align:center;"><?php echo   $fila['descripcion']; ?></td>
 <td style="width:14mm;"><?php echo  $tipoMoneda.' '.$fila['valor_unitario']; ?></td>
 <td style="width:14mm;"><?php echo   $tipoMoneda.' '.$importe_total; ?></td>
 </tr>
 
 <?php
             $descuentosItems += $fila['descuento'];  
            }
                   $descuentosItems;  
    
                   $descuentos = round($nota['descuento'] + $descuentosItems,2);
             ?>

 </tbody>
 

 </table>
</div>
 <div class="conte-totales">
 <table class="table-totales">
 <tr>
 <td style="width:49mm; text-align:right;">Gravadas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$nota['op_gravadas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Exoneradas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$nota['op_exoneradas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Inafectas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$nota['op_inafectas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Gratuitas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$nota['op_gratuitas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Descuento Total: </td>
 <td style="width:21mm; text-align:left"><?php echo  $tipoMoneda.' '.number_format($descuentos,2);?></td>

 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">IGV(18%): </td>
 <td style="width:21mm; text-align:left"><?php echo  $tipoMoneda.' '.$nota['igv'];?></td>

 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Total: </td>
 <td style="width:21mm; text-align:left"><?php echo  $tipoMoneda.' '.$nota['total'];?></td>

 </tr>
 </table>
 </div>
 <div class="en-letras">
 <span>   <?php 
       
       echo 'SON: '. CantidadEnLetra($nota['total'], $nombreMoneda);
       
       ?></span>
 </div>


 <div class="bar-code">
 <?php
$ruc = $emisor['ruc'];
$tipo_documento = $nota['tipocomp'];
$serie = $nota['serie'];
$correlativo = $nota['correlativo'];
$igv = $nota['igv'];
$total = $nota['total'];
$fecha = $nota['fecha_emision'];
$tipodoccliente = $tipodoccliente;
$nro_doc_cliente = $rucdni;

?>
 <qrcode class="barcode" value="<?php echo $texto_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|' . $correlativo . '|' . $igv . '|' . $total . '|' . $fecha . '|' . $tipodoccliente . '|' . $nro_doc_cliente; ?>" style="width: 28mm; background-color: white; color: #3c3c3c; border: none; padding:none"></qrcode>
 </div>

 <div class="impresa">
 Representación impresa de la Factura Electrónica.
 <br>
 Consulte su documento en: https://www.sunat.gob.pe
 </div>
 </page>