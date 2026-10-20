<?php
 namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;
class ModeloReportes{

    public static function mdlSumaComprobantes($tabla, $where){

        $stmt = Conexion::conectar()->prepare("SELECT SUM(total) AS total FROM $tabla $where");    
        $stmt->execute();
         return $stmt->fetch();
    }     
    
    public static function mdlReportes(){
        $content =  "<tbody class='body-reporte-ventas'></tbody>";
    return $content;
    }
    public static function mdlReportesCompras(){
        $content =  "<tbody class='body-reporte-compras'></tbody>";
    return $content;
    }

    public static function mdlReporteVentasDashboard($tabla, $fechaInicial, $fechaFinal){
        if($fechaInicial != null){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE (anulado = 'n' AND feestado != 2) AND tipocomp != '02' AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'");    
        $stmt->execute();
         return $stmt->fetchall();  
        
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE (anulado = 'n' AND feestado != 2) AND tipocomp != '02'");    
        $stmt->execute();
         return $stmt->fetchall();  
        }
    }
     public static function mdlReporteVentasExcel($tabla, $fechaInicial, $fechaFinal){
        if($fechaInicial != null){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE  tipocomp != '02' AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'");    
        $stmt->execute();
         return $stmt->fetchall();  
        
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE  tipocomp != '02' ");    
        $stmt->execute();
         return $stmt->fetchall();  
        }
    }
     public static function mdlReporteComprasExcel($tabla, $fechaInicial, $fechaFinal){
        if($fechaInicial != null){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE   fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'");    
        $stmt->execute();
         return $stmt->fetchall();  
        
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");    
        $stmt->execute();
         return $stmt->fetchall();  
        }
    }
    public static function mdlReporteVentasNcdExcel($tabla, $fechaInicial, $fechaFinal){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'");    
        $stmt->execute();
         return $stmt->fetchall();  
    }

    public static function mdlReporteVentasPDF($tabla, $fechaInicial, $fechaFinal, $valor){
            if($fechaInicial != null){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $valor fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'");    
        $stmt->execute();
         return $stmt->fetchall();  
        
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE");    
        $stmt->execute();
         return $stmt->fetchall();  
        }
    }
   // MOSTRAR NOTAS CD
   public static function mdlMostrarNotasCD($tabla, $item, $valor){
        
    if($item != null){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch();

    }else{
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
        $stmt->execute();
        return $stmt->fetchall();

    }
   

    $stmt->close();
    $stmt = null;
}
}