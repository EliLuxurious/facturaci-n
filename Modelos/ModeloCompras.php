<?php
namespace Modelos;
use Conect\Conexion;
use PDO;

class ModeloCompras{

    public static function mdlMostrarCompras($tabla, $item, $valor){
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

    // MOSTRAR DETALLES DE VENTA
    public static function mdlMostrarDetallesCompras($tabla, $item, $valor){       

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchall();

        
        
        $stmt->close();
        $stmt = null;
    }
    // GUARDAR DETALLES VENTA CARRITO EN LA BD
public static function mdlInsertarDetalles($idcompra, $detalle){

    $stmt = Conexion::conectar()->prepare("INSERT INTO compra_detalle(idcompra, item, cod_producto, descripcion, codigo_afectacion,  cantidad, valor_unitario, precio_unitario, icbper, igv, descuento, valor_total, importe_total)
    VALUES (:idcompra, :item, :cod_producto, :descripcion, :codigo_afectacion, :cantidad, :valor_unitario, :precio_unitario, :icbper, :igv, :descuento, :valor_total, :importe_total)");
foreach($detalle as $k => $v){
    $stmt->bindParam(":idcompra", $idcompra, PDO::PARAM_INT);
    $stmt->bindParam(":item", $v['item'], PDO::PARAM_INT);
    $stmt->bindParam(":cod_producto", $v['codigo'], PDO::PARAM_STR);
    $stmt->bindParam(":descripcion", $v['descripcion'], PDO::PARAM_STR);
    $stmt->bindParam(":codigo_afectacion", $v['codigo_afectacion'], PDO::PARAM_STR);
    // $stmt->bindParam(":tipo_precio", $v['tipo_precio'], PDO::PARAM_STR);
    $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_STR);
    $stmt->bindParam(":valor_unitario", $v['valor_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":precio_unitario", $v['precio_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":icbper", $v['icbper'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $v['igv'], PDO::PARAM_STR);
    // $stmt->bindParam(":porcentaje_igv", $v['porcentaje_igv'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento", $v['descuento'], PDO::PARAM_STR);
    // $stmt->bindParam(":descuento_factor", $v['descuentos']['factor'], PDO::PARAM_STR);
    $stmt->bindParam(":valor_total", $v['valor_total'], PDO::PARAM_STR);
    $stmt->bindParam(":importe_total", $v['importe_total'], PDO::PARAM_STR);

   $stmt->execute();
}   

}
    // GUARDAR COMPRA CARRITO EN LA BD
    public static function mdlInsertarCompra($comprobante){

    $stmt = Conexion::conectar()->prepare("INSERT INTO compra(id, tipocomp,  serie, correlativo, serie_correlativo, fecha_emision, fechahora, codmoneda, metodopago, comentario, op_gravadas, op_exoneradas, op_inafectas, op_gratuitas,  descuento, icbper, igv, subtotal, total, codproveedor, tipodoc, tipocomp_ref, serie_ref, correlativo_ref, codmotivo, fechamodificado)
    VALUES (NULL, :tipocomp, :serie, :correlativo, :serie_correlativo, :fecha_emision, :fechahora, :codmoneda, :metodopago, :comentario, :op_gravadas, :op_exoneradas, :op_inafectas, :op_gratuitas, :descuento, :icbper, :igv, :subtotal, :total, :codproveedor, :tipodoc, :tipocomp_ref, :serie_ref, :correlativo_ref, :codmotivo, :fechamodificado)");
$fechaini = $comprobante["fechadoc"];
$fechaini2 = str_replace('/', '-', $fechaini);
$fechaDoc = date('Y-m-d', strtotime($fechaini2));
$fechainim = $comprobante["fechamodificado"];
$fechainim2 = str_replace('/', '-', $fechainim);
$fechamodificado = date('Y-m-d', strtotime($fechainim2));
$fechahora = date("Y-m-d H:i:s");
    $stmt->bindParam(":tipocomp", $comprobante['tipocomp'], PDO::PARAM_STR);
    // $stmt->bindParam(":idserie", $comprobante['idserie'], PDO::PARAM_INT);
    $stmt->bindParam(":serie", $comprobante['serie'], PDO::PARAM_STR);
    $stmt->bindParam(":correlativo", $comprobante['correlativo'], PDO::PARAM_INT);
    $stmt->bindParam(":serie_correlativo", $comprobante['serie_correlativo'], PDO::PARAM_STR);
    $stmt->bindParam(":fecha_emision", $fechaDoc, PDO::PARAM_STR);
    $stmt->bindParam(":fechahora", $fechahora, PDO::PARAM_STR);
    $stmt->bindParam(":codmoneda", $comprobante['moneda'], PDO::PARAM_STR);
    $stmt->bindParam(":metodopago", $comprobante['metodopago'], PDO::PARAM_STR);
    $stmt->bindParam(":comentario", $comprobante['comentario'], PDO::PARAM_STR);
    $stmt->bindParam(":op_gravadas", $comprobante['op_gravadas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_exoneradas", $comprobante['op_exoneradas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_inafectas", $comprobante['op_inafectas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_gratuitas", $comprobante['op_gratuitas'], PDO::PARAM_STR);
    // $stmt->bindParam(":descuento_factor", $comprobante['descuento_factor'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento", $comprobante['descuento'], PDO::PARAM_STR);
    $stmt->bindParam(":icbper", $comprobante['icbper'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $comprobante['igv'], PDO::PARAM_STR);
    $stmt->bindParam(":subtotal", $comprobante['subtotal'], PDO::PARAM_STR);
    $stmt->bindParam(":total", $comprobante['total'], PDO::PARAM_STR);
    $stmt->bindParam(":codproveedor", $comprobante['codproveedor'], PDO::PARAM_INT);
    // $stmt->bindParam(":codvendedor", $comprobante['codvendedor'], PDO::PARAM_INT);
    $stmt->bindParam(":tipodoc", $comprobante['tipodoc'], PDO::PARAM_INT);
    $stmt->bindParam(":tipocomp_ref", $comprobante['tipocomp_ref'], PDO::PARAM_STR);
    $stmt->bindParam(":serie_ref", $comprobante['serie_ref'], PDO::PARAM_STR);
    $stmt->bindParam(":correlativo_ref", $comprobante['correlativo_ref'], PDO::PARAM_STR);
    $stmt->bindParam(":codmotivo", $comprobante['codmotivo'], PDO::PARAM_STR);
    $stmt->bindParam(":fechamodificado", $fechamodificado, PDO::PARAM_STR);


   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}

public static function mdlAnularCompra($idcompra, $anulado){
    $stmt = Conexion::conectar()->prepare("UPDATE compra SET anulado=:anulado WHERE id=:id");


    $stmt->bindParam(":id", $idcompra, PDO::PARAM_INT);
    $stmt->bindParam(":anulado", $anulado, PDO::PARAM_STR);
    
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
    $stmt = Conexion::conectar()->prepare("SELECT * FROM compra ORDER BY id DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}

// BUSCAR PRODUCTO
public static function mdlBuscarProducto($tabla, $valor){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE codigo LIKE :valor OR descripcion LIKE :valor OR serie LIKE :valor LIMIT 10");
    $parametros = array(':valor'=>'%'.$valor.'%');
    
            $stmt->execute($parametros);
            return $stmt->fetchall();
}
}