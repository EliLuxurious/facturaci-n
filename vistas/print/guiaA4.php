<?php
//Consultar los datos necesarios para mostrar en el PDF - FIN

    if($guia['cli_tipodoc'] == 6){
        $rucdni = $cliente['ruc'];
        $razons_nombre = $cliente['razon_social'];
        $tipodoccliente = 6;
       
    }
     if($guia['cli_tipodoc'] == 1){
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;

    }
     if($guia['cli_tipodoc'] != 1 && $guia['cli_tipodoc'] != 6){
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;

    }
        $nombre_comprobante = 'GUÍA DE REMISIÓN REMITENTE ELECTRÓNICA';
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
          #tabla-cliente div{
            font-weight: bold;
          }
          .observacion{
            border: 1px solid #666;
            margin-top: 20px;
            padding: 10px;
            font-size: 10px;
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
.title{
    text-align: center;
    font-weight: bold;
}
.title2{
    text-align: center;
}
.bar-code{
    margin-top: 10px;
}
  </style>


 <page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm"> 
    <page_header> 
         
     
    </page_header> 
    <page_footer> 
         
    </page_footer>  

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
            <h4><?php echo $guia['serie'] . ' - ' .str_pad( $guia['correlativo'],6,"0",STR_PAD_LEFT); ?></h4>
        </div>
    </td>
  </tr>

</table>
<!--FIN CABECERA COMPROBANTE=================== -->

<!--CLIENTE COMPROBANTE=================== -->
<table id="tabla-cliente">
    <tr>
        <td colspan="2" class="v100">
            <div style="text-align: center;">DESTINATARIO</div>
        </td>
    </tr>
    <tr>
        <td class="v25">NOMBRE/RAZON SOCIAL:</td>
        <td class="v75"><?php echo $razons_nombre ?></td>
        
    </tr>
    <tr>
        <td class="v25">R.U.C/D.N.I:</td>
        <td class="v75"><?php echo $rucdni ?></td>
        
    </tr>
    <tr>
        <td class="v25">DIRECCIÓN:</td>
        <td class="v75"><?php echo $cliente['direccion']?></td>
        
    </tr>
   

</table>
<table id="tabla-cliente">
    <tr>
        <td colspan="4" class="v100">
            <div style="text-align: center;">ENVÍO</div>
        </td>
    </tr>
    <tr>
        <td class="v25">FECHA EMISIÓN:</td>
        <td class="v25"><?php echo date_format(date_create($guia['fecha_emision']), 'd/m/Y');?></td>

        <td class="v25">FECHA INICIO TRASLADO:</td>
        <td class="v25"><?php echo date_format(date_create($guia['fechaTraslado']), 'd/m/Y');?></td>
    </tr>
    <tr>
        <td class="v25">MOTIVO TRASLADO:</td>
        <td class="v25"><?php echo $motivo_traslado['descripcion'] ?></td>
         <td class="v25">MOD. TRANSPORTE:</td>
        <td class="v25"><?php echo $modalidad_traslado['descripcion'] ?></td>
    </tr>
    <tr>
        <td class="v25">PESO BRUTO TOTAL:</td>
        <td class="v25"><?php echo $guia['pesoTotal'].' ('.$guia['uniPeso'].')' ?></td>
         <td class="v25">NÚMERO DE BULTOS:</td>
        <td class="v25"><?php echo $guia['numBultos']?></td>
    </tr>
</table>

<table id="tabla-cliente">
    <tr>
        <td class="v50 title">PUNTO DE PARTIDA:</td>
        <td class="v50 title">PUNTO DE LLEGADA:</td>

    </tr>
    <tr>
        <td class="v50 title2" colspan=""><?php  echo @$guia['direccionPartida'].'<br>'.@$ubigeoPartida['nombre_distrito'].' - '.@$ubigeoPartida['nombre_provincia'].' - '.@$ubigeoPartida['name'] ?></td>
     
        <td class="v50  title2" colspan=""><?php echo @$guia['direccionLlegada'].'<br>'.@$ubigeoLlegada['nombre_distrito'].' - '.@$ubigeoLlegada['nombre_provincia'].' - '.@$ubigeoLlegada['name'] ?></td>
    </tr>
</table>

<table id="tabla-cliente">
    <!-- <tr>
        <td colspan="4" class="v100">
            <div style="text-align: center;">TRANSPORTE</div>
        </td>
    </tr> -->
    <?php if($guia['modTraslado'] == '01'){ ?>
    <tr>
        <td class="v50 title">RUC TRANSPORTE:</td>
        <td class="v50 title">RAZÓN SOCIAL TRANSPORTE:</td>
    </tr>
    <tr>  
        <td class="v50 title2"><?php echo $guia['transp_numDoc'];?></td>
        <td class="v50 title2"><?php echo $guia['transp_nombreRazon'];?></td>
    </tr>
    <?php } else{ ?>

    <tr>
        <td class="v50 title">UNIDAD DE TRANSPORTE:</td>
        <td class="v50 title">DATOS CONDUCTORES:</td>
    </tr>
    <tr>
        <td class="v50 title2"><?php echo $guia['transp_placa'];?></td>
        <td class="v50 title2"><?php echo $guia['transp_nombreRazon'].'<br> DNI: '. $guia['numDocChofer'];?></td>
    </tr>
        <?php } ?>
</table>
<!--FIN CLIENTE COMPROBANTE=================== -->

<!-- ITEMS COMPROBANTE=================== -->
<table id="tabla-items">
<tr class="">
    <th class="v10 b-l">ITEM</th>
    <th class="v10">CODIGO</th>
    <th class="v40">DESCRIPCIÓN</th>
    <th class="v20">UNIDAD</th>
    <th class="v20 b-r">CANTIDAD</th>
</tr>
<?php
         
             foreach($detalle as $key => $fila){ 
  
                ?>
<tr>
    <td class="v10"><?php echo ++$key ?><</td>
    <td class="v10"><?php echo   $fila['codigo']; ?></td>
    <td class="v40"><?php echo $fila['descripcion'] ;?></td>
    <td class="v20"><?php echo $fila['codunidad'] ;?></td>
    <td class="v20"><?php echo $fila['cantidad']; ?><</td>
</tr>
<?php } ?>
</table>
<!-- FIN ITEMS COMPROBANTE=================== -->
<?php
 if($guia['observacion'] != ''){
?>
 <div class="observacion v100"><b>OBSERVACIONES:</b><br>
<?php 
   echo $guia['observacion'];
?>
</div>  
<?php
 }
 $ruc = $emisor['ruc'];
 $tipo_documento = $guia['tipodoc'];
 $serie = $guia['serie'];
 $correlativo = $guia['correlativo'];
 $fecha = $guia['fecha_emision'];
 $nro_doc_cliente = $rucdni;
?>
<div class="bar-code">
 <qrcode class="barcode" value="<?php echo $texto_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|'. $fecha . '|' . $tipodoccliente . '|' . $nro_doc_cliente; ?>" style="width: 26mm; background-color: white; color: #000; border: none; padding:none"></qrcode>   
</div>


<div class="pie-pag">
    Representación impresa de la <?php echo $nombre_comprobante;?>
    <?php 
    if($emisor['modo'] == 'n'){
        echo "<p>COMPROBANTE DE PRUEBA, NO TIENE NINGÚNA VALIDEZ</p>";
    }

    ?>
</div>
</page>