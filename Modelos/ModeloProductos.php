<?php
namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;

class ModeloProductos{

 public static function mdlMostrarProductos($tabla, $item, $valor) {
        
    if($item != null){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY id DESC");
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
 public static function mdlMostrarProductosMasVendidos($tabla, $item, $valor, $orden) {
        
    if($item != null){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY id DESC");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch();

    }else{
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden DESC");
        //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
        $stmt->execute();
        return $stmt->fetchall();

    }
   

    $stmt->close();
    $stmt = null;
}
 public static function mdlMostrarProductosStock($tabla, $item, $valor) {


        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY id DESC");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchall();
   

    $stmt->close();
    $stmt = null;
}
 
// REGISTRO DE PRODUCTOS
public static function mdlCrearProducto($tabla, $datos){

    $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, codigo, serie, descripcion, imagen, stock, tipo_precio, valor_unitario,  precio_unitario, precio_compra, igv, codigoafectacion, codunidad) VALUES (:id_categoria, :codigo, :serie, :descripcion, :imagen, :stock, :tipo_precio, :valor_unitario,  :precio_unitario, :precio_compra, :igv,  :codigoafectacion, :codunidad)");

    $stmt->bindParam(":id_categoria", $datos['id_categoria'], PDO::PARAM_INT);
    $stmt->bindParam(":codigo", $datos['codigo'], PDO::PARAM_STR);
    $stmt->bindParam(":serie", $datos['serie'], PDO::PARAM_STR);
    $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
    $stmt->bindParam(":imagen", $datos['imagen'], PDO::PARAM_STR);
    $stmt->bindParam(":stock", $datos['stock'], PDO::PARAM_STR);
    $stmt->bindParam(":tipo_precio", $datos['tipo_precio'], PDO::PARAM_STR);
    $stmt->bindParam(":valor_unitario", $datos['valor_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":precio_unitario", $datos['precio_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":precio_compra", $datos['precio_compra'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $datos['igv'], PDO::PARAM_STR);
    $stmt->bindParam(":codigoafectacion", $datos['codigoafectacion'], PDO::PARAM_STR);
    $stmt->bindParam(":codunidad", $datos['unidad'], PDO::PARAM_STR);

   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}
// EDITAR PRODUCTO
public static function mdlEditarProducto($tabla, $datos){

    $stmt = Conexion::conectar()->prepare("UPDATE $tabla set id = :id, id_categoria = :id_categoria, serie = :serie, descripcion = :descripcion, imagen = :imagen, stock = :stock, precio_compra = :precio_compra,  precio_unitario =  :precio_unitario, valor_unitario = :valor_unitario, igv = :igv, codigoafectacion = :codigoafectacion, codunidad = :codunidad WHERE id = :id");

    $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
    $stmt->bindParam(":id_categoria", $datos['id_categoria'], PDO::PARAM_INT);
    $stmt->bindParam(":serie", $datos['serie'], PDO::PARAM_STR);
    $stmt->bindParam(":codigo", $datos['codigo'], PDO::PARAM_STR);
    $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
    $stmt->bindParam(":imagen", $datos['imagen'], PDO::PARAM_STR);
    $stmt->bindParam(":stock", $datos['stock'], PDO::PARAM_STR);
    $stmt->bindParam(":precio_compra", $datos['precio_compra'], PDO::PARAM_STR);
    $stmt->bindParam(":precio_unitario", $datos['precio_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":valor_unitario", $datos['valor_unitario'], PDO::PARAM_STR);
    $stmt->bindParam(":codigoafectacion", $datos['codigoafectacion'], PDO::PARAM_STR);
    $stmt->bindParam(":codunidad", $datos['unidad'], PDO::PARAM_STR);
    $stmt->bindParam(":igv", $datos['igv'], PDO::PARAM_STR);

   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}
// EDITAR PRODUCTO
public static function mdlActivaDesactivaUnidadMedida($tabla, $datos){

    $stmt = Conexion::conectar()->prepare("UPDATE $tabla set activo = :activo WHERE id = :id");

    $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
    $stmt->bindParam(":activo", $datos['modo'], PDO::PARAM_STR);


   if($stmt->execute()){
         return   'ok';
    } else {
        return  'error';
    } 

    $stmt->close();
    $stmt = null;

}
// EDITAR PRODUCTO
public static function mdlActualizarStock($tabla, $detalle, $valor){

    if($valor == null){
    $stmt = Conexion::conectar()->prepare("UPDATE $tabla set stock = :stock WHERE id = :id");

foreach($detalle as $k => $v){
    $tabla = 'productos';
    $item = 'id';
    $valor = $v['id'];
    $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);
    
    foreach ($productos as $i => $prod){
       
    $cantidad = $prod['stock'] - $v['cantidad'];
   
    $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
    $stmt->bindParam(":stock", $cantidad, PDO::PARAM_STR);


   $stmt->execute();
 }
}
    }else{

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set stock = :stock WHERE id = :id");

    foreach($detalle as $k => $v){
    $tabla = 'productos';
    $item = 'id';
    $valor = $v['id'];
    $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);
    
    foreach ($productos as $i => $prod){
       
    $cantidad = $prod['stock'] + $v['cantidad'];
   
    $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
    $stmt->bindParam(":stock", $cantidad, PDO::PARAM_STR);


   $stmt->execute();
 }
} 
    }
}
// EDITAR PRODUCTO MAS VENDIDO
public static function mdlActualizarMasVendido($tabla, $detalle, $valor){

    if($valor == null){
    $stmt = Conexion::conectar()->prepare("UPDATE $tabla set ventas = :ventas WHERE id = :id");

foreach($detalle as $k => $v){
    $tabla = 'productos';
    $item = 'id';
    $valor = $v['id'];
    $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);
    
    foreach ($productos as $i => $prod){
       
    $cantidad = $prod['ventas'] - $v['cantidad'];
   
    $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
    $stmt->bindParam(":ventas", $cantidad, PDO::PARAM_STR);


   $stmt->execute();
 }
}
    }else{

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla set ventas = :ventas WHERE id = :id");

    foreach($detalle as $k => $v){
    $tabla = 'productos';
    $item = 'id';
    $valor = $v['id'];
    $productos = ModeloProductos::mdlMostrarProductosStock($tabla, $item, $valor);
    
    foreach ($productos as $i => $prod){
       
    $cantidad = $prod['ventas'] + $v['cantidad'];
   
    $stmt->bindParam(":id", $v['id'], PDO::PARAM_INT);
    $stmt->bindParam(":ventas", $cantidad, PDO::PARAM_STR);


   $stmt->execute();
 }
} 
    }
}

// ELIMINAR PRODUCTO
public static function mdlEliminarProducto($tabla, $datos){

    $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla  WHERE id=:id");
    $stmt->bindParam(":id", $datos, PDO::PARAM_INT);

    if($stmt->execute()){
        return 'ok';
    }else{
        return 'error';
    }
    $stmt->close();
    $stmt = null;
}



 // LISTAR PRODUCTOS OTRO MÉTODO
 public static function mdlListarProductos(){

    $content =  "<tbody class='body-productos'></tbody>";
    return $content;

}
 // LISTAR PRODUCTOS PARA LAS VENTAS
 public static function mdlListarProductosVentas(){

    $content =  "<tbody class='body-productos-ventas'></tbody>";
    return $content;

}
 // LISTAR PRODUCTOS PARA LAS VENTAS
 public static function mdlListarProductosGuia(){

    $content =  "<tbody class='body-productos-guia'></tbody>";
    return $content;

}
}