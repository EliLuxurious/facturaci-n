<?php 
session_start();
require_once "../vistas/tables/pagination.php";
require_once "../vendor/autoload.php";
use Controladores\ControladorResumenDiario;
use Controladores\ControladorClientes;
use Controladores\ControladorVentas;



class AjaxResumenDiario{

    // LISTA LOS COMPROBANTES POR LA FECHA DE EMISIÓN
    public $fechaResumen;
    public function ajaxTraerFecha(){
        $fecha1 = $this->fechaResumen;
        $fecha2 = str_replace('/', '-', $fecha1);
        $fecha = date('Y-m-d', strtotime($fecha2));
        $resultado = ControladorResumenDiario::ctrMostrarComprobantes($fecha);
        return $resultado;
    }
    // ENVÍA EL COMPROBANTE ESCOGIENDO LA FECHA DE EMISIÓN
    public $fechaResumenEnvio;
    public function ajaxEnviarResumenDiario(){
        $fecha1 = $this->fechaResumenEnvio;
        $fecha2 = str_replace('/', '-', $fecha1);
        $fecha = date('Y-m-d', strtotime($fecha2));

        $resultado = ControladorResumenDiario::ctrEnviarResumenDiario($fecha);
        return $resultado;
    }
    // MUESTRA TODOS LOS RESÚMENES MEDIANTE AJAX
    public function ajaxMostrarResumenes(){

        $resultado = ControladorResumenDiario::ctrMostrarResumenes();
        return $resultado;
        
    }
    // MUESTRA LA BOLETAS ENVIADAS POR RESÚMEN DIARIO
    public function ajaxMostrarResumenesBoletas(){

        $idenvio = $_POST['idenvio'];
        $resultado = ControladorResumenDiario::ctrMostrarDetallesResumenenes($idenvio);
        return $resultado;

    }
    // public function ajaxLoadBoletas(){
    //     $resultado = ControladorResumenDiario::ctrLoadBoletas();
    //     return $resultado;
    // }
}
if(isset($_POST['fechaResumen'])){
    $objFecha = new AjaxResumenDiario();
    $objFecha->fechaResumen = $_POST['fechaResumen'];
    $objFecha->ajaxTraerFecha();
}
if(isset($_POST['ruta'])){
if($_POST['ruta']= 'resumen-diario'){
    $objResumen = new AjaxResumenDiario();
    $objResumen->fechaResumenEnvio = $_POST['fechaResumenEnvio'];
    $objResumen->ajaxEnviarResumenDiario();
}
}
if(isset($_POST['resumen_boletas'])){
    $objResumen = new AjaxResumenDiario();
    $objResumen->ajaxMostrarResumenes();
}
if(isset($_POST['idenvio'])){
    $objResumen = new AjaxResumenDiario();
    $objResumen->ajaxMostrarResumenesBoletas();
}
// if(isset($_POST['loadBoletas'])){
//     $objResumen = new AjaxResumenDiario();
//     $objResumen->ajaxLoadBoletas();
// }