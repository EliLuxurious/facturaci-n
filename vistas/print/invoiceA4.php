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
        $nombre_comprobante = $tipo_comprobante['descripcion'];
    } 
    if($venta['tipocomp'] == '00'){
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;
        $nombre_comprobante = $tipo_comprobante['descripcion'];
    }

   ?>

        <style>
     
          #tabla-cabecera, #tabla-cliente, #tabla-items, #tabla-totales, .tabla-importes, .tabla-observacion{
           position: relative;
            width:100%;
            border-collapse: collapse;
           
          }
          #tabla-cabecera{
            text-align: center;
            letter-spacing: 0.5px;
            color: #333;
          }
           #tabla-cabecera h3{
              font-size: 16px;
              margin-bottom: 1px;
              color: #444;
          }
          #tabla-cliente td{
            border: 0.5px solid #333;
            padding: 7px;
            text-align: left;        
            font-size: 12px;            
            padding-left: 10px; 
            letter-spacing: 1px;
          }
          #tabla-totales td{ 
            padding: 7px;
            text-align: left;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
          .tabla-importes td{
            border: 0.5px solid #333;
            padding: 7px;
            text-align: left;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
          
          #tabla-cliente{
              margin-top: 10px;
          }
          #tabla-items{
            margin-top: 10px;
          }
          #tabla-items th{
            border: 0.5px solid #333;
            padding: 6px;
            text-align: center;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
          #tabla-items td{
            border: 0.5px solid #333;
            padding: 6px;
            text-align: center;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
              
          .ruc-emisor{
              position: relative;
              border: 1px solid #666;
              border-radius: 20px;
              text-align: center;
              vertical-align: top;
             
          }
          .ruc-emisor h4{
              color: #444;
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
.direccion{
    font-size: 10px;
}
.total-letras{
    width: 100%;
    border: 0.5px solid #333;
    font-size: 9px;
    text-align: left;
    border-radius: 10px;
    padding: 10px;
    margin-top: 5px;
    padding-left: 10px;
}

.tabla-observacion{
    position: relative;
   margin-top: 5px;
    
   
}
.tabla-observacion td{
    position: relative;
    vertical-align: baseline;
    
   
}
.tabla-tipo-pago{
    width: 70%;
    border-collapse: collapse;
}
.tabla-tipo-pago td{
   border-bottom: 0.5px solid #333;
    
    
}
  
.col{
    background-color: #999;
}
.pie-pag{
    padding: 10px;
    font-size: 12px;
    border: 0.5px solid #333;
    margin-top: 10px;
    border-radius: 10px; 
}
.b-l{
    border-radius: 7px 0px 0px 0px;
}
.b-r{
    border-radius: 0px 7px 0px 0px;
}
.mayu{
    text-transform: uppercase;
}
.anulado-print{
    position: absolute;
    top:30%;
    left:23%;
    color: #FF7979;
    font-size: 30px;
    text-align: center;
    font-weight: bold
}
  </style>


 <page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm"> 
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
<!-- CABECERA COMPROBANTE=================== -->
<table id="tabla-cabecera">
  <tr>
      <!-- LOGO================== -->
    <td class="v25"><img src="<?php
    
        $logo =  (isset($emisor['logo']))? dirname(__FILE__).'/../img/logo/'.$emisor['logo']:'';
        echo $logo;
    ?>"  class="v100"></td>
    <!--FIN LOGO================== -->
    <td class="v45">
        <h3><?php echo $emisor['razon_social'];?></h3>
        <label class="direccion"><?php echo $emisor['direccion'];?></label>
        <br>
        <span class="direccion">Telf. <?php echo $emisor['telefono']; ?></span>

    </td>
    <td class="v30" style="text-align: left">
        <div class="ruc-emisor v100">
            <h4>R.U.C. <?php echo $emisor['ruc'];?></h4>
            <h4><?php echo $nombre_comprobante;?></h4>
            <h4><?php echo $venta['serie'] . ' - ' .str_pad( $venta['correlativo'],6,"0",STR_PAD_LEFT); ?></h4>
        </div>
    </td>
  </tr>

</table>
<!--FIN CABECERA COMPROBANTE=================== -->

<!--CLIENTE COMPROBANTE=================== -->
<table id="tabla-cliente">
    <tr>
        <td class="v25 b-l">FECHA DE EMISIÓN:</td>
        <td class="v25">
            <?php 
             echo  date("d/m/Y", strtotime($venta['fecha_emision']));
             ?>
        </td>
        <td class="v25">MEDIO DE PAGO:</td>
        <td class="v25 b-r"><?php echo $metodo_pago['descripcion']?></td>
    </tr>
    <tr>
        <td class="v25">CLIENTE:</td>
        <td class="v75" colspan="3"><?php echo $razons_nombre ?></td>
        
    </tr>
    <tr>
        <td class="v25">R.U.C/D.N.I:</td>
        <td class="v75" colspan="3"><?php echo $rucdni ?></td>
        
    </tr>
    <tr>
        <td class="v25">DIRECCIÓN:</td>
        <td class="v75" colspan="3"><?php echo $cliente['direccion']?></td>
        
    </tr>
    <tr>
        <td class="v25">TIPO DE MONEDA:</td>
        <td class="v75" colspan="3"><?php echo $nombreMoneda?></td>
        
    </tr>

</table>
<!--FIN CLIENTE COMPROBANTE=================== -->

<!-- ITEMS COMPROBANTE=================== -->
<table id="tabla-items">
<tr class="">
    <th class="v10 b-l">ITEM</th>
    <th class="v10">CANTIDAD</th>
    <th class="v40">DESCRIPCIÓN</th>
    <th class="v20">VALOR U.</th>
    <th class="v20 b-r">IMPORTE</th>
</tr>
<?php
            $descuentosItems = 0.00;
             foreach($detalle as $key => $fila){ 

            $importe_total = ($fila['valor_unitario'] > 0) ? $fila['importe_total'] : number_format(0.00,2);    

                ?>
<tr>
    <td class="v10"><?php echo ++$key ?><</td>
    <td class="v10"><?php echo   $fila['cantidad']; ?></td>
    <td class="v40"><?php echo $fila['descripcion'] ;?></td>
    <td class="v20"><?php echo  $tipoMoneda.' '.$fila['valor_unitario'] ;?></td>
    <td class="v20"><?php echo   $tipoMoneda.' '.$importe_total; ?><</td>
</tr>

<?php 
            $descuentosItems += $fila['descuento'];  
        }
               $descuentosItems;  

               $descuentos = round($venta['descuento'] + $descuentosItems,2);

?>

</table>
<!-- FIN ITEMS COMPROBANTE=================== -->
    <div class="total-letras">
    <?php
    echo 'SON: '.CantidadEnLetra($venta['total'], $nombreMoneda);
    ?>
    </div>

      
    <table id="tabla-totales">
        
     <tr>
         <td class="v65" style="padding: 0px;">
            <table class="tabla-observacion">
                <tr>
                    <td class="v30">
                      
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
                    <qrcode class="barcode" value="<?php echo $texto_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|' . $correlativo . '|' . $igv . '|' . $total . '|' . $fecha . '|' . $tipodoccliente . '|' . $nro_doc_cliente; ?>" style="width: 26mm; background-color: white; color: #000; border: none; padding:none"></qrcode>

                </td>
                    
                    <td class="v70">

                       <b>Observación:</b><br>
                       Consulte su documento electrónico en https://www.sunat.gob.pe
                    </td>
                    
                </tr>
               
            </table>        
        
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

         </td>

         <td class="v35">
             <table class="tabla-importes">
             <tr>               
               <td class="v50 b-l">GRAVADAS:</td>
               <td class="v50 b-r"><?php echo $tipoMoneda.$venta['op_gravadas'];?></td>
            </tr>        
           <tr>               
               <td>EXONERADAS:</td>
               <td><?php echo $tipoMoneda.$venta['op_exoneradas'];?></td>
          </tr>
           <tr>               
               <td>INAFECTAS:</td>
               <td><?php echo $tipoMoneda.$venta['op_inafectas'];?></td>
          </tr>
           <tr>               
               <td>GRATUITAS:</td>
               <td><?php echo $tipoMoneda.$venta['op_gratuitas'];?></td>
          </tr>
           <tr>               
               <td>DESCUENTO(-):</td>
               <td><?php echo  $tipoMoneda.number_format(($descuentos),2);?></td>
          </tr>
           <tr>               
               <td>ICBPER:</td>
               <td><?php echo  $tipoMoneda.$venta['icbper'];?></td>
          </tr>
           <tr>               
               <td>IGV(18%):</td>
               <td><?php echo  $tipoMoneda.$venta['igv'];?></td>
          </tr>
           <tr>               
               <td>TOTAL:</td>
               <td><?php echo  $tipoMoneda.$venta['total'];?></td>
          </tr>
             </table>
         </td>
     </tr>          
        </table>
<div class="pie-pag">
    Representación impresa de la <?php echo $nombre_comprobante;?>
    <?php 
    if($emisor['modo'] == 'n'){
        echo "<p>COMPROBANTE DE PRUEBA, NO TIENE NINGÚNA VALIDEZ</p>";
    }
    if($venta['bienesSelva'] == 'si'){
        echo "BIENES TRANSFERIDOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA";
    }
    if($venta['serviciosSelva'] == 'si'){
        echo "SERVICIOS PRESTADOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA";
    }
    ?>
</div>
</page>