<?php
session_start();
require_once "../vendor/autoload.php";
use Controladores\ControladorGuiaRemision;
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;


class AjaxGuia{
    public function ajaxBuscarUbigeoPartida(){
        $item = 'nombre_distrito';
        $valor = $_POST['ubigeopartida'];
        $respuesta = ControladorGuiaRemision::ctrMostrarUbigeo($item, $valor);
        
        foreach ($respuesta as $k => $v){
            echo "<legend style='margin:0px !important; font-size: 17px;'><a href='#' class='btn btn-ubigeo-partida'  idUbigeo='".$v['ubigeo']."'>".$v['ubigeo'].' - '.$v['name']." - ".$v['nombre_provincia']." - ".$v['nombre_distrito']."</a></legend>";
        }
    }
    public function ajaxBuscarUbigeoLlegada(){
        $item = 'nombre_distrito';
        $valor = $_POST['ubigeollegada'];
        $respuesta = ControladorGuiaRemision::ctrMostrarUbigeo($item, $valor);
        
        foreach ($respuesta as $k => $v){
            echo "<legend style='margin:0px !important; font-size: 17px;'><a href='#' class='btn btn-ubigeo-llegada'  idUbigeo='".$v['ubigeo']."'>".$v['ubigeo'].' - '.$v['name']." - ".$v['nombre_provincia']." - ".$v['nombre_distrito']."</a></legend>";
        }
    }

    public function ajaxAgregarUbigeo() {
        $tabla = 'ubigeo_distrito';
        $item = 'id';
        $valor = $_POST['codUbigeo'];

        $respuesta = ControladorGuiaRemision::ctrMostrar($tabla,$item,$valor);
        echo json_encode($respuesta);
    }
   
    public function ajaxAgregarComprobante() {
        $tabla = 'venta';
        $valor = $_POST['serieCorrelativo'];

        $respuesta = ControladorGuiaRemision::ctrBuscarSerieCorrelativo($tabla,$valor);
       foreach($respuesta as $k => $v) {
         echo "<legend style='margin:0px !important; font-size: 17px;'><a href='#' class='btn btn-serie-correlativo'  numCorrelativo='".$v['serie_correlativo']."'>".$v['serie_correlativo']."</a></legend>";
       }
       
    }



    
    public $numCorrelativo;
 public function ajaxLlenarGuia(){

     $item = 'serie_correlativo'; 
     $valor = $this->numCorrelativo;

     $venta = ControladorVentas::ctrMostrarVentas($item, $valor);

   
     $item = "idventa";
     $valor = $venta['id'];

     $detalles = ControladorVentas::ctrMostrarDetalles($item, $valor);
    $_SESSION['carritoG'] = array();
     $carritoG=$_SESSION['carritoG'];
//Asignamos a la variable $carro los valores guardados en la sessión
    unset($carritoG);

     if(!isset($_SESSION['carritoG'])){
         $_SESSION['carritoG'] = array();
     }

     $carritoG = $_SESSION['carritoG'];

     //$item = count($carritoG)+1;
     
     
     foreach ($detalles as $k => $value){
         $item = "id";
         $valor = $value['idproducto'];       
     $producto = ControladorProductos::ctrMostrarProductos($item, $valor);
     
     $item = count($carritoG)+1;
     $existe = false;
    
     foreach ($carritoG as $k => $v) {
        
         if($v['id']== $producto['id'] ){
             $item = $k;
             $existe = true;
             break;
         }
     }
        

 
     $carritoG[$item] = array(
        'id'=> $producto['id'],
        'codigo'=> $producto['codigo'],
        'descripcion'=> $producto['descripcion'],       
        'unidad'=> $producto['codunidad'],
        'cantidad'=> $value['cantidad']
       
        );
    

}

     $_SESSION['carritoG'] = $carritoG;
     
    $respuesta = ControladorGuiaRemision::ctrLlenarCarritoGuia($carritoG);
 
}
 public function ajaxLlenaCarroGuia(){
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];    
     
    $item = 'id';
    $valor = $idProducto;
     $producto = ControladorProductos::ctrMostrarProductos($item, $valor);
     if(!isset($_SESSION['carritoG'])){
        $_SESSION['carritoG'] = array();
    }

    $carritoG = $_SESSION['carritoG'];

    //$item = count($carritoG)+1;
    if($_POST['cantidad'] != null){
        $item = count($carritoG)+1;
    foreach ($carritoG as $k => $v) {        
        if($v['codigo']==$producto['codigo']){
            $item = $k;
            $existe = true;
            break;
        }
    }
        

 
     $carritoG[$item] = array(
        'id'=> $producto['id'],
        'codigo'=> $producto['codigo'],
        'descripcion'=> $producto['descripcion'],       
        'unidad'=> $producto['codunidad'],
        'cantidad'=> $cantidad,
       
        );
    

}

     $_SESSION['carritoG'] = $carritoG;
     
    $respuesta = ControladorGuiaRemision::ctrLlenarCarritoGuia($carritoG);
 
}


    public function ajaxCrearGuia(){

        $datosForm = $_POST;
        $respuesta =  ControladorGuiaRemision::ctrCrearGuia($datosForm);
        return $respuesta;
    }
}

if(isset($_POST['modalidadTraslado'])){
    $objGuia = new AjaxGuia();
    $objGuia->ajaxCrearGuia();
}
if(isset($_POST['ubigeopartida'])){
    $objGuia = new AjaxGuia();
    $objGuia->ajaxBuscarUbigeoPartida();
}
if(isset($_POST['ubigeollegada'])){
    $objGuia = new AjaxGuia();
    $objGuia->ajaxBuscarUbigeoLlegada();
}
if(isset($_POST['codUbigeo'])){
    $objGuia = new AjaxGuia();
    $objGuia->ajaxAgregarUbigeo();
}
if(isset($_POST['numCorrelativo'])){

    $objSerieNota = new AjaxGuia();
    $objSerieNota->numCorrelativo = $_POST['numCorrelativo'];
    $objSerieNota->ajaxLlenarGuia();

}
if(isset($_POST['idProducto'])){

    $objSerieNota = new AjaxGuia();
    $objSerieNota->ajaxLlenaCarroGuia();

}
if(isset($_POST['serieCorrelativo'])){
    $objSerieNota = new AjaxGuia();
    $objSerieNota->ajaxAgregarComprobante();

}

