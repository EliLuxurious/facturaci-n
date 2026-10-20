<?php
namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;

class ModeloEnvioSunat{

    // GUARDAR VENTA CARRITO EN LA BD
public static function mdlActualizarVenta($idVenta,$codigosSunat){

    $stmt = Conexion::conectar()->prepare("UPDATE venta SET feestado=:feestado, fecodigoerror=:fecodigoerror, femensajesunat=:femensajesunat, nombrexml=:nombrexml, xmlbase64=:xmlbase64, cdrbase64=:cdrbase64 WHERE id=:id");
$fechaDoc = date("Y-m-d");
$fechahora = date("Y-m-d H:i:s");
    $stmt->bindParam(":id", $idVenta, PDO::PARAM_INT);
    $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
    $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
    $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
    $stmt->bindParam(":nombrexml", $codigosSunat['nombrexml'], PDO::PARAM_STR);
    $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
    $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);
  

   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}

// OBTENER EL ULTIMO ID COMPROBANTE
public static function mdlObtenerUltimoComprobanteId(){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM venta ORDER BY id DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}

public static function mdlInsertarDetallesResumenBaja($idEnvioBaja, $items){
    $stmt = Conexion::conectar()->prepare("INSERT INTO envio_resumen_detalle(idenvio, idventa, condicion)VALUES(:idenvio, :idventa, :condicion)");

    foreach($items as $k => $value){

    $stmt->bindParam(":idenvio", $idEnvioBaja, PDO::PARAM_INT);
    $stmt->bindParam(":idventa", $value['idventa'], PDO::PARAM_INT);
    $stmt->bindParam(":condicion", $value['baja'], PDO::PARAM_INT);

    $stmt->execute();
    }
}

public static function mdlInsertarResumenBaja($cabecera, $codigosSunat){
    $stmt = Conexion::conectar()->prepare("INSERT INTO envio_resumen(idemisor, fecha_envio, correlativo, resumen, baja, nombrexml, feestado, fecodigoerror, femensajesunat, ticket)VALUES (:idemisor, :fecha_envio, :correlativo, :resumen, :baja, :nombrexml, :feestado, :fecodigoerror, :femensajesunat, :ticket)");

    $stmt->bindParam(":idemisor", $cabecera['idemisor'], PDO::PARAM_STR);
    $stmt->bindParam(":fecha_envio", $cabecera['fecha_envio'], PDO::PARAM_STR);
    $stmt->bindParam(":correlativo", $cabecera['correlativo'], PDO::PARAM_STR);
    $stmt->bindParam(":resumen", $cabecera['resumen'], PDO::PARAM_STR);
    $stmt->bindParam(":baja", $cabecera['baja'], PDO::PARAM_INT);
    $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
    $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
    $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
    $stmt->bindParam(":nombrexml", $codigosSunat['nombrexml'], PDO::PARAM_STR);
    $stmt->bindParam(":ticket", $codigosSunat['ticket'], PDO::PARAM_STR);
    // $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
    // $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);

    if($stmt->execute()){
        return   'ok';
   } else {
       return  'error';
   } 

   $stmt->close();
   $stmt = null;
}
public static function mdlActualizarResumenBaja($cabecera, $codigosSunat){
    $stmt = Conexion::conectar()->prepare("UPDATE envio_resumen SET fecha_envio=:fecha_envio, nombrexml=:nombrexml, feestado=:feestado, fecodigoerror=:fecodigoerror, femensajesunat=:femensajesunat, ticket=:ticket");


    $stmt->bindParam(":fecha_envio", $cabecera['fecha_envio'], PDO::PARAM_STR);
    $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
    $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
    $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
    $stmt->bindParam(":nombrexml", $codigosSunat['nombrexml'], PDO::PARAM_STR);
    $stmt->bindParam(":ticket", $codigosSunat['ticket'], PDO::PARAM_STR);
    // $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
    // $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);

    if($stmt->execute()){
        return   'ok';
   } else {
       return  'error';
   } 

   $stmt->close();
   $stmt = null;
}
public static function mdlActualizarVentaBaja($idVenta, $datos){
    $stmt = Conexion::conectar()->prepare("UPDATE venta SET anulado=:anulado, idbaja=:idbaja WHERE id=:id");


    $stmt->bindParam(":id", $idVenta, PDO::PARAM_INT);
    $stmt->bindParam(":anulado", $datos['anulado'], PDO::PARAM_STR);
    $stmt->bindParam(":idbaja", $datos['idbaja'], PDO::PARAM_INT);

    if($stmt->execute()){
        return   'ok';
   } else {
       return  'error';
   } 

   $stmt->close();
   $stmt = null;
}
// OBTENER EL ULTIMO ID EMVIOBAJA
public static function mdlObtenerUltimoResumenBajaId(){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM envio_resumen ORDER BY idenvio DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}
    // MOSTRAR VENTAS BAJAS
    public static function mdlMostrarBajas($tabla, $item, $valor){
        if($item != null){
            
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchall();
            
        }
        
        $stmt->close();
        $stmt = null;
    }
    // MOSTRAR VENTAS BAJA
    public static function mdlMostrarBaja($tabla, $item, $valor){
        if($item != null){
            
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
            
        }
        
        $stmt->close();
        $stmt = null;
    }
}