<?php
session_start();

require_once "../vendor/autoload.php";
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorVentas;
use Controladores\ControladorClientes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\Controlador;

class AjaxEvioSunat{
    public $idVenta;
    public function ajaxActualizarEvioSunat(){

        $idVenta = $this->idVenta;
        $respuesta = ControladorEnvioSunat::ctrActualizarVenta($idVenta);
        echo $respuesta;
    }

    public $idComprobante;
    public function ajaxEnvioBaja(){

        $idComprobante = $this->idComprobante;
        $respuesta = ControladorEnvioSunat::ctrBajaComprobante($idComprobante);
        echo $respuesta;
    }
}
if(isset($_POST['idVenta'])){
    $objVenta = new AjaxEvioSunat();
    $objVenta->idVenta = $_POST['idVenta'];
    $objVenta->ajaxActualizarEvioSunat();
}
if(isset($_POST['idComprobante'])){
    $objComprobante = new AjaxEvioSunat();
    $objComprobante->idComprobante = $_POST['idComprobante'];
    $objComprobante->ajaxEnvioBaja();
}