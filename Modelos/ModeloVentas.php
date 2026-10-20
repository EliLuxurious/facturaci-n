<?php
namespace Modelos;
use Conect\Conexion;
use PDO;

class ModeloVentas{
    // MOSTRAR VENTAS
    public static function mdlMostrarVentas($tabla, $item, $valor){
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
    public static function mdlMostrarDetalles($tabla, $item, $valor){
       

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchall();

        
        
        $stmt->close();
        $stmt = null;
    }
    // MOSTRAR DETALLES Y PRODUCTOS DE VENTA
    public static function mdlMostrarDetallesProductos($item, $valor){
       

            $stmt = Conexion::conectar()->prepare("SELECT t1.idproducto, t1.cantidad,t2.descripcion,t1.precio_unitario, t1.valor_unitario, t1.valor_total,t1.importe_total, t1.descuento, t1.descuento_factor, t1.icbper, t1.igv, t1.codigo_afectacion, t2.id, t2.codunidad, t2.codigo, t2.tipo_precio, t2.codigoafectacion, t2.id  FROM detalle t1 INNER JOIN productos t2 ON t1.idproducto=t2.id  WHERE $item=:$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchall();

        
        
        $stmt->close();
        $stmt = null;
    }


    // GUARDAR DETALLES VENTA CARRITO EN LA BD
public static function mdlInsertarDetalles($idventa, $detalle){

    $stmt = Conexion::conectar()->prepare("INSERT INTO detalle(idventa, item, idproducto, codigo_afectacion, tipo_precio, cantidad, valor_unitario, precio_unitario, icbper, igv, porcentaje_igv, descuento, descuento_factor, valor_total, importe_total)
    VALUES (:idventa, :item, :idproducto, :codigo_afectacion, :tipo_precio, :cantidad, :valor_unitario, :precio_unitario, :icbper, :igv, :porcentaje_igv, :descuento, :descuento_factor, :valor_total, :importe_total)");
foreach($detalle as $k => $v){
    $stmt->bindParam(":idventa", $idventa, PDO::PARAM_INT);
    $stmt->bindParam(":item", $v['item'], PDO::PARAM_INT);
    $stmt->bindParam(":idproducto", $v['id'], PDO::PARAM_INT);
    $stmt->bindParam(":codigo_afectacion", $v['codigo_afectacion_alt'], PDO::PARAM_STR);
    $stmt->bindParam(":tipo_precio", $v['tipo_precio'], PDO::PARAM_STR);
    $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_STR);
    $stmt->bindParam(":valor_unitario", $v['valor_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":precio_unitario", $v['precio_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":icbper", $v['icbper'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $v['igv'], PDO::PARAM_STR);
    $stmt->bindParam(":porcentaje_igv", $v['porcentaje_igv'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento", $v['descuentos']['monto'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento_factor", $v['descuentos']['factor'], PDO::PARAM_STR);
    $stmt->bindParam(":valor_total", $v['valor_total'], PDO::PARAM_STR);
    $stmt->bindParam(":importe_total", $v['importe_total'], PDO::PARAM_STR);

   $stmt->execute();
}   

}
    // GUARDAR VENTA CARRITO EN LA BD
public static function mdlInsertarVenta($idemisor, $comprobante,$codigosSunat){

    $stmt = Conexion::conectar()->prepare("INSERT INTO venta(id, idemisor, tipocomp, idserie, serie, correlativo, serie_correlativo, fecha_emision, fechahora, codmoneda, tipocambio, metodopago,  comentario, op_gravadas, op_exoneradas, op_inafectas, op_gratuitas, igv_op, descuento_factor, descuento, icbper, igv, subtotal, total, codcliente, codvendedor, tipodoc, feestado, fecodigoerror, femensajesunat, nombrexml, xmlbase64, cdrbase64, bienesSelva, serviciosSelva)
    VALUES (NULL, :idemisor, :tipocomp, :idserie, :serie, :correlativo, :serie_correlativo, :fecha_emision, :fechahora, :codmoneda, :tipocambio, :metodopago, :comentario, :op_gravadas, :op_exoneradas, :op_inafectas, :op_gratuitas,:igv_op, :descuento_factor, :descuento, :icbper, :igv, :subtotal, :total, :codcliente, :codvendedor, :tipodoc, :feestado, :fecodigoerror, :femensajesunat, :nombrexml, :xmlbase64, :cdrbase64, :bienesSelva, :serviciosSelva)");

$fechahora = $comprobante['fecha_emision'].' '.date("H:i:s");
    $stmt->bindParam(":idemisor", $idemisor, PDO::PARAM_INT);
    $stmt->bindParam(":tipocomp", $comprobante['tipodoc'], PDO::PARAM_STR);
    $stmt->bindParam(":idserie", $comprobante['idserie'], PDO::PARAM_INT);
    $stmt->bindParam(":serie", $comprobante['serie'], PDO::PARAM_STR);
    $stmt->bindParam(":correlativo", $comprobante['correlativo'], PDO::PARAM_INT);
    $stmt->bindParam(":serie_correlativo", $comprobante['serie_correlativo'], PDO::PARAM_STR);
    $stmt->bindParam(":fecha_emision", $comprobante['fecha_emision'], PDO::PARAM_STR);
    $stmt->bindParam(":fechahora", $fechahora, PDO::PARAM_STR);
    $stmt->bindParam(":codmoneda", $comprobante['moneda'], PDO::PARAM_STR);
    $stmt->bindParam(":tipocambio", $comprobante['tipocambio'], PDO::PARAM_STR);
    $stmt->bindParam(":metodopago", $comprobante['metodopago'], PDO::PARAM_STR);
    $stmt->bindParam(":comentario", $comprobante['comentario'], PDO::PARAM_STR);
    $stmt->bindParam(":op_gravadas", $comprobante['total_opgravadas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_exoneradas", $comprobante['total_opexoneradas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_inafectas", $comprobante['total_opinafectas'], PDO::PARAM_STR);
    $stmt->bindParam(":op_gratuitas", $comprobante['total_opgratuitas'], PDO::PARAM_STR);
    $stmt->bindParam(":igv_op", $comprobante['igv_op'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento_factor", $comprobante['descuento_factor'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento", $comprobante['descuento'], PDO::PARAM_STR);
    $stmt->bindParam(":icbper", $comprobante['icbper'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $comprobante['igv'], PDO::PARAM_STR);
    $stmt->bindParam(":subtotal", $comprobante['monto_base'], PDO::PARAM_STR);
    $stmt->bindParam(":total", $comprobante['total'], PDO::PARAM_STR);
    $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
    $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
    $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
    $stmt->bindParam(":nombrexml", $codigosSunat['nombrexml'], PDO::PARAM_STR);
    $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
    $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);
    $stmt->bindParam(":codcliente", $comprobante['codcliente'], PDO::PARAM_INT);
    $stmt->bindParam(":codvendedor", $comprobante['codvendedor'], PDO::PARAM_INT);
    $stmt->bindParam(":tipodoc", $comprobante['codigo_doc_cliente'], PDO::PARAM_INT);
    $stmt->bindParam(":bienesSelva", $comprobante['bienesSelva'], PDO::PARAM_STR);
    $stmt->bindParam(":serviciosSelva", $comprobante['serviciosSelva'], PDO::PARAM_STR);

   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}
public static function mdlInsertarVentaCredito($idventa, $comprobante){

    $stmt = Conexion::conectar()->prepare("INSERT INTO pago_credito(id, id_venta, fecha, cuota, tipopago)
    VALUES (NULL, :id_venta, :fecha, :cuota, :tipopago)");

    $stmt->bindParam(":id_venta", $idventa, PDO::PARAM_INT);
    $stmt->bindParam(":fecha", $comprobante['fecha_cuota'], PDO::PARAM_STR);
    $stmt->bindParam(":cuota", $comprobante['cuotas'], PDO::PARAM_STR);
    $stmt->bindParam(":tipopago", $comprobante['tipopago'], PDO::PARAM_STR);
   

   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}

public static function mdlActualizarRechazadoVenta($idventa){

    $stmt = Conexion::conectar()->prepare("UPDATE venta SET op_gravadas=0, op_exoneradas=0, op_inafectas=0, op_gratuitas=0, descuento=0, descuento_factor=0, icbper=0, igv=0, subtotal=0, total=0 WHERE id=$idventa");
  

   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;
}
public static function mdlActualizarRechazadoDetalles($idventa, $detalles){
  
    foreach($detalles as $k => $v){
    $stmt = Conexion::conectar()->prepare("UPDATE detalle SET valor_unitario=:valor_unitario, precio_unitario=:precio_unitario, descuento=:descuento, descuento_factor=:descuento_factor, icbper=:icbper, igv= :igv, valor_total= :valor_total, importe_total= :importe_total WHERE idventa=:idventa");
  
    $stmt->bindParam(":idventa", $idventa, PDO::PARAM_INT);
    $stmt->bindParam(":valor_unitario", $v['valor_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":precio_unitario", $v['precio_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento", $v['descuento'], PDO::PARAM_STR);
    $stmt->bindParam(":descuento_factor", $v['descuento_factor'], PDO::PARAM_STR);
    $stmt->bindParam(":icbper", $v['icbper'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $v['igv'], PDO::PARAM_STR);
    $stmt->bindParam(":valor_total", $v['valor_total'], PDO::PARAM_STR);
    $stmt->bindParam(":importe_total", $v['importe_total'], PDO::PARAM_STR);

   $stmt->execute();
    }

}

// OBTENER EL ULTIMO ID COMPROBANTE
public static function mdlObtenerUltimoComprobanteId(){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM venta ORDER BY id DESC LIMIT 1");

    $stmt->execute();

    return $stmt->fetch();

}
// OBTENER COMPROBANTES POR ID
public static function mdlObtenerComprobantesId($id){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM venta WHERE id = :id ORDER BY id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchall();

}

 // LISTAR VENTAS  OTRO MÉTODO
 public static function mdlListarVentas(){

    $content =  "<tbody class='body-ventas'></tbody>";
    return $content;

}
// CONTAR LOS COMPROBANTES NO ENVIADOS====
public static function mdlComprobantesNoEnviados(){
    $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) FROM venta WHERE (tipocomp = '01' || tipocomp='03') AND (feestado ='' || feestado ='3') AND (anulado ='n' AND resumen ='n')");

    $stmt->execute();

    return $stmt->fetch();

}
}