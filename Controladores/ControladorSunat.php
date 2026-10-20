<?php
// MOSTRAR UNIDADES DE MEDIDA 
namespace Controladores;
use Modelos\ModeloSunat;
class ControladorSunat{
public static function ctrMostrarUnidadMedida($item, $valor){
    
    $tabla = "unidad";
    $respuesta = ModeloSunat::mdlMostrarUnidadMedida($tabla, $item, $valor);
    return $respuesta;
}

public static function ctrMostrarTipoAfectacion($item, $valor){
    
    $tabla = "tipo_afectacion";
    $respuesta = ModeloSunat::mdlMostrarUnidadMedida($tabla, $item, $valor);
    return $respuesta;
}
public static function ctrMostrarTipoA($item, $valor){
    
    $tabla = "tipo_afectacion";
    $respuesta = ModeloSunat::mdlMostrarTipoAfectacion($tabla, $item, $valor);
    return $respuesta;
}

public static function ctrMostrarTipoDocumento($item, $valor){
    
    $tabla = "tipo_documento";
    $respuesta = ModeloSunat::mdlMostrarTipoDocumento($tabla, $item, $valor);
    return $respuesta;
}
public static function ctrMostrarSerie($valor){
    $tabla = "serie";
    $item = "tipocomp";
    $respuesta = ModeloSunat::mdlMostrarSerie($tabla, $item, $valor);
    return $respuesta;
}

public static function ctrTipoComprobante($valor){
    $tabla = "tipo_comprobante";
    $item = "codigo";
    $respuesta = ModeloSunat::mdlTipoComprobante($tabla, $item, $valor);
    return $respuesta;
}

public static function ctrMostrarCorrelativo($item, $valor){
    $tabla = "serie";
    $respuesta = ModeloSunat::mdlMostrarCorrelativo($tabla, $item, $valor);
    return $respuesta;
}

public static function ctrActualizarCorrelativo($datos){
    $tabla = "serie";
    $repuesta = ModeloSunat::mdlEditarCorrelativo($tabla, $datos);
}

public static function ctrActualizarSerieResumen($datos){
    $tabla = "serie";
    $repuesta = ModeloSunat::mdlEditarSerieResumen($tabla, $datos);
}
// MOSTRAR TIPO COMPROBANTE
public static function ctrMostrarTablaParametrica($item, $valor, $codigo){

    $tabla = "tabla_parametrica";
   
    $respuesta = ModeloSunat::mdlMostrarTablaParametrica($tabla, $item, $valor, $codigo);
    return $respuesta;

}
	// BUSCAR SERIE CORRELATIVO
    public static function ctrBuscarSerieCorrelativo($valor, $tipocomp){
        $tabla = "venta";
        $respuesta = ModeloSunat::mdlBuscarSerieCorrelativo($tabla, $valor, $tipocomp);
       
       foreach($respuesta as $k => $v)
{
    return $v;
}      
        }  

    public static function ctrMostrarMetodoPago($item,$valor){
        $tabla = "medio_pago";
        $respuesta = ModeloSunat::mdlMostrarTipoDocumento($tabla, $item, $valor);
        return $respuesta;

    }
    // COMPROBAR CONEXIÓN 
// public static function ctrConn(){
//     // use 80 for http or 443 for https protocol
//     $connected = @fsockopen("www.google.com", 80, $errno, $errstr, 15);
//     if ($connected){
//         fclose($connected);
//         return true; 
//     }
//     return false;
// }
}