<?php
namespace Modelos;
use Conect\Conexion;
use PDO;

class ModeloProveedores{

        // MOSTRAR PROVEEDORES
        public static function mdlMostrarProveedores($tabla, $item, $valor){
        
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
    
    public static function mdlGuardarProveedor($tabla, $datos){

        if(strlen($datos['docIdentidad']) > 8){
            $ruc = $datos['docIdentidad'];   
            $razon_social = $datos['razon_social'];
            $nombre = '';        

        }else{
            $ruc = '';
        }
        if(strlen($datos['docIdentidad']) == 8){
            $dni = $datos['docIdentidad'];   
            $razon_social = '';
            $nombre = $datos['razon_social'];            

        }else{
            $dni = '';
        }

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, documento, ruc, razon_social, email, telefono,  direccion, ubigeo) VALUES (:nombre, :documento, :ruc, :razon_social, :email, :telefono, :direccion, :ubigeo)");

        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":documento", $dni, PDO::PARAM_STR);
        $stmt->bindParam(":ruc", $ruc, PDO::PARAM_STR);
        $stmt->bindParam(":razon_social", $razon_social, PDO::PARAM_STR);
        $stmt->bindParam(":email", $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeo", $datos['ubigeo'], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";
        }
        $stmt->close();
        $stmt = null;

    }

    // BUSCAR PROVEEDOR EN LA EMISIÓN DE COMPROBANTES
public static function mdlBuscarProveedor($tabla, $valor){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE nombre LIKE :valor OR documento LIKE :valor OR ruc LIKE :valor");
    $parametros = array(':valor'=>'%'.$valor.'%');
    
            $stmt->execute($parametros);
            return $stmt->fetchall();
}
   
}