<?php
require_once "../vendor/autoload.php";
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorSunat;
use Controladores\ControladorClientes;


class AjaxSunat{

    
    public $codigoAfectacion;
    public function ajaxCodigoAfectacion(){

        $item = "codigo";
        $valor = $this->codigoAfectacion;
        $respuesta = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
        echo json_encode($respuesta);
    }
    
    public $codUnidad;
    public function ajaxCodigoUnidad(){

        $item = "codigo";
        $valor = $this->codUnidad;
        $respuesta = ControladorSunat::ctrMostrarUnidadMedida($item, $valor); 
        echo json_encode($respuesta);
    }
    // OBTENER EL CORRELATIVO Y SUMAR 1
    public $idSerie;
    public function ajaxCorrelativo(){
        $item = "id";
        $valor = $this->idSerie;
        $respuesta = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

        if($respuesta['correlativo'] == 0){

        echo $respuesta['correlativo'] = 1;

        }else{
            
            echo $respuesta['correlativo'] + 1;
        }
    }
    
    // BUSCAR SERIE CORRELATIVO VENTA
    public $serieCorrelativo;
    public function ajaxBuscarSerieCorrelativo($tipocomp){
        $valor = $this->serieCorrelativo;
        if($tipocomp == "01"){
        $codigo = "01";
        $respuesta = ControladorSunat::ctrBuscarSerieCorrelativo($valor, $codigo);
        } 
        if($tipocomp == "03"){
        $codigo = "03";
        $respuesta = ControladorSunat::ctrBuscarSerieCorrelativo($valor, $codigo);
        }
        echo json_encode($respuesta);
    }

//  public function ajaxConnection(){
     
//     $respuesta = ControladorSunat::ctrConn();
//     echo $respuesta;
//  } 
}
if(isset($_POST['codigoAfectacion'])){

    $objCodigoAfectacion = new AjaxSunat();
    $objCodigoAfectacion->codigoAfectacion = $_POST['codigoAfectacion'];
    $objCodigoAfectacion->ajaxCodigoAfectacion();
}
if(isset($_POST['codUnidad'])){

    $objCodigoUnidad = new AjaxSunat();
    $objCodigoUnidad->codUnidad = $_POST['codUnidad'];
    $objCodigoUnidad->ajaxCodigoUnidad();
}
if(isset($_POST['idSerie'])){

    $objCorrelativo = new AjaxSunat();
    $objCorrelativo->idSerie = $_POST['idSerie'];
    $objCorrelativo->ajaxCorrelativo();
}

if(isset($_POST['serieCorrelativo'])){

    $objSerie = new AjaxSunat();
    $objSerie->serieCorrelativo = $_POST['serieCorrelativo'];
    $objSerie->ajaxBuscarSerieCorrelativo($_POST['tipoComprobante']);

}
// if(isset($_POST['conexion'])){

//     $objConn = new AjaxSunat();
//     $objConn->ajaxConnection();

// }

