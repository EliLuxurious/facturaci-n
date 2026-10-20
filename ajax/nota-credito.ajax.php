<?php
session_start();

require_once "../vendor/autoload.php";
use Controladores\ControladorProductos;
use Controladores\ControladorNotaCredito;
use Controladores\ControladorVentas;
use Controladores\ControladorClientes;
use Controladores\ControladorSunat;
use Controladores\ControladorEmpresa;

class AjaxNotaCredito{


 // LLENAR DATOS A LA NOTA DE CRÉDITO CLIENTE Y DESCUENTO
 public $correlativoSerie;
 public function ajaxLlenarNotaCreditoCliente(){

     $item = 'serie_correlativo'; 
     $valor = $this->correlativoSerie;
     $venta = ControladorVentas::ctrMostrarVentas($item, $valor);

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
                 "serie" => $venta['serie'],
                 "correlativo" => $venta['correlativo'],
                 "id_nc" => $venta['id_nc']

     );
     echo json_encode($datos);
 }
   
 // LLENAR DATOS A LA NOTA DE CRÉDITO CARRITO
 public $numCorrelativo;
 public function ajaxLlenarNotaCredito(){
     $item = 'serie_correlativo'; 
     $valor = $this->numCorrelativo;

     $venta = ControladorVentas::ctrMostrarVentas($item, $valor);
if(is_array($venta)){ 
     if($venta['id_nc'] ==null || $venta['id_nc'] == 0){
    
    // exit();

     $item = "idventa";
     $valor = $venta['id'];

     $detalles = ControladorVentas::ctrMostrarDetalles($item, $valor);
  
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
     
}else{
    
    echo "error";

}
}
 
}
/*================================================================
//GUARDAR NOTA DE CRÉDITO DEL
===============================================================*/
public function ajaxGuardarNotaCredito($serieCo){
    $valor = $serieCo;
    //$moneda = $this->moneda;
    $doc = array('moneda'=> $_POST['moneda'],
                    'idSerie' => $_POST['serie'],
                    'fechaDoc' => $_POST['fechaDoc'],
                    'tipodoc' => $_POST['tipoDoc'],
                    'numDoc' =>  $_POST['docIdentidad'],
                    'descuento' =>  $_POST['descuentoGlobal'],
                    'fechaVence' => $_POST['fechaVence'],
                    'ruta_comprobante' => $_POST['ruta_comprobante'],
                    'motivo' => $_POST['motivo'],
                    'tipo_cambio' => $_POST['tipo_cambio'],
                    // 'envioSunat' => $_POST['envioSunat'],
                    // 'metodopago' => $_POST['metodopago'],
                    // 'comentario' => $_POST['comentario'],
                    // 'email' => $_POST['email'],
                    // 'modoemail' => $_POST['modoemail'],
                    // "bienesSelva" => $bienesSelva,
                    // "serviciosSelva" => $serviciosSelva
                );
    $resultado = ControladorNotaCredito:: ctrGuardarNotaCredito($valor, $doc);
    
}
}

if(isset($_POST['numCorrelativo'])){

    $objSerieNota = new AjaxNotaCredito();
    $objSerieNota->numCorrelativo = $_POST['numCorrelativo'];
    $objSerieNota->ajaxLlenarNotaCredito();

}
if(isset($_POST['correlativoSerie'])){

    $objSerieNotaS = new AjaxNotaCredito();
    $objSerieNotaS->correlativoSerie = $_POST['correlativoSerie'];
    $objSerieNotaS->ajaxLlenarNotaCreditoCliente();

}

// GUARDAR NOTA DE CRÉDITO
if(isset($_POST['ruta_comprobante']) && $_POST['ruta_comprobante'] == 'nota-credito'){
    $objGuardarNc= new AjaxNotaCredito();
    //$objGuardarVenta-> moneda = $_POST['moneda'];
    $objGuardarNc->ajaxGuardarNotaCredito($_POST['serieNumero']);
}

