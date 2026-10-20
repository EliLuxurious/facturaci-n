<?php
namespace Modelos;
use Conect\Conexion;
use PDO;

class ModeloGuiaRemision{
    // MOSTRAR TIPOS DE DOCUMENTOS DE IDENTIDAD
public static function mdlMostrar($tabla, $item, $valor){

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
}
public static function mdlBuscarSerieCorrelativo($tabla, $valor){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE serie_correlativo LIKE :valor LIMIT 10");
    $parametros = array(':valor'=>'%'.$valor.'%');
    
            $stmt->execute($parametros);
            return $stmt->fetchall();
}

public static function mdlMostrarTraslado($tabla, $item, $valor){

    if($item != null){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY codigo DESC");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch();

    }else{
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
        $stmt->execute();
        return $stmt->fetchall();

    }
}
public static function mdlMostrarUbigeo($item, $valor){
    $stmt = Conexion::conectar()->prepare("SELECT de.name, pro.nombre_provincia, dis.nombre_distrito, dis.id AS ubigeo FROM ubigeo_departamento de INNER JOIN ubigeo_provincia pro ON de.id = pro.department_id INNER JOIN ubigeo_distrito dis ON pro.id = dis.province_id WHERE $item LIKE :$item LIMIT 15");

    // $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
    $parametros = array(':'.$item => '%'.$valor.'%');
    if($stmt->execute($parametros)){
    return $stmt->fetchall();
    }else{
        return 'error';
    }

}
public static function mdlMostrarUbigeoSolo($item, $valor){
    $stmt = Conexion::conectar()->prepare("SELECT de.name, pro.nombre_provincia, dis.nombre_distrito, dis.id FROM ubigeo_departamento de INNER JOIN ubigeo_provincia pro ON de.id = pro.department_id INNER JOIN ubigeo_distrito dis ON pro.id = dis.province_id WHERE dis.id = :$item");

    $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetch();
   
}
// OBTENER EL ULTIMO ID COMPROBANTE
public static function mdlObtenerUltimoComprobanteIdGuia(){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM guia ORDER BY id DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}
    // GUARDAR DETALLES VENTA CARRITO EN LA BD
    public static function mdlInsertarDetallesGuia($idGuia, $detalle){

        $stmt = Conexion::conectar()->prepare("INSERT INTO guia_detalle(id_guia, indexg, id_producto, codSunat, cantidad)
        VALUES (:id_guia, :indexg, :id_producto, :codSunat, :cantidad)");
    foreach($detalle as $k => $v){
        $stmt->bindParam(":id_guia", $idGuia, PDO::PARAM_INT);
        $stmt->bindParam(":indexg", $v['index'], PDO::PARAM_INT);
        $stmt->bindParam(":id_producto", $v['id_producto'], PDO::PARAM_INT);
        $stmt->bindParam(":codSunat", $v['codProdSunat'], PDO::PARAM_STR);
        $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_STR);

        $stmt->execute();

    }
}
public static function mdlGuardarGuia($datosGuia, $codigosSunat){
    $stmt= Conexion::conectar()->prepare("INSERT INTO guia(id, id_cliente, cli_tipodoc, tipodoc, serie, correlativo, fecha_emision, hora, comp_ref, baja_numdoc, baja_tipodoc, rel_numdoc, rel_tipodoc, terceros_tipodoc, terceros_numdoc, terceros_nombrerazon, cod_traslado, uniPeso, pesoTotal, numBultos, indTransbordo, modTraslado, fechaTraslado, transp_tipoDoc, transp_numDoc, transp_nombreRazon, transp_placa, tipoDocChofer, numDocChofer, observacion, ubigeoPartida, direccionPartida, ubigeoLlegada, direccionLlegada, feestado, fecodigoerror, femensajesunat, nombrexml, xmlbase64, cdrbase64, tipovehiculo) VALUES (NULL, :id_cliente, :cli_tipodoc, :tipodoc, :serie, :correlativo, :fecha_emision, :hora, :comp_ref, :baja_numdoc, :baja_tipodoc, :rel_numdoc, :rel_tipodoc, :terceros_tipodoc, :terceros_numdoc, :terceros_nombrerazon, :cod_traslado, :uniPeso, :pesoTotal, :numBultos, :indTransbordo, :modTraslado, :fechaTraslado, :transp_tipoDoc, :transp_numDoc, :transp_nombreRazon, :transp_placa, :tipoDocChofer, :numDocChofer, :observacion, :ubigeoPartida, :direccionPartida, :ubigeoLlegada, :direccionLlegada, :feestado, :fecodigoerror, :femensajesunat, :nombrexml, :xmlbase64, :cdrbase64, :tipovehiculo)"); 

    $stmt->bindParam(":id_cliente", $datosGuia['id_cliente'], PDO::PARAM_INT);
    $stmt->bindParam(":cli_tipodoc", $datosGuia['destinatario']['tipoDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":tipodoc", $datosGuia['guia']['tipoDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":serie", $datosGuia['guia']['serie'], PDO::PARAM_STR);
    $stmt->bindParam(":correlativo", $datosGuia['guia']['correlativo'], PDO::PARAM_INT);
    $stmt->bindParam(":fecha_emision", $datosGuia['guia']['fechaEmision'], PDO::PARAM_STR);
    $stmt->bindParam(":fecha_emision", $datosGuia['guia']['fechaEmision'], PDO::PARAM_STR);
    $stmt->bindParam(":hora", $datosGuia['guia']['horaEmision'], PDO::PARAM_STR);
    $stmt->bindParam(":comp_ref", $datosGuia['comp_ref'], PDO::PARAM_STR);
    $stmt->bindParam(":baja_numdoc", $datosGuia['docBaja']['nroDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":baja_tipodoc", $datosGuia['docBaja']['tipoDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":rel_numdoc", $datosGuia['relDoc']['nroDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":rel_tipodoc", $datosGuia['relDoc']['tipoDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":terceros_tipodoc", $datosGuia['terceros']['tipoDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":terceros_numdoc", $datosGuia['terceros']['numDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":terceros_nombrerazon", $datosGuia['terceros']['nombreRazon'], PDO::PARAM_STR);
    $stmt->bindParam(":cod_traslado", $datosGuia['datosEnvio']['codTraslado'], PDO::PARAM_STR);
    $stmt->bindParam(":uniPeso", $datosGuia['datosEnvio']['uniPesoTotal'], PDO::PARAM_STR);
    $stmt->bindParam(":pesoTotal", $datosGuia['datosEnvio']['pesoTotal'], PDO::PARAM_STR);
    $stmt->bindParam(":numBultos", $datosGuia['datosEnvio']['numBultos'], PDO::PARAM_STR);
    $stmt->bindParam(":indTransbordo", $datosGuia['datosEnvio']['indTransbordo'], PDO::PARAM_STR);
    $stmt->bindParam(":modTraslado", $datosGuia['datosEnvio']['modTraslado'], PDO::PARAM_STR);
    $stmt->bindParam(":fechaTraslado", $datosGuia['datosEnvio']['fechaTraslado'], PDO::PARAM_STR);
    $stmt->bindParam(":transp_tipoDoc", $datosGuia['transportista']['tipoDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":transp_numDoc", $datosGuia['transportista']['numDoc'], PDO::PARAM_STR);
    $stmt->bindParam(":transp_nombreRazon", $datosGuia['transportista']['nombreRazon'], PDO::PARAM_STR);
    $stmt->bindParam(":transp_placa", $datosGuia['transportista']['placa'], PDO::PARAM_STR);
    $stmt->bindParam(":tipoDocChofer", $datosGuia['transportista']['tipoDocChofer'], PDO::PARAM_STR);
    $stmt->bindParam(":numDocChofer", $datosGuia['transportista']['numDocChofer'], PDO::PARAM_STR);
    $stmt->bindParam(":ubigeoPartida", $datosGuia['partida']['ubigeo'], PDO::PARAM_STR);
    $stmt->bindParam(":direccionPartida", $datosGuia['partida']['direccion'], PDO::PARAM_STR);
    $stmt->bindParam(":ubigeoLlegada", $datosGuia['llegada']['ubigeo'], PDO::PARAM_STR);
    $stmt->bindParam(":direccionLlegada", $datosGuia['llegada']['direccion'], PDO::PARAM_STR);
    $stmt->bindParam(":observacion", $datosGuia['guia']['observacion'], PDO::PARAM_STR);   
    $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
    $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
    $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
    $stmt->bindParam(":nombrexml", $codigosSunat['nombrexml'], PDO::PARAM_STR);
    $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
    $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);  
    $stmt->bindParam(":tipovehiculo", $datosGuia['datosEnvio']['tipoVehiculo'], PDO::PARAM_STR);  
   
    if($stmt->execute()){
    return   'ok';
} else {
   return  'error';
} 

$stmt->close();
$stmt = null;
}

  // MOSTRAR DETALLES Y PRODUCTOS DE LA GUIA
  public static function mdlMostrarDetallesProductosGuia($item, $valor){
       

    $stmt = Conexion::conectar()->prepare("SELECT t1.id_producto, t1.cantidad, t2.descripcion, t2.id, t2.codunidad, t2.codigo, t2.id  FROM guia_detalle t1 INNER JOIN productos t2 ON t1.id_producto=t2.id  WHERE $item=:$item");
    $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchall();



$stmt->close();
$stmt = null;
}
public static function mdlListarGuias(){
    $content =  "<tbody class='body-listaguias'></tbody>";
    return $content;
}
}