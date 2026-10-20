<?php
namespace Modelos;
use Conect\Conexion;
use PDO;
class ModeloCategorias{

    public static function mdlCrearCategoria($tabla, $datos){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(categoria) VALUES (:categoria) ");
        $stmt->bindParam(":categoria", $datos, PDO::PARAM_STR);
    
        if($stmt->execute()){
            return 'ok';
        }else{
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlMostrarCategorias($tabla, $item, $valor){
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
    // EDITAR CATEGORIA
    public static function mdlEditarCategoria($tabla, $datos){
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET categoria = :categoria WHERE id = :id ");
        $stmt->bindParam(":categoria", $datos['categoria'], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
    
        if($stmt->execute()){
            return 'ok';
        }else{
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    // ELIMINAR CATEGORIA
    public static function mdlEliminarCategoria($tabla, $datos){
        
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
}
