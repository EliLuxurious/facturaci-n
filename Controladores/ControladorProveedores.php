<?php
namespace Controladores;
use Modelos\ModeloProveedores;
class ControladorProveedores{

     // MOSTRAR PROVEEDORES
     public static  function ctrMostrarProveedores($item, $valor){
        
        $tabla = "proveedores";
        $respuesta = ModeloProveedores::mdlMostrarProveedores($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrGuardarProveedor($datos) { 
        $tabla = "proveedores";

        $respuesta = ModeloProveedores::mdlGuardarProveedor($tabla, $datos);
        return $respuesta;


    }

 // BUSCAR RUC Y DNI SUNAT - RENIEC
    //  public static  function ctrBuscarRuc($numDoc,$tipoDoc){
         
    //      $respuesta = ModeloProveedores::mdlBuscarRuc($numDoc,$tipoDoc);
    //      return $respuesta;
         
    //  }
 
    public static function ctrBucarProveedor($valor){ 
     $tabla = "proveedores";
     $respuesta = ModeloProveedores::mdlBuscarProveedor($tabla, $valor);
     return $respuesta;
    }
 
 
 
 }
