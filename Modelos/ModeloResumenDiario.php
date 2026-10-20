<?php
namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;
class ModeloResumenDiario{
    // OBTENER COMPROBANTES PARA RESÚMEN
    public static function mdlMostrarComprobantes($fecha, $tipocomp, $estado){
$resumen = 'n';
    $stmt = Conexion::conectar()->prepare("SELECT * FROM venta WHERE fecha_emision=:fecha_emision AND tipocomp=:tipocomp AND feestado=:feestado AND resumen=:resumen LIMIT 500");
    $stmt->bindParam(":fecha_emision", $fecha, PDO::PARAM_STR);
    $stmt->bindParam(":tipocomp", $tipocomp, PDO::PARAM_STR);
    $stmt->bindParam(":feestado", $estado, PDO::PARAM_STR);
    $stmt->bindParam(":resumen", $resumen, PDO::PARAM_STR);
    if($stmt->execute()){

    return $stmt->fetchall();

    }else{
        return 'error';
    }
}
// OBTENER EL ULTIMO ID COMPROBANTE
public static function mdlObtenerUltimoComprobanteId(){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM venta ORDER BY id DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}

public static function mdlInsertarDetallesResumen($idEnvio, $items){
    $stmt = Conexion::conectar()->prepare("INSERT INTO envio_resumen_detalle(idenvio, idventa, condicion)VALUES(:idenvio, :idventa, :condicion)");

    foreach($items as $k => $value){

    $stmt->bindParam(":idenvio", $idEnvio, PDO::PARAM_INT);
    $stmt->bindParam(":idventa", $value['id'], PDO::PARAM_INT);
    $stmt->bindParam(":condicion", $value['condicion'], PDO::PARAM_INT);

    $stmt->execute();
    }
}

public static function mdlInsertarResumen($cabecera, $codigosSunat){
    $stmt = Conexion::conectar()->prepare("INSERT INTO envio_resumen(idemisor, fecha_envio, fecha_emision, correlativo, resumen, baja, nombrexml, feestado, fecodigoerror, femensajesunat, ticket)VALUES (:idemisor, :fecha_envio, :fecha_emision, :correlativo, :resumen, :baja, :nombrexml, :feestado, :fecodigoerror, :femensajesunat, :ticket)");

    $stmt->bindParam(":idemisor", $cabecera['idemisor'], PDO::PARAM_STR);
    $stmt->bindParam(":fecha_envio", $cabecera['fecha_envio'], PDO::PARAM_STR);
    $stmt->bindParam(":fecha_emision", $cabecera['fecha_emision'], PDO::PARAM_STR);
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

public static function mdlActualizarResumen($cabecera, $codigosSunat){
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
public static function mdlActualizarVentaResumen($items, $datos){
    $stmt = Conexion::conectar()->prepare("UPDATE venta SET resumen=:resumen WHERE id=:id");
foreach($items as $k => $v){
    $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
    $stmt->bindParam(":resumen", $datos['resumen'], PDO::PARAM_STR);
    // $stmt->bindParam(":idbaja", $datos['idbaja'], PDO::PARAM_INT);

    $stmt->execute();
   } 


}
// OBTENER EL ULTIMO ID EMVIOBAJA
public static function mdlObtenerUltimoResumenId(){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM envio_resumen ORDER BY idenvio DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}
// EN EL TBODY SE CARGAN TODOS LOS RESÚMENES DIARIOS MEDIANTE AJAX
public static function mdlMostrarResumenes(){

    $content =  "<tbody class='body-resumenes'></tbody>";
    return $content;

    }
// EN EL TBODY SE CARGAN TODOS LAS BOLETAS DE RESUMENES DIARIOS MEDIANTE AJAX
public static function mdlMostrarBoletasResumenes(){

    $content =  '<tbody class="resultado-ver-boletas"></tbody>';
    return $content;

    }
// public static function mdlMostrarDetallesResumenenes($tabla, $idenvio){
//     $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE idenvio=:idenvio");
//     $stmt->bindParam(":idenvio", $idenvio, PDO::PARAM_INT);

//     $stmt->execute();

//     return $stmt->fetchall();

// }

          

    }
