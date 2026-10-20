<?php
session_start();

require_once "../vendor/autoload.php";
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorCotizaciones;
use Controladores\ControladorClientes;
use Controladores\ControladorSunat;
use Controladores\ControladorEmpresa;

class AjaxCotizaciones{


 // LLENAR DATOS A LA NOTA DE CRÉDITO CLIENTE Y DESCUENTO
 public $correlativoSerie;
 public function ajaxLlenarComprobanteCliente(){

     $item = 'serie_correlativo'; 
     $valor = $this->correlativoSerie;
     $venta = ControladorCotizaciones::ctrMostrarCotizacion($item, $valor);

     $item = 'id'; 
     $valor = $venta['codcliente'];
     $cliente = ControladorClientes::ctrMostrarClientes($item, $valor);
     
     $datos = array(
                 "idcliente"=> $cliente['id'],
                 "nombre" => $cliente['nombre'],
                 "ruc" => $cliente['ruc'],
                 "dni" => $cliente['documento'],
                 "razon_social" => $cliente['razon_social'],
                 "direccion" => $cliente['direccion'],
                 "ubigeo" => $cliente['ubigeo'],
                 "telefono" => $cliente['telefono'],
                 "descuento" => $venta['descuento'],
                 "descuento_factor" => $venta['descuento_factor'],
                 "seriecorrelativo" => $venta['serie_correlativo'],
                 "tipodoc" => $venta['tipodoc'],

     );
     echo json_encode($datos);
 }
   
 // LLENAR DATOS A LA NOTA DE CRÉDITO CARRITO
 public $numCorrelativo;
 public function ajaxLlenarComprobante(){
     $item = 'serie_correlativo'; 
     $valor = $this->numCorrelativo;

     $venta = ControladorCotizaciones::ctrMostrarCotizacion($item, $valor);
if(is_array($venta)){    
    
    // exit();

     $item = "idventa";
     $valor = $venta['id'];
     $detalles = ControladorCotizaciones::ctrMostrarDetalles($item, $valor);
  
     $carrito=$_SESSION['carrito'];
     //Asignamos a la variable $carro los valores guardados en la sessión
     unset($_SESSION['carrito']);

     if(!isset($_SESSION['carrito'])){
         $_SESSION['carrito'] = array();
     }

     $carrito = $_SESSION['carrito'];

     //$item = count($carrito)+1;
     
     
     foreach ($detalles as $k => $value){
         $item = "id";
         $valor = $value['idproducto'];
     $producto = ControladorProductos::ctrMostrarProductos($item, $valor);
     
     $item = count($carrito)+1;
     $existe = false;
    
     foreach ($carrito as $k => $v) {
        
         if($v['id']== $producto['id'] ){
             $item = $k;
             $existe = true;
             break;
         }
     }
     
        if($value['codigo_afectacion'] == 10 || $value['codigo_afectacion'] == 20 || $value['codigo_afectacion'] == 30){
            $igvp = $value['igv'];
        }else{
            $igvp = 0.00; 
        }     

 
     $carrito[$item] = array(
        'id'=> $producto['id'],
        'codigo'=> $producto['codigo'],
        'descripcion'=> $producto['descripcion'],
        'valor_unitario'=> $producto['valor_unitario'],
        'precio_unitario'=> $producto['precio_unitario'],
        'igv' => $igvp,
        'unidad'=> $producto['codunidad'],
        'codigoafectacion'=> $value['codigo_afectacion'],
        'cantidad'=> $value['cantidad'],
        'descuento_item'	=> $value['descuento'],
        'tipo_afectacion'	=> $value['codigo_afectacion'],
        'descuento_factor'	=> $value['descuento_factor'],
        'valor_total' => $value['valor_total'],
        'importe_total' => $value['importe_total'],
        'icbper' => $value['icbper']
        );
    

}

     $_SESSION['carrito'] = $carrito;
     

}
 
}

}

if(isset($_POST['numcot'])){

    $objSerieNota = new AjaxCotizaciones();
    $objSerieNota->numCorrelativo = $_POST['numCorrelativo'];
    $objSerieNota->ajaxLlenarComprobante();

}
if(isset($_POST['ccot'])){

    $objSerieNotaS = new AjaxCotizaciones();
    $objSerieNotaS->correlativoSerie = $_POST['correlativoSerie'];
    $objSerieNotaS->ajaxLlenarComprobanteCliente();

}



