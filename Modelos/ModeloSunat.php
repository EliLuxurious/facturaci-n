<?php
namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;
class ModeloSunat{

// LISTAR UNIDAD DE MEDIDAS
public static function mdlMostrarUnidadMedida($tabla, $item, $valor){

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
public static function mdlMostrarTipoAfectacion($tabla, $item, $valor){

    if($item != null){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

        if($stmt->execute()){
            return $stmt->fetchall();
        }else{
            return 'error';
        }
        

    }else{
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
        $stmt->execute();
        return $stmt->fetchall();

    }
   

    $stmt->close();
    $stmt = null;
}

// MOSTRAR TIPOS DE DOCUMENTOS DE IDENTIDAD
public static function mdlMostrarTipoDocumento($tabla, $item, $valor){

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
    // TIPOS DE COMPROBANTES PARA EMITIR
    public static function mdlTipoComprobante($tabla, $item, $valor){

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

// MOSTRAR SERIE
public static function mdlMostrarSerie($tabla, $item, $valor){

    // if($item != null){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY id ASC");
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchall();

    // }else{
    //     $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
    //     //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
    //     $stmt->execute();
    //     return $stmt->fetchall();

    // }
   

    $stmt->close();
    $stmt = null;
}
public static function mdlMostrarCorrelativo($tabla, $item, $valor){

     if($item != null){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY id ASC");
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
// MOSTRAR TABLA PARAMÉTRICA
public static function mdlMostrarTablaParametrica($tabla, $item, $valor,$codigo){
if($codigo == null){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
    $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR); 

    $stmt->execute();
    return $stmt->fetchall();
}else{
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND codigo = :codigo");
    $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR); 
    $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR); 

    $stmt->execute();
    return $stmt->fetch();
}
    $stmt->close();
    $stmt = null;
}

    // ACTUALIZAR CORRELATIVO
    public static function mdlEditarCorrelativo($tabla, $datos){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET correlativo = :correlativo WHERE id = :id ");
        $stmt->bindParam(":correlativo", $datos['correlativo'], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
    
        if($stmt->execute()){
            return 'ok';
        }else{
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }
    // ACTUALIZAR CORRELATIVO
    public static function mdlEditarSerieResumen($tabla, $datos){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET serie = :serie WHERE id = :id ");
        $stmt->bindParam(":serie", $datos['serie'], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
    
        if($stmt->execute()){
            return 'ok';
        }else{
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    // BUSCAR SERIE CORRELATIVO DE COMPROBANTES
public static function mdlBuscarSerieCorrelativo($tabla, $valor, $tipocomp){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE serie_correlativo LIKE :valor AND tipocomp=:tipocomp");
    $parametros = array(':valor'=>'%'.$valor.'%',
                        ':tipocomp' => $tipocomp,
                    );
    
            $stmt->execute($parametros);
            return $stmt->fetchall();

}

}