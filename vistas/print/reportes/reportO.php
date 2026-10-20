<?php 
use Controladores\ControladorSunat;
?>
   <style>
  
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
        <td class="v30">
        <img src=" 
            <?php
        $logo =  (isset($emisor['logo']))? dirname(__FILE__).'/../../img/logo/'.$emisor['logo']:'';
        echo $logo;
        ?>
        " alt="" class="v100">
       
        </td>
        <td class="v70 center">
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
        <th colspan="8" class="titulo">REPORTE DE VENTAS -  <?php echo $nombre_comp; ?></th>
    </tr>
    <tr>
    <th >#</th>
    <th >COMP.</th>
    <th >Serie #</th>
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
    $css = '';
    foreach($resultado as $k => $value):

        $valor = $value['tipocomp'];
        $tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);
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

       echo '<tr class="">
       <td class="v5">' . ++$k.'</td>
       <td class="v10 '.$css.'">' . $nombre_comprobante.'</td>
       <td class="v10 '.$css.'">' . $value['serie_correlativo'].'</td>
       <td class="v15">' . $value['op_gravadas'].'</td>
       <td class="v15">' . $value['op_exoneradas'].'</td>
       <td class="v15">' . $value['op_inafectas'].'</td>
       <td class="v15">' . $value['igv'].'</td>
       <td class="v15">' . $value['total'].'</td>
       
       </tr>';
       $op_gravadas += $value['op_gravadas'];
       $op_exoneradas += $value['op_exoneradas'];
       $op_inafectas += $value['op_inafectas'];
       $igv += $value['igv'];
        $total += $value['total'];
      }

      if(($value['tipocomp'] == '07' || $value['tipocomp'] == '08') && ($value['feestado'] == 1 || $value['feestado'] =='' || $value['feestado'] ==3)) {

        echo '<tr>
        <td class="v5">' . ++$k.'</td>
        <td class="v10">' . $nombre_comprobante.'</td>
        <td class="v10">' . $value['serie_correlativo'].'</td>
        <td class="v15">' . $value['op_gravadas'].'</td>
        <td class="v15">' . $value['op_exoneradas'].'</td>
        <td class="v15">' . $value['op_inafectas'].'</td>
        <td class="v15">' . $value['igv'].'</td>
        <td class="v15">' . $value['total'].'</td>
        
        </tr>';
        $op_gravadas += $value['op_gravadas'];
        $op_exoneradas += $value['op_exoneradas'];
        $op_inafectas += $value['op_inafectas'];
        $igv += $value['igv'];
         $total += $value['total'];
       }

    endforeach;

    echo '<tr class="totales">
                   
                <td colspan="3" class="v25" style="text-align: center;">TOTALES:</td>    
                <td class="v15">'.number_format($op_gravadas,2).'</td>    
                <td class="v15">'.number_format($op_exoneradas,2).'</td>    
                <td class="v15">'.number_format($op_inafectas,2).'</td>    
                <td class="v15">'.number_format($igv,2).'</td>    
                <td class="v15">'.number_format($total,2).'</td>    
    
    </tr>';
        ?>



</table>
</page>


