<?php

use Controladores\ControladorReportes;
use Controladores\ControladorSunat;

?>
   <style>
  
  .v5{
    width: 5%;
}
.v10{
    width: 10%;
}
.v14{
    width: 14%;
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
.tabla-comprobantes, .tabla-header{
    position: relative;
            width:100%;
            border-collapse: collapse;
            font-size: 12px;
}
.tabla-header td{
    padding: 10px;
}
.tabla-header h3{
    color: #555;
    letter-spacing: 2px;
}
.tabla-comprobantes {
    margin-top: 10px;
}
.tabla-comprobantes th{
    padding: 10px;
    border: 1px solid #ccc;
    text-transform: uppercase;
    text-align: center;
}
.tabla-comprobantes td{
    padding: 10px;
    border: 1px solid #ccc;
    color: #000;
}
.center{
    text-align: center;
}
.titulo{
    font-size: 16px;
    color: #333;
    /* background-color: #F5F5F5; */
}
.fecha{
    font-size: 15px;
    color: #333;
    /* background-color: #F5F5F5; */
}
.totales {
    font-weight: 600;
    font-size: 14px;
    background-color: #F5F5F5;
}
.fa{
    background-color: #DAFFC7;
}
.bo{
    background-color: #CCDCFF;
}
</style>
   <page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm"> 
    <page_header> 
         
     
    </page_header> 
    <page_footer> 
         
    </page_footer>  
    
<table class="tabla-header">
    <tr>
        <td class="v20">
        <img src=" 
            <?php
        $logo =  (isset($emisor['logo']))? dirname(__FILE__).'/../../img/logo/'.$emisor['logo']:'';
        echo $logo;
        ?>
        " alt="" class="v100">
       
        </td>
        <td class="v80 center">
            <h3><?php echo $emisor['razon_social']; ?></h3>
        </td>
    </tr>
</table>
<table class="tabla-comprobantes">
    <tr>
        <?php 
        if($tipocomp == '00'){
            $nombre_comp = 'FACTURAS Y BOLETAS';
        }
        if($tipocomp == '01'){
            $nombre_comp = 'FACTURAS';
        }
        if($tipocomp == '02'){
            $nombre_comp = 'NOTAS DE VENTA';
        }
        if($tipocomp == '03'){
            $nombre_comp = 'BOLETAS';
        }
        if($tipocomp == '07'){
            $nombre_comp = 'NOTAS DE CRÉDITO';
        }
        if($tipocomp == '08'){
            $nombre_comp = 'NOTAS DE DÉBITTO';
        }
       
        ?>
        <th colspan="10" class="titulo">REPORTE DE VENTAS -  <?php echo $nombre_comp; ?></th>
         </tr>
         <tr>
         <th colspan="10" class="fecha">DESDE EL <?php echo $fechaini. ' HASTA EL '.$fechafin; ?></th>
         </tr>
   
    <tr>
    <th >#</th>
    <th >COMP.</th>
    <th >Serie #</th>
    <th >Medio de Pago</th>
    <th >Fecha</th>
    <th >Gravadas</th>
    <th >Exoneradas</th>
    <th >Inafectas</th>
    <th >I.G.V.</th>
    <th >TOTAL</th>
    </tr>

    <?php 
    $total = 0;
    $op_gravadas =0;
    $igv =0;
    $op_exoneradas =0;
    $op_inafectas =0;
    $totalD = 0;
    $op_gravadasD =0;
    $igvD =0;
    $op_exoneradasD =0;
    $op_inafectasD =0;
    $totalE = 0;
    $op_gravadasE =0;
    $igvE =0;
    $op_exoneradasE =0;
    $op_inafectasE =0;
    $css = '';
    $descnc = '';
    foreach($resultado as $k => $value):
        $item = 'codigo';
        @$valor = $value['metodopago'];
        $mediopago = ControladorSunat::ctrMostrarMetodoPago($item,$valor);

        if(isset($mediopago['descripcion'])){
            $mediodepago = $mediopago['descripcion'];
        }else{
            $mediodepago = '';
        }

        $valor = $value['tipocomp'];
        $tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);
        // if(isset($value['numoperacion']) && $value['numoperacion'] != '0'){
        //     $numoperacion = '';
        // }else{
        //     $numoperacion = '';
        // }
if($value['tipocomp'] == '01'){
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}
if($value['tipocomp'] == '03'){
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}
if($value['tipocomp'] == '02'){
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}
if($value['tipocomp'] == '07'){
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}
if($value['tipocomp'] == '08'){
    $nombre_comprobante = $tipo_comprobante['descripcion'];
}
if($tipocomp == '00'){
    if($value['tipocomp'] == '01'){
       $css = 'fa';  
    }
    if($value['tipocomp'] == '03'){
       $css = 'bo';  
    }
   
}
      if(($value['tipocomp'] == '01' || $value['tipocomp'] == '02' || $value['tipocomp'] == '03' || $tipocomp == '00') && $value['anulado'] =='n' && ($value['feestado'] == 1 || $value['feestado'] =='' || $value['anulado'] == 3)) {
        if($value['id_nc'] > 0 && $value['id_nc'] != null){
        $tabla = 'nota_credito';
        $item = 'id';
        $valor = $value['id_nc'];
        $notac = ControladorReportes::ctrMostrarNCD($tabla, $item, $valor);
        $op_gravadasNC = $notac['op_gravadas'];
        $op_exoneradasNC = $notac['op_exoneradas'];
        $op_inafectasNC = $notac['op_inafectas'];
        $igvnc = $notac['igv']; 
        $totalnc = $notac['total'];
        $descnc = 'Afecta NC N°.'.$notac['serie'].'-'.$notac['correlativo'].'';
        
        }else{
        $op_gravadasNC = 0;
        $op_exoneradasNC = 0;
        $op_inafectasNC = 0;
        $igvnc = 0;
        $totalnc = 0;
        $descnc = '';
        }
        if($value['id_nd'] > 0 && $value['id_nd'] != null){
        $tabla = 'nota_debito';
        $item = 'id';
        $valor = $value['id_nd'];
        $notad = ControladorReportes::ctrMostrarNCD($tabla, $item, $valor);
        $op_gravadasND = $notad['op_gravadas'];
        $op_exoneradasND = $notad['op_exoneradas'];
        $op_inafectasND = $notad['op_inafectas'];
        $igvnd = $notad['igv'];
        $totalnd = $notad['total'];
        $descnd = 'Afecta ND N°.'.$notad['serie'].'-'.$notad['correlativo'].'';
        
        }else{
        $op_gravadasND = 0;
        $op_exoneradasND = 0;
        $op_inafectasND = 0;
        $igvnd =0;
        $totalnd = 0;
        $descnd = '';
        }
       echo '<tr>
       <td class="v5">' . ++$k.'</td>
       <td class="v10 '.$css.'">' . $nombre_comprobante.'</td>
       <td class="v10 '.$css.'">' . $value['serie_correlativo'].'<br>'.$descnc. $descnd.'</td>
       <td class="v10">' . $mediodepago.'</td>
       <td class="v14">' . $value['fechahora'].'</td>
       <td class="v10">' . $value['op_gravadas'] - $op_gravadasNC + $op_gravadasND.'</td>
       <td class="v10">' . $value['op_exoneradas'] - $op_exoneradasNC + $op_exoneradasND.'</td>
       <td class="v10">' . $value['op_inafectas'] - $op_inafectasNC + $op_inafectasND.'</td>
       <td class="v10">' . $value['igv'] - $igvnc + $igvnd.'</td>
       <td class="v10">' . $value['total'] - $totalnc + $totalnd.'</td>
       
       </tr>';
       if($value['metodopago'] == '009'){
        $op_gravadasE += $value['op_gravadas'] - $op_gravadasNC + $op_gravadasND;
       $op_exoneradasE += $value['op_exoneradas'] - $op_exoneradasNC + $op_exoneradasND;
       $op_inafectasE += $value['op_inafectas'] - $op_inafectasNC + $op_inafectasND;
       $igvE += $value['igv'] - $igvnc + $igvnd;
        $totalE += $value['total'] - $totalnc + $totalnd; 
    }
      else{
        $op_gravadasD += $value['op_gravadas'] - $op_gravadasNC + $op_gravadasND;
       $op_exoneradasD += $value['op_exoneradas'] - $op_exoneradasNC + $op_exoneradasND;
       $op_inafectasD += $value['op_inafectas'] - $op_inafectasNC + $op_inafectasND;
       $igvD += $value['igv'] - $igvnc + $igvnd;
        $totalD += $value['total'] - $totalnc + $totalnd; 
    }
    
        $op_gravadas += $value['op_gravadas'] - $op_gravadasNC + $op_gravadasND;
       $op_exoneradas += $value['op_exoneradas'] - $op_exoneradasNC + $op_exoneradasND;
       $op_inafectas += $value['op_inafectas'] - $op_inafectasNC + $op_inafectasND;
       $igv += $value['igv'] - $igvnc + $igvnd;
        $total += $value['total'] - $totalnc + $totalnd;  
    
      
      }

      if(($value['tipocomp'] == '07' || $value['tipocomp'] == '08') && ($value['feestado'] == 1 || $value['feestado'] =='' || $value['feestado'] ==3)) {

        echo '<tr>
        <td class="v5">' . ++$k.'</td>
        <td class="v10">' . $nombre_comprobante.'</td>
        <td class="v10">' . $value['serie'].'-'.$value['correlativo'].'</td>
        <td class="v10">' . $mediodepago.'</td>
        <td class="v14">' . $value['fecha_emision'].'</td>
        <td class="v10">' . $value['op_gravadas'].'</td>
        <td class="v10">' . $value['op_exoneradas'].'</td>
        <td class="v10">' . $value['op_inafectas'].'</td>
        <td class="v10">' . $value['igv'].'</td>
        <td class="v10">' . $value['total'].'</td>
        
        </tr>';
        
        if(isset($value['metodopago']) && $value['metodopago'] == '009'){
            $op_gravadasE += $value['op_gravadas'];
           $op_exoneradasE += $value['op_exoneradas'];
           $op_inafectasE += $value['op_inafectas'];
           $igvE += $value['igv'];
            $totalE += $value['total']; 
        }
          else{
            $op_gravadasD += $value['op_gravadas'];
           $op_exoneradasD += $value['op_exoneradas'];
           $op_inafectasD += $value['op_inafectas'];
           $igvD += $value['igv'];
            $totalD += $value['total']; 
        }
        
            $op_gravadas += $value['op_gravadas'];
           $op_exoneradas += $value['op_exoneradas'];
           $op_inafectas += $value['op_inafectas'];
           $igv += $value['igv'];
            $total += $value['total']; 
       }

    endforeach;

    
    echo '<tr class="totales">
    
    <td colspan="5" class="v50" style="text-align: center;">TOTALES DEPÓSITOS:</td>    
    <td class="v10">'.number_format($op_gravadasD,2).'</td>    
    <td class="v10">'.number_format($op_exoneradasD,2).'</td>    
    <td class="v10">'.number_format($op_inafectasD,2).'</td>    
    <td class="v10">'.number_format($igvD,2).'</td>    
    <td class="v10">'.number_format($totalD,2).'</td>    
    
    </tr>';
    echo '<tr class="totales">
    
    <td colspan="5" class="v50" style="text-align: center;">TOTALES EN EFECTIVO:</td>    
    <td class="v10">'.number_format($op_gravadasE,2).'</td>    
    <td class="v10">'.number_format($op_exoneradasE,2).'</td>    
    <td class="v10">'.number_format($op_inafectasE,2).'</td>    
    <td class="v10">'.number_format($igvE,2).'</td>    
    <td class="v10">'.number_format($totalE,2).'</td>    
    
    </tr>';
    echo '<tr class="totales">
                   
                <td colspan="5" class="v50" style="text-align: center;">TOTALES:</td>    
                <td class="v10">'.number_format($op_gravadas,2).'</td>    
                <td class="v10">'.number_format($op_exoneradas,2).'</td>    
                <td class="v10">'.number_format($op_inafectas,2).'</td>    
                <td class="v10">'.number_format($igv,2).'</td>    
                <td class="v10">'.number_format($total,2).'</td>    
    
    </tr>';
        ?>



</table>
</page>