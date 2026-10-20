<?php
namespace Controladores;
use Modelos\ModeloReportes;
class ControladorReportes{

    public static function ctrSumaFacturas($tabla, $tipoc, $fechaInicial, $fechaFinal){
        if($tipoc != null){
        $where = "WHERE tipocomp=$tipoc AND (anulado = 'n' AND feestado != 2) AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
        }else{
            $where = "WHERE (anulado = 'n' AND feestado != 2) AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";

        }
        $respuesta = ModeloReportes::mdlSumaComprobantes($tabla, $where);
        return $respuesta;
    }
    public static function ctrSumaCompras($tabla, $tipoc, $fechaInicial, $fechaFinal){
        if($tipoc != null){
        $where = "WHERE tipocomp=$tipoc AND (anulado = 'n') AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
        }else{
            $where = "WHERE (anulado = 'n') AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";

        }
        $respuesta = ModeloReportes::mdlSumaComprobantes($tabla, $where);
        return $respuesta;
    }
    
    public static function ctrSumaNotas($tabla, $tipoc, $fechaInicial, $fechaFinal){
        if($tipoc != null){
        $where = "WHERE tipocomp=$tipoc AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
        }else{
            $where = "WHERE fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
        }
        $respuesta = ModeloReportes::mdlSumaComprobantes($tabla, $where);
        return $respuesta;
    }

    public function ctrReportes(){
        $resultado = ModeloReportes::mdlReportes();
       echo $resultado;
    }
    public function ctrReportesCompras(){
        $resultado = ModeloReportes::mdlReportesCompras();
       echo $resultado;
    }
    public static function ctrReporteVentasDashboard($tabla, $fechaInicial, $fechaFinal){

        $respuesta = ModeloReportes::mdlReporteVentasDashboard($tabla, $fechaInicial, $fechaFinal);
        return $respuesta;
        
    }
     
    public static function ctrReporteVentasExcel($tabla, $fechaInicial, $fechaFinal){

        $respuesta = ModeloReportes::mdlReporteVentasExcel($tabla, $fechaInicial, $fechaFinal);
        return $respuesta;
        
    }
    public static function ctrReporteComprasExcel($tabla, $fechaInicial, $fechaFinal){

        $respuesta = ModeloReportes::mdlReporteComprasExcel($tabla, $fechaInicial, $fechaFinal);
        return $respuesta;
        
    }
    public static function ctrReporteVentasNcdExcel($tabla, $fechaInicial, $fechaFinal){

        $respuesta = ModeloReportes::mdlReporteVentasNcdExcel($tabla, $fechaInicial, $fechaFinal);
        return $respuesta;
        
    }
    public static function ctrDescargaReporteExcel(){

        $Name = $_GET["reporte"].'.xls';

        header("Content-type: application/vnd.ms-excel; charset=iso-8859-1"); // Archivo de Excel    
        header('Content-Disposition: attachment; filename="'.$Name.'"');
    
        echo "<table border='0'> 
        <tr>
        <td colspan='19' style='text-align:center; font-weight:bold; font-size:28px; background: #53F442; padding:8px;'>REPORTE DE VENTAS</td>
        </tr>
                <tr>
                <td></td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12</td>
                <td>13</td>
                <td>14</td>
                <td>15</td>
                <td>16</td>
                <td>17</td>
                <td>18</td>
                </tr>
        
                <tr> 
                <td style='font-weight:bold; border:1px solid #eee;'>PERIODO</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>CODIGO UNICO (CUO)</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>FECHA E</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>FECHA V</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>TIPO COMP</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>SERIE</td>
                <td style='font-weight:bold; border:1px solid #eee;'>CORRELATIVO</td>
                <td style='font-weight:bold; border:1px solid #eee;'>MONEDA</td>
                <td style='font-weight:bold; border:1px solid #eee;'>TIPO CAMBIO</td>
                <td style='font-weight:bold; border:1px solid #eee;'>GRAVADAS</td>
                <td style='font-weight:bold; border:1px solid #eee;'>EXONERADAS</td>
                <td style='font-weight:bold; border:1px solid #eee;'>INAFECTAS</td>
                <td style='font-weight:bold; border:1px solid #eee;'>I.G.V.</td>
                <td style='font-weight:bold; border:1px solid #eee;'>NETO</td>		
                <td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>	
                <td style='font-weight:bold; border:1px solid #eee;'>SERIE</td>	
                <td style='font-weight:bold; border:1px solid #eee;'>CORRELATIVO</td>	
                <td style='font-weight:bold; border:1px solid #eee;'>ANULADO</td>	
                <td style='font-weight:bold; border:1px solid #eee;'>ESTADO EN LA SUNAT</td>	
                </tr>";
            $tabla = 'venta';
            $fechaInicial = $_GET['fechainicial'];
            $fechaFinal = $_GET['fechafinal'];
            $reportes = ControladorReportes::ctrReporteVentasExcel($tabla, $fechaInicial, $fechaFinal);
            
            $a =substr($fechaInicial,0 , 4);
            $m = substr($fechaInicial,5, 2);

            foreach ($reportes as $k => $v){ 
          
             
            echo "<tr>
                
                <td style='border:1px solid #eee;'>".$a.$m."00</td> 
                <td style='border:1px solid #eee;'>".++$k."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['tipocomp']."</td> 
                <td style='border:1px solid #eee;'>".$v['serie']."</td> 
                <td style='border:1px solid #eee;'>".$v['correlativo']."</td> 
                <td style='border:1px solid #eee;'>".$v['codmoneda']."</td>
                <td style='border:1px solid #eee;'>".$v['tipocambio']."</td>"; 
            if($v['anulado'] == 'n'){
               echo "<td style='border:1px solid #eee;'>".$v['op_gravadas']."</td> 
                <td style='border:1px solid #eee;'>".$v['op_exoneradas']."</td> 
                <td style='border:1px solid #eee;'>".$v['op_inafectas']."</td>  
                <td style='border:1px solid #eee;'>".$v['igv']."</td> 
                <td style='border:1px solid #eee;'>".$v['subtotal']."</td> 
                <td style='border:1px solid #eee;'>".$v['total']."</td>";
            }
            if($v['anulado'] == 's'){
               echo "<td style='border:1px solid #eee;'>0</td> 
                <td style='border:1px solid #eee;'>0</td> 
                <td style='border:1px solid #eee;'>0</td>  
                <td style='border:1px solid #eee;'>0</td> 
                <td style='border:1px solid #eee;'>0</td> 
                <td style='border:1px solid #eee;'>0</td>";
            }
                echo "<td style='border:1px solid #eee;'></td>
                <td style='border:1px solid #eee;'></td>";
                
                if($v['anulado'] == 'n'){
                    echo "<td style='border:1px solid #eee;'>NO</td>";
                }else{
                    echo "<td style='border:1px solid #eee;'>SI</td>";
                }
                if($v['feestado'] == '1' || $v['resumen'] == 's'){
                    echo "<td style='border:1px solid #eee;'>0 ACEPTADO POR SUNAT</td>";
                }
                if($v['feestado'] == '2'){
                    echo "<td style='border:1px solid #eee; background-color: #F85A5A;'>2 RECHAZADO POR SUNAT</td>";
                
                }if(($v['feestado'] == '3' || $v['feestado'] == '') && $v['resumen'] =='n' ){
                    echo "<td style='border:1px solid #eee;'>NO ENVIADO A SUNAT</td>";
                }

            echo "</tr>";
            
        
    }
    $fechaInicial = $_GET['fechainicial'];
    $fechaFinal = $_GET['fechafinal'];
    $tabla = 'nota_credito';
    $reportesncd = ControladorReportes::ctrReporteVentasNcdExcel($tabla, $fechaInicial, $fechaFinal);
            foreach ($reportesncd as $kk => $v){ 
          
            echo "<tr>
                
                <td style='border:1px solid #eee;'>".$a.$m."00</td> 
                <td style='border:1px solid #eee;'>".++$k."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['tipocomp']."</td> 
                <td style='border:1px solid #eee;'>".$v['serie']."</td> 
                <td style='border:1px solid #eee;'>".$v['correlativo']."</td> 
                <td style='border:1px solid #eee;'>".$v['codmoneda']."</td> 
                
                <td style='border:1px solid #eee;'>".$v['tipocambio']."</td>
                <td style='border:1px solid #eee;'>-".$v['op_gravadas']."</td> 
                <td style='border:1px solid #eee;'>-".$v['op_exoneradas']."</td> 
                <td style='border:1px solid #eee;'>-".$v['op_inafectas']."</td>  
                <td style='border:1px solid #eee;'>-".$v['igv']."</td> 
                <td style='border:1px solid #eee;'></td> 
                <td style='border:1px solid #eee;'>-".$v['total']."</td>
                <td style='border:1px solid #eee;'>".$v['serie_ref']."</td>
                <td style='border:1px solid #eee;'>".$v['correlativo_ref']."</td>
                <td style='border:1px solid #eee;'>NO</td>";
                if($v['feestado'] == '1'){
                    echo "<td style='border:1px solid #eee;'>0 ACEPTADO POR SUNAT</td>";
                }
                if($v['feestado'] == '2'){
                    echo "<td style='border:1px solid #eee;'>2 RECHAZADO POR SUNAT</td>";
                
                }if($v['feestado'] == ''){
                    echo "<td style='border:1px solid #eee;'>NO ENVIADO A SUNAT</td>";
                }

     
               echo "</tr>";
            
        
    }
    $fechaInicial = $_GET['fechainicial'];
    $fechaFinal = $_GET['fechafinal'];
    $tabla = 'nota_debito';
  
    $reportesncd = ControladorReportes::ctrReporteVentasNcdExcel($tabla, $fechaInicial, $fechaFinal);
            foreach ($reportesncd as $kk => $v){ 
          
             
            echo "<tr>
                
                <td style='border:1px solid #eee;'>".$a.$m."00</td> 
                <td style='border:1px solid #eee;'>".++$k."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['tipocomp']."</td> 
                <td style='border:1px solid #eee;'>".$v['serie']."</td> 
                <td style='border:1px solid #eee;'>".$v['correlativo']."</td> 
                <td style='border:1px solid #eee;'>".$v['codmoneda']."</td> 
                <td style='border:1px solid #eee;'>".$v['tipocambio']."</td>
                <td style='border:1px solid #eee;'>".$v['op_gravadas']."</td> 
                <td style='border:1px solid #eee;'>".$v['op_exoneradas']."</td> 
                <td style='border:1px solid #eee;'>".$v['op_inafectas']."</td>  
                <td style='border:1px solid #eee;'>".$v['igv']."</td> 
                <td style='border:1px solid #eee;'></td> 
                <td style='border:1px solid #eee;'>".$v['total']."</td>
                <td style='border:1px solid #eee;'>".$v['serie_ref']."</td>
                <td style='border:1px solid #eee;'>".$v['correlativo_ref']."</td>
                <td style='border:1px solid #eee;'>NO</td>";
                if($v['feestado'] == '1'){
                    echo "<td style='border:1px solid #eee;'>0 ACEPTADO POR SUNAT</td>";
                }
                if($v['feestado'] == '2'){
                    echo "<td style='border:1px solid #eee;'>2 RECHAZADO POR SUNAT</td>";
                
                }
                if($v['feestado'] == ''){
                    echo "<td style='border:1px solid #eee;'>NO ENVIADO A SUNAT</td>";
                }

     
               echo "</tr>";
            
        
    }


        echo "</table>";
    }                                                                                                
    public static function ctrDescargaReporteComprasExcel(){

        $Name = $_GET["reporte"].'.xls';

        header("Content-type: application/vnd.ms-excel; charset=iso-8859-1"); // Archivo de Excel    
        header('Content-Disposition: attachment; filename="'.$Name.'"');
    
        echo "<table border='0'> 
        
        <tr>
        <td colspan='17' style='text-align:center; font-weight:bold; font-size:28px; background: #42B5F4; padding:8px;'>REPORTE DE COMPRAS</td>
        </tr>
                <tr>
                <td></td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12</td>
                <td>13</td>
                <td>14</td>
                <td>15</td>
                <td>16</td>
                </tr>
                <tr> 
                <td style='font-weight:bold; border:1px solid #eee;'>PERIODO</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>CODIGO UNICO (CUO)</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>FECHA E</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>FECHA V</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>TIPO COMP</td> 
                <td style='font-weight:bold; border:1px solid #eee;'>SERIE</td>
                <td style='font-weight:bold; border:1px solid #eee;'>CORRELATIVO</td>
                <td style='font-weight:bold; border:1px solid #eee;'>MONEDA</td>
                <td style='font-weight:bold; border:1px solid #eee;'>TIPO CAMBIO</td>
                <td style='font-weight:bold; border:1px solid #eee;'>GRAVADAS</td>
                <td style='font-weight:bold; border:1px solid #eee;'>EXONERADAS</td>
                <td style='font-weight:bold; border:1px solid #eee;'>INAFECTAS</td>
                <td style='font-weight:bold; border:1px solid #eee;'>I.G.V.</td>
                <td style='font-weight:bold; border:1px solid #eee;'>NETO</td>		
                <td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>	
                <td style='font-weight:bold; border:1px solid #eee;'>SERIE</td>	
                <td style='font-weight:bold; border:1px solid #eee;'>CORRELATIVO</td>	
                <td style='font-weight:bold; border:1px solid #eee;'>ANULADO</td>	
               
                </tr>";
            $tabla = 'compra';
            $fechaInicial = $_GET['fechainicial'];
            $fechaFinal = $_GET['fechafinal'];
            $reportes = ControladorReportes::ctrReporteComprasExcel($tabla, $fechaInicial, $fechaFinal);
            
            $a =substr($fechaInicial,0 , 4);
            $m = substr($fechaInicial,5, 2);

            foreach ($reportes as $k => $v){ 
          
             
            echo "<tr>
                
                <td style='border:1px solid #eee;'>".$a.$m."00</td> 
                <td style='border:1px solid #eee;'>".++$k."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['fecha_emision']."</td> 
                <td style='border:1px solid #eee;'>".$v['tipocomp']."</td> 
                <td style='border:1px solid #eee;'>".$v['serie']."</td> 
                <td style='border:1px solid #eee;'>".$v['correlativo']."</td> 
                <td style='border:1px solid #eee;'>".$v['codmoneda']."</td> 
                <td style='border:1px solid #eee;'>".$v['tipocambio']."</td>
                ";

                if($v['tipocomp'] == '07'){
               echo "
               <td style='border:1px solid #eee;'>-".$v['op_gravadas']."</td> 
               <td style='border:1px solid #eee;'>-".$v['op_exoneradas']."</td> 
               <td style='border:1px solid #eee;'>-".$v['op_inafectas']."</td> 
               <td style='border:1px solid #eee;'>-".$v['igv']."</td> 
                <td style='border:1px solid #eee;'>-".$v['subtotal']."</td> 
                <td style='border:1px solid #eee;'>-".$v['total']."</td>
                <td style='border:1px solid #eee;'>".$v['serie_ref']."</td>
                <td style='border:1px solid #eee;'>".$v['correlativo_ref']."</td>";
                }
                else if($v['anulado'] == 's'){
               echo "
               <td style='border:1px solid #eee;'>0</td> 
               <td style='border:1px solid #eee;'>0</td> 
               <td style='border:1px solid #eee;'>0</td> 
               <td style='border:1px solid #eee;'>0</td> 
                <td style='border:1px solid #eee;'>0</td> 
                <td style='border:1px solid #eee;'>0</td>
                <td style='border:1px solid #eee;'>".$v['serie_ref']."</td>
                <td style='border:1px solid #eee;'>".$v['correlativo_ref']."</td>";
                }
                else  if($v['tipocomp'] == '08'){
               echo "
               <td style='border:1px solid #eee;'>".$v['op_gravadas']."</td> 
               <td style='border:1px solid #eee;'>".$v['op_exoneradas']."</td> 
               <td style='border:1px solid #eee;'>".$v['op_inafectas']."</td> 
               <td style='border:1px solid #eee;'>".$v['igv']."</td> 
                <td style='border:1px solid #eee;'>".$v['subtotal']."</td> 
                <td style='border:1px solid #eee;'>".$v['total']."</td>
                <td style='border:1px solid #eee;'>".$v['serie_ref']."</td>
                <td style='border:1px solid #eee;'>".$v['correlativo_ref']."</td>";
                }
                else{
              echo "
              <td style='border:1px solid #eee;'>".$v['op_gravadas']."</td> 
              <td style='border:1px solid #eee;'>".$v['op_exoneradas']."</td> 
              <td style='border:1px solid #eee;'>".$v['op_inafectas']."</td> 
                <td style='border:1px solid #eee;'>".$v['igv']."</td> 
                <td style='border:1px solid #eee;'>".$v['subtotal']."</td> 
                <td style='border:1px solid #eee;'>".$v['total']."</td>
                <td style='border:1px solid #eee;'></td>
                <td style='border:1px solid #eee;'></td>";
                }
                
                if($v['anulado'] == 'n'){
                    echo "<td style='border:1px solid #eee;'>NO</td>";
                }else{
                    echo "<td style='border:1px solid #eee;'>SI</td>";
                }
                

            echo "</tr>";
            
        
    }
   
  
        echo "</table>";
    }                                                                                                

    public static function ctrReporteVentasPDF($tabla, $fechaInicial, $fechaFinal, $tipocomp){
        if($tipocomp == '00'){
            $valor = "(tipocomp='01' || tipocomp='03') AND";
        }
        else{

            $valor = 'tipocomp = "'.$tipocomp.'" AND';
        }
        $resultado = ModeloReportes::mdlReporteVentasPDF($tabla, $fechaInicial, $fechaFinal, $valor);
        
        return $resultado;

    }
    public static function ctrReporteComprasPDF($tabla, $fechaInicial, $fechaFinal, $tipocomp){
        if($tipocomp == '00'){
            $valor = "(tipocomp='01' || tipocomp='03') AND";
        }
        else{

            $valor = 'tipocomp = "'.$tipocomp.'" AND';
        }
        $resultado = ModeloReportes::mdlReporteVentasPDF($tabla, $fechaInicial, $fechaFinal, $valor);
        
        return $resultado;

    }

    public static function ctrMostrarNCD($tabla, $item, $valor){

        $respuesta  = ModeloReportes::mdlMostrarNotasCD($tabla, $item, $valor);
        return $respuesta;
    }
}
