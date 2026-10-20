<?php
namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;

   class ModeloNotaDebito{
        // MOSTRAR NOTA DE CRÉDITO
        public static function mdlMostrarNotaDebito($tabla, $item, $valor){
            if($item != null){
    
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
                $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch();
    
            }else{
    
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
                $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll();
            }
            
            $stmt->close();
            $stmt = null;
        }
    // MOSTRAR DETALLES NOTA DE CRÉDITO Y PRODUCTOS
    public static function mdlDetallesNotaDebitoProductos($item, $valor){
       

            $stmt = Conexion::conectar()->prepare("SELECT t1.cantidad, t2.id, t2.descripcion, t1.valor_unitario, t1.valor_total, t1.importe_total, t1.descuento  FROM nota_debito_detalle t1 INNER JOIN productos t2 ON t1.idproducto=t2.codigo WHERE $item=:$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchall();

        
        
        $stmt->close();
        $stmt = null;
    }

    // GUARDAR DETALLES NOTA DE CRÉDITO EN LA BD
    public static function mdlInsertarDetallesNotaDebito($idnd, $detalle){

        $stmt = Conexion::conectar()->prepare("INSERT INTO nota_debito_detalle(id,idnd, item, idproducto, cantidad, valor_unitario, precio_unitario, igv, porcentaje_igv, valor_total, importe_total)
        VALUES (NULL, :idnd, :item, :idproducto, :cantidad, :valor_unitario, :precio_unitario, :igv, :porcentaje_igv, :valor_total, :importe_total)");

    foreach($detalle as $k => $v){
        $stmt->bindParam(":idnd", $idnd, PDO::PARAM_INT);
        $stmt->bindParam(":item", $v['item'], PDO::PARAM_INT);
        $stmt->bindParam(":idproducto", $v['codigo'], PDO::PARAM_INT);
        $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_STR);
        $stmt->bindParam(":valor_unitario", $v['valor_unitario'], PDO::PARAM_STR);
        $stmt->bindParam(":precio_unitario", $v['precio_unitario'], PDO::PARAM_STR);
        $stmt->bindParam(":igv", $v['igv'], PDO::PARAM_STR);
        $stmt->bindParam(":porcentaje_igv", $v['porcentaje_igv'], PDO::PARAM_STR);
        $stmt->bindParam(":valor_total", $v['valor_total'], PDO::PARAM_STR);
        $stmt->bindParam(":importe_total", $v['importe_total'], PDO::PARAM_STR);
    
       $stmt->execute();
    }  
}
    // GUARDAR NOTA DE CRÉDITO LA BD
public static function mdlInsertarNotaDebito($idemisor, $comprobante,$codigosSunat){

    $stmt = Conexion::conectar()->prepare("INSERT INTO nota_debito(id, idemisor, tipocomp, idserie, serie, correlativo, fecha_emision, codmoneda, tipocambio, op_gravadas, op_exoneradas, op_inafectas, descuento, igv, total, codcliente, tipocomp_ref, serie_ref, correlativo_ref, seriecorrelativo_ref, codmotivo, feestado, fecodigoerror, femensajesunat)
    VALUES (NULL, :idemisor, :tipocomp, :idserie, :serie, :correlativo, :fecha_emision, :codmoneda, :tipocambio, :op_gravadas, :op_exoneradas, :op_inafectas, :descuento, :igv, :total, :codcliente, :tipocomp_ref, :serie_ref, :correlativo_ref,:seriecorrelativo_ref, :codmotivo, :feestado, :fecodigoerror, :femensajesunat)");
$fechaDoc = date("Y-m-d");
$fechahora = date("Y-m-d H:i:s");
    $stmt->bindParam(":idemisor", $idemisor, PDO::PARAM_INT);
    $stmt->bindParam(":tipocomp", $comprobante['tipodoc'], PDO::PARAM_STR);
    $stmt->bindParam(":idserie", $comprobante['idserie'], PDO::PARAM_INT);
    $stmt->bindParam(":serie", $comprobante['serie'], PDO::PARAM_STR);
    $stmt->bindParam(":correlativo", $comprobante['correlativo'], PDO::PARAM_INT);
    $stmt->bindParam(":fecha_emision", $fechaDoc, PDO::PARAM_STR);
    $stmt->bindParam(":codmoneda", $comprobante['moneda'], PDO::PARAM_STR);
    $stmt->bindParam(":tipocambio", $comprobante['tipocambio'], PDO::PARAM_STR);
    $stmt->bindParam(":op_gravadas", $comprobante['total_opgravadas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_exoneradas", $comprobante['total_opexoneradas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_inafectas", $comprobante['total_opinafectas'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento", $comprobante['descuento'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $comprobante['igv'], PDO::PARAM_STR);
    $stmt->bindParam(":total", $comprobante['total'], PDO::PARAM_STR);
    $stmt->bindParam(":codcliente", $comprobante['codcliente'], PDO::PARAM_INT);
    $stmt->bindParam(":tipocomp_ref", $comprobante['tipocomp_ref'], PDO::PARAM_STR);
    $stmt->bindParam(":serie_ref", $comprobante['serie_ref'], PDO::PARAM_STR);
    $stmt->bindParam(":correlativo_ref", $comprobante['correlativo_ref'], PDO::PARAM_INT);
    $stmt->bindParam(":seriecorrelativo_ref", $comprobante['seriecorrelativo_ref'], PDO::PARAM_STR);
    $stmt->bindParam(":codmotivo", $comprobante['codmotivo'], PDO::PARAM_STR);
    $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
    $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
    $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
 
    // $stmt->bindParam(":tipodoc", $comprobante['codigo_doc_cliente'], PDO::PARAM_INT);

   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}

// OBTENER EL ULTIMO ID NOTA DE CRÉDITO
public static function mdlObtenerUltimaNotaDebitoId(){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM nota_debito ORDER BY id DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}

public static function mdlActualizarVentaND($item, $valor, $valor2 = 'n'){
    $stmt = Conexion::conectar()->prepare("UPDATE venta SET id_nd=:id_nd, resumen = :resumen WHERE id=:id");

    $stmt->bindParam(":id", $item, PDO::PARAM_INT);
    $stmt->bindParam(":id_nd", $valor, PDO::PARAM_INT);
    $stmt->bindParam(":resumen", $valor2, PDO::PARAM_STR);
    // $stmt->bindParam(":idbaja", $datos['idbaja'], PDO::PARAM_INT);

    $stmt->execute();
   }
}