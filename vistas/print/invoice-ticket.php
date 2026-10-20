<?php

//Consultar los datos necesarios para mostrar en el PDF - FIN
    if($venta['codmoneda'] == 'PEN'){
        $tipoMoneda = 'S/ ';
        $nombreMoneda = 'SOLES';
    }else {
        $tipoMoneda = '$USD ';
        $nombreMoneda = 'DÓLARES AMERICANOS';
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
        $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'DE VENTA';
    }
   ?>
<style>
.anulado-print{
    position: absolute;
    width: 100%;
    height: 100%;
    font-size: 25px;
    color: red;
    z-index: 1;
    text-align: center;
    font-weight: bold;

   }
   .anulado-print span{
       font-size: 16px;
    color: red;

   }
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
.anulado-print{
    position: absolute;
    top:13%;
    left:2%;
    color: red;
    font-size: 20px;
    text-align: center;
    font-weight: bold
}
.anulado-print span{
    color: red;
}
.v5{
    width: 5%;
}
.v10{
    width: 10%;
}
.v15{
    width: 15%;
}
.v20{
    width: 20%;
}
.v25{
    width: 25%;
}

.v30{
    width: 30%;
}
.v35{
    width: 35%;
}
.v40{
    width: 40%;
}
.v45{
    width: 45%;
}
.v50{
    width: 50%;
}
.v55{
    width: 55%;
}
.v60{
    width: 60%;
}
.v65{
    width: 65%;
}
.v70{
    width: 70%;
}
.v75{
    width: 75%;
}
.v80{
    width: 80%;
}
.v100{
    width: 100%;
}
.tabla-tipo-pago{
    width: 100%;
}
.tabla-tipo-pago td{
   border-bottom: 0.5px solid #333;
   font-size: 12px;    
    
}
</style>
   
   <page backtop="1.5mm" backbottom="1.5mm" backleft="1.5mm" backright="1.5mm"> 
    <page_header> 
         
     
    </page_header> 
    <page_footer> 
         
    </page_footer>

    <?php if($venta['anulado'] == 's'){ ?>
<div class="anulado-print">
ANULADO<br>
<span>
<?php 
echo "Ticket de baja: ".$ticket['ticket'];
?>
</span>

</div>
<?php }?>

<?php if($emisor['logo'] == ''){ 

}else
   echo '<div class="logo">
    <img  src="'.dirname(__FILE__).'/../img/logo/'.$emisor['logo'].'" alt="">
    </div>';   
?>
 <?php if($venta['anulado'] == 's'){ ?>
<div class="anulado-print">
ANULADO<br>
<span>
<?php 
echo "Ticket de baja: ".$ticket['ticket'];
?>
</span>

</div>

<?php }?>

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
 <h3><?php echo $venta['serie'] . ' - ' .str_pad( $venta['correlativo'],6,"0",STR_PAD_LEFT); ?></h3>
 </div>
 <div class="conte-cliente">
 <table class="table-cliente">
 <tr>
 <td style="width:29mm; text-align:right;">Fecha de Emisión: </td>
 <td style="width:42mm; text-align:left;"><?php 
             echo  date("d-m-Y", strtotime($venta['fecha_emision']));
             ?></td>
 </tr>
 <tr>
 <td style="width:29mm; text-align:right;">Medio de pago: </td>
 <td style="width:42mm; text-align:left;"><?php echo $metodo_pago['descripcion']?></td>
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
                   
                   $descuentos = round($venta['descuento'] + $descuentosItems,2);
                   ?>

 </tbody>
 

 </table>
</div>
 <div class="conte-totales">
 
<table class="table-totales">
 <tr>
 <td style="width:49mm; text-align:right;">Gravadas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$venta['op_gravadas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Exoneradas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$venta['op_exoneradas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Inafectas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$venta['op_inafectas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Gratuitas: </td>
 <td style="width:21mm; text-align:left"><?php echo $tipoMoneda.' '.$venta['op_gratuitas'];?></td>
 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Descuento Total (-): </td>
 <td style="width:21mm; text-align:left"><?php echo  $tipoMoneda.number_format($descuentos, 2)?></td>

 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">ICBPER: </td>
 <td style="width:21mm; text-align:left"><?php echo  $tipoMoneda.$venta['icbper'];?></td>

 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">IGV(18%): </td>
 <td style="width:21mm; text-align:left"><?php echo  $tipoMoneda.' '.$venta['igv'];?></td>

 </tr>
 <tr>
 <td style="width:49mm; text-align:right;">Total: </td>
 <td style="width:21mm; text-align:left"><?php echo  $tipoMoneda.' '.$venta['total'];?></td>

 </tr>
 </table>
    

 </div>
 <div class="en-letras">
 <span>   <?php 
       
       echo 'SON: '. CantidadEnLetra($venta['total'], $nombreMoneda);
       
       ?></span>
 </div>


 <div class="bar-code">
 <?php
$ruc = $emisor['ruc'];
$tipo_documento = $venta['tipocomp'];
$serie = $venta['serie'];
$correlativo = $venta['correlativo'];
$igv = $venta['igv'];
$total = $venta['total'];
$fecha = $venta['fecha_emision'];
$tipodoccliente = $venta['tipodoc'];
$nro_doc_cliente = $rucdni;

?>
 <qrcode class="barcode" value="<?php echo $texto_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|' . $correlativo . '|' . $igv . '|' . $total . '|' . $fecha . '|' . $tipodoccliente . '|' . $nro_doc_cliente; ?>" style="width: 28mm; background-color: white; color: #3c3c3c; border: none; padding:none"></qrcode>
 </div>
 <table class="tabla-tipo-pago">
                <tr>
                    <td colspan="2">
                        <b>Información Adicional</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        LEYENDA
                    </td>
                </tr> 
                <?php 
                    if(!empty($ventaCredito) && $ventaCredito['tipopago'] == "Credito"){  ?>
                <tr>
                    <td class="v45">FORMA DE PAGO:</td>
                   
                     <td class="v55">Crédito</td>
                     </tr>
                    
                     <?php
                        $cuotas = json_decode($ventaCredito['cuota'], true);
                        $fecha_cuota = json_decode($ventaCredito['fecha'], true);
                        $k=0;
                        for ($index = 0 ; $index < count($cuotas); $index ++) {
                            echo '<tr>
                                <td class="v45">Fecha de pago '.++$k.'</td>    
                                <td class="v55">Cuota '.$k.'</td>    
                            </tr>';
                            echo '<tr>
                                <td class="v45">'.$fecha_cuota[$index].'</td>    
                                <td class="v55">'.number_format($cuotas[$index],2).'</td>    
                            </tr>';
                        }
                     ?>
                    <?php }else{ 
                    echo '<tr><td class="v45">FORMA DE PAGO:</td>';
                      echo '<td class="v55">Contado</td></tr>';
                     } ?>
               
                <tr>
                    <td>VENDEDOR:</td>
                    <td class="mayu"><?php echo $vendedor['id'].'-'.substr($vendedor['perfil'],0,2); ?></td>
                </tr>
                <?php
                if(!empty($venta['comentario']) || $venta['comentario'] != ''){
                ?>
                <tr>

                    <td colspan="2" style="text-align:center">OBSERVACIONES:</td>
                </tr>
                <tr>
                    <td class="mayu" colspan="2"><?php echo $venta['comentario']; ?></td>
                </tr>
                <?php 
                }
                ?>
            </table>
 <?php 
        if($venta['tipocomp'] != '02'){
        ?>
 <div class="impresa">
 Representación impresa de la Factura Electrónica.
 <br>
 Consulte su documento en: https://www.sunat.gob.pe
 <br>
 <?php
  if($venta['bienesSelva'] == 'si'){
    echo "BIENES TRANSFERIDOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA";
}
if($venta['serviciosSelva'] == 'si'){
    echo "SERVICIOS PRESTADOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA";
}
?>
 </div>
 <?php } ?>
 </page>