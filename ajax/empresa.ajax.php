<?php
session_start();
require_once "../vendor/autoload.php";

use Controladores\ControladorEmpresa;
use Controladores\ControladorClientes;
use Controladores\ControladorUsuarios;

class AjaxEmpresa
{

    public function ajaxModoProduccion()
    {
        $datos = array(
            "id" => $_POST["idEmisor"],
            "modo" => $_POST['modo']
        );

        $respuesta = ControladorEmpresa::ctrActualizarModoProduccion($datos);
        if ($respuesta == "ok") {
            $produccion = ControladorEmpresa::ctrProduccion();
            echo $produccion;
        }
    }

    public function ajaxMostrarModo()
    {

        $respuesta = ControladorEmpresa::ctrModoProduccion();
        echo $respuesta;
    }

    public function ajaxConsultarComprobante()
    {
        $comprobante = array(
            "tipocomp" => $_POST['tipocomprobante'],
            "serie" => $_POST['seriec'],
            "correlativo" => $_POST['correlativoc']
        );
        $respuesta = ControladorEmpresa::ctrConsultarComprobante($comprobante);
        return $respuesta;
    }
    public $rucEmpresa;
    public function ajaxBuscarRucEmpresa()
    {
        $ruc = $this->rucEmpresa;
        $respuesta = ControladorEmpresa::ctrBuscarRucEmpresa($ruc);
    }
    public function ajaxCambiarLogo()
    {

        $directorio = __DIR__ . "/../vistas/img/logo/";

        unlink($directorio . $_POST["logoActual"]);


        $nombre_logo = $_FILES['editarLogo']['name'];
        $nombrec = substr($nombre_logo, strrpos($nombre_logo, '.') + 1);
        $nomlogo = 'logo.' . $nombrec;
        $nombre_logo = $nomlogo;

        move_uploaded_file($_FILES['editarLogo']['tmp_name'], $directorio . $nombre_logo);

        $empresa   = ControladorEmpresa::ctrEmisor();
        $datos = array(
            "id" => $empresa['id'],
            "logo" => $nombre_logo
        );

        $respuesta = ControladorEmpresa::ctrCambiarLogo($datos);

        echo $respuesta;
    }
    public function ajaxEliminarLogo()
    {
        $empresa   = ControladorEmpresa::ctrEmisor();
        $datos = array(
            "id" => $_POST['idEmpresa'],
            "logo" => ''
        );
        $respuesta = ControladorEmpresa::ctrEliminarLogo($datos);
        echo $respuesta;
        $directorio = __DIR__ . "/../vistas/img/logo/";

        unlink($directorio . $_POST["logo"]);
    }
    // CAMBIAR PLANTILLA ===================
    public function ajaxCambiarPlantilla()
    {

        $empresa   = ControladorEmpresa::ctrEmisor();
        $datos = array(
            "id" => $empresa['id'],
            "plantilla" => $_POST["plantilla"]
        );

        $respuesta = ControladorEmpresa::ctrCambiarPlantilla($datos);

        echo $respuesta;
    }

    public function ajaxBienesSelva()
    {
        $item = 'id';
        $valor = $_POST['id_empresa'];
        $itembs = 'bienesSelva';
        $valorbs = $_POST['bienesSelva'];
        $respuesta = ControladorEmpresa::ctrBienesServiciosSelva($item, $valor, $itembs, $valorbs);
    }
    public function ajaxServiciosSelva()
    {
        $item = 'id';
        $valor = $_POST['id_empresa'];
        $itembs = 'serviciosSelva';
        $valorbs = $_POST['serviciosSelva'];
        $respuesta = ControladorEmpresa::ctrBienesServiciosSelva($item, $valor, $itembs, $valorbs);
    }

    public function ajaxActualizarBD()
    {

        $respuesta = ControladorEmpresa::ctrAgregarCampoTablas();
        echo $respuesta;
    }
}
if (isset($_POST['modo'])) {
    $objModo = new AjaxEmpresa();
    $objModo->ajaxModoProduccion();
}
if (isset($_POST['pp'])) {
    $objMostrar = new AjaxEmpresa();
    $objMostrar->ajaxMostrarModo();
}
if (isset($_POST['tipocomprobante'])) {
    $objMostrar = new AjaxEmpresa();
    $objMostrar->ajaxConsultarComprobante();
}
if (isset($_POST['rucEmpresa'])) {

    $objRuc = new AjaxEmpresa();
    $objRuc->rucEmpresa = $_POST['rucEmpresa'];
    $objRuc->ajaxBuscarRucEmpresa();
}
if (isset($_POST['logoActual'])) {

    $objLogo = new AjaxEmpresa();
    $objLogo->ajaxCambiarLogo();
}
if (isset($_POST['idEmpresa'])) {

    $objLogo = new AjaxEmpresa();
    $objLogo->ajaxEliminarLogo();
}
if (isset($_POST['plantilla'])) {

    $objLogo = new AjaxEmpresa();
    $objLogo->ajaxCambiarPlantilla();
}
if (isset($_POST['bienesSelva'])) {

    $objbSelva = new AjaxEmpresa();
    $objbSelva->ajaxBienesSelva();
}
if (isset($_POST['serviciosSelva'])) {

    $objbSelva = new AjaxEmpresa();
    $objbSelva->ajaxServiciosSelva();
}
if (isset($_POST['updateBd'])) {

    $objbSelva = new AjaxEmpresa();
    $objbSelva->ajaxActualizarBD();
}
