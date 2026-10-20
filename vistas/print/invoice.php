
   <?php
//Consultar los datos necesarios para mostrar en el PDF - FIN
if($venta['codmoneda'] == 'PEN'){
    $tipoMoneda = 'S/ ';
    $nombreMoneda = 'SOLES';
}else {
    $tipoMoneda = '$USD ';
    $nombreMoneda = 'DÓLARES';
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

   ?>
   <style>
  
   .anulado-print{
    position: absolute;
    width: 100%;
    height: 100%;
    font-size: 25px;
    color: red;
    z-index: 99999;
    text-align: center;
    font-weight: bold;

   }
   .anulado-print span{
       font-size: 16px;
    color: red;

   }
  
  table{
        width: 100%;
        border-collapse: collapse;
     //background: green;
    }
    .head{
        
        color: #5d5d5d;
       /* // background-color: #fff000 */
    }
    .datos-cliente{
        width: 100%;
        border-collapse: collapse;
       margin-top: 2mm
      
    }
    .datos-cliente td{
        border: .1px solid #000;
        padding: 10px;
        font-size: 11px;
        padding-top: 7px;
        padding-bottom: 7px;
        color: #242424;
    }
    .full-contenedor{
        position: relative;
       width: 100%;
       height: 100%;
       z-index: 1;
    }
   .first{
        
        border-radius: 5px 0px 0px 0px;

    }
   .last{
        
        border-radius: 0px 5px 0px 0px;

    }
    .descripcion-productos th{
        padding: 2mm;
        text-align: center;
        color: #000;
        font-size: 11px;
        border: 0.05px solid black;
        
    }
    
    .descripcion-productos td{
        padding: .5mm;
        font-size: 11px;
        color: #242424;
        border: .05px solid #000;
        /* border-right: .2px solid #000; */
        text-align: center;
    }
    .montototal{
        width: 100%;
        margin-top: 5px;
    }
  
    .montototal td{
        width: 186.2mm;
        border-radius: 5px;
        padding: 5px;
        border: .05px solid #000;
        color: #242424;
        font-size: 8px;
        padding-left: 5mm;
        
    }
    .barcode{
        
        border: none;
        margin-top: 15px;
    }
    .totales{
        width: 100%;
        border-collapse: collapse;
        
    }
    .totales td{
        font-size: 13px;
        padding: 1mm;
        text-align: left;
        color: #242424;
    }
    .barc-resumen{
        margin: 10px;
        width: 100%;
        background-color: white;
        
    }
/*
    .descripcion-productos tr{
        border-bottom: 1px solid black;
    }
*/

</style>
   <page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm"> 
    <page_header> 
         
     
    </page_header> 
    <page_footer> 
         
    </page_footer>  
    
 <div class="full-contenedor">

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

 <div>
 <table class="head"><tr>
         <td style="width:45mm;text-align:center;vertical-align: middle;">
         <?php if($emisor['logo'] == ''){ 

         }else

         echo '<img style="width:100%" src="'.dirname(__FILE__).'/../img/logo/'.$emisor['logo'].'" alt="">';

         ?>
        </td>
         <td style="text-align:left; width:92mm;vertical-align: middle">
         <div style="text-align:center;font-size:18px;width:100%; font-weight:bold"><?php echo $emisor['razon_social']; ?></div> 
         <div style="text-align:center;font-size:12px;width:100%"><?php echo $emisor['direccion']; ?> <br>Telf. <?php echo $emisor['telefono']; ?></div>
         </td>
             <td style="text-align:left;  width: 55mm"><div style="text-align:center; border: .3px solid #000;width: 55mm; padding:.5mm 2mm .5mm 2mm; border-radius:10px;"><h4 style="margin-botton:0px; font-size:15px;"><?php echo $emisor['ruc']; ?></h4>
             <h5 style="margin:0px; font-size:16px"><?php echo $nombre_comprobante; ?></h5>
         <h4 style="margin-botton:0px; font-size:15px"><?php echo $venta['serie'] . ' - ' .str_pad( $venta['correlativo'],6,"0",STR_PAD_LEFT); ?></h4></div>
         </td>
         </tr>
         </table>
         </div>
     
    <table  class="datos-cliente">
             <tr class="clie"><td style="width:30mm" class="first">FECHA DE EMISIÓN:</td><td style="width:45mm" ><?php 
             echo  date("d-m-Y", strtotime($venta['fecha_emision']));?>
             </td>
             <td style="width:26mm">MEDIO DE PAGO:</td>
             <td style="width:55mm" class="last"><?php echo $metodo_pago['descripcion']?>
            </td>
            </tr>
             <tr class="clie"><td>CLIENTE:</td><td colspan="3"><?php echo $razons_nombre ?></td></tr>
             <tr class="clie"><td>R.U.C/D.N.I:</td><td colspan="3"><?php echo $rucdni ?></td></tr>
             <tr class="clie"><td>DIRECCIÓN:</td><td colspan="3"><?php echo $cliente['direccion'] ?></td></tr>
         </table>
 
        
         
        <table  class="descripcion-productos" style="margin-top: 2mm">
           <thead>
            <tr>
            <th style="width:13mm" class="first">ITEM</th>
            <th style="width:13mm">CANTIDAD</th>
            <th style="width:83mm;">DESCRIPCIÓN</th>            
            <th style="width:25mm">VALOR U.</th>
            <th style="width:25mm" class="last">IMPORTE</th>
            </tr>
            </thead>
            
            <tbody>

            <?php
            $descuentosItems = 0.00;
             foreach($detalle as $key => $fila){ 
        //        $item = "codigo";
        //        $valor = $fila['idproducto'];
        //    $producto = ControladorProductos::ctrMostrarProductos($item, $valor);
                                
                ?>
             <tr>
             
             <td style="text-align:center"><?php  echo  ++$key ;?></td>
             <td style="text-align:center"><?php echo   $fila['cantidad']; ?></td>
             <td style="padding-left:12px"><?php echo $fila['descripcion'] ;?></td>
             
             <td style="padding-left:12px"><?php echo  $tipoMoneda.' '.$fila['valor_unitario'] ;?></td>

             <?php  if($fila['valor_unitario'] > 0) { ?>
             <td style="padding-left:12px"><?php echo   $tipoMoneda.' '.$fila['importe_total']; ?></td>

             <?php } else { ?>
                <td style="padding-left:12px"><?php echo   $tipoMoneda.' '.$fila['valor_unitario']; ?></td>
                <?php } ?>
             </tr>

                <?php
                $descuentosItems += $fila['descuento'];  
            }
                   $descuentosItems;  
                
                ?>

        </tbody>
<!--
        <tfoot>
            <tr><td colspan="5" style="border-top:1px solid black; padding:5px;">
                SON: CIENTO CINCUENTA Y 60/100 SOLES
            </td></tr>
        </tfoot> 
-->
         </table>

         <table class="montototal">
       <tr><td>
       <?php 
       
       echo 'SON: '. CantidadEnLetra($venta['total'], $nombreMoneda);
       
       ?>
           
       </td></tr>
   </table>

   <table class="barc-resumen">

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

   <tr><td style="width:30mm;"><qrcode class="barcode" value="<?php echo $texto_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|' . $correlativo . '|' . $igv . '|' . $total . '|' . $fecha . '|' . $tipodoccliente . '|' . $nro_doc_cliente; ?>" style="width: 26mm; background-color: white; color: #000; border: none; padding:none"></qrcode></td>
  

   <td  style="width:88mm; padding:10px">
    <?php 
        if($venta['tipocomp'] != '02'){
        ?>
       <h5>Observación: </h5>
<label for="" style="font-size:10px;">Consulte su documento electrónico en:
    https://www.sunat.gob.pe
HASH: vEQaYLNAX/q/5UHuKYEHdaHfM34=</label>
    <?php } ?>

</td>

  

   <td  style="width:55mm;vertical-align: top;">
    <div style=";width:100%;margin:0">
        <?php 
        if($venta['tipocomp'] == '02'){
        ?>
             <table class="totales" style=";width:100%;margin:0;">
             <tr>
          <td style="width:30mm;">Op. Gravada:</td>
          <td style="width:25mm;padding-left:12px"><?php echo $tipoMoneda.' '.$venta['op_gravadas'];?></td>
      </tr>
         <tr>
          <td style="width:30mm;">Op. Exonerada:</td>
          <td style="width:25mm;padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['op_exoneradas'];?></td>
      </tr>
         <tr>
          <td style="width:30mm;">Op. Inafectas:</td>
          <td style="width:25mm;padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['op_inafectas'];?></td>
      </tr>
         <tr>
          <td style="width:30mm;">Op.Gratuitas:</td>
          <td style="width:25mm;padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['op_gratuitas'];?></td>
      </tr>
         <tr>
          <td>Descuentos (-):</td>
          <td style="padding-left:12px"><?php echo  $tipoMoneda.round(($venta['descuento']+$descuentosItems),2);?></td>
      </tr>
         <tr>
          <td>IGV(18%):</td>
          <td style="padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['igv'];?></td>
      </tr>
         <tr>
          <td>Total:</td>
          <td style="padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['total'];?></td>
      </tr>
  </table>
        <?php }else {
            
            $op_gratuitas = $venta['op_gratuitas'];
            
            ?>
     <table class="totales" style=";width:100%;margin:0;">
      <tr>
          <td style="width:30mm;">Op. Gravada:</td>
          <td style="width:25mm;padding-left:12px"><?php echo $tipoMoneda.' '.$venta['op_gravadas'];?></td>
      </tr>
         <tr>
          <td style="width:30mm;">Op. Exonerada:</td>
          <td style="width:25mm;padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['op_exoneradas'];?></td>
      </tr>
         <tr>
          <td style="width:30mm;">Op. Inafectas:</td>
          <td style="width:25mm;padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['op_inafectas'];?></td>
      </tr>
         <tr>
          <td style="width:30mm;">Op. Gratuitas:</td>
          <td style="width:25mm;padding-left:12px"><?php echo  $tipoMoneda.' '.number_format($op_gratuitas, 2);;?></td>
      </tr>
         <tr>
          <td>Descuentos (-):</td>
          <td style="padding-left:12px"><?php echo  $tipoMoneda.round(($venta['descuento']+$descuentosItems),2);?></td>
      </tr>
         <tr>
          <td>IGV(18.00%):</td>
          <td style="padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['igv'];?></td>
      </tr>
         <tr>
          <td>Total:</td>
          <td style="padding-left:12px"><?php echo  $tipoMoneda.' '.$venta['total'];?></td>
      </tr>
  </table>
        <?php } ?>
    </div> 
 </td>
   </tr>
   
       
  </table>
  <!-- <div style=";width:100mm; padding:5px;padding-left: 16px; font-size:10px">
  Representación Impresa de Documento Electrónico Generado
En Una Versión de Pruebas. No tiene Validez!
</div>    -->
</div>

<!--   <barcode dimension="1D" type="EAN13" value="45" label="label" style="width:30mm; height:6mm; color: #770000; font-size: 4mm"></barcode> -->

</page> 
