<?php
require_once "../vendor/autoload.php";
use Controladores\ControladorProductos;
use SebastianBergmann\Environment\Console;

class AjaxProductos
{
    // EDITAR PRODUCTO Y SELECCIONAR PRODUCTO
    public $idProducto;
        
    public function ajaxCrearProducto()
    {
        $productos = $_POST;
        $file = $_FILES;
        $respuesta = ControladorProductos::ctrCrearProducto($productos, $file);
        // var_dump($file);
        echo $respuesta;
    }

    public function ajaxCrearCodigoProducto()
    {
        $item = "id_categoria";
        $valor = $this->idCategoria;
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor);

        echo json_encode($respuesta);
    }

    public function ajaxCrearCodigoProductoFix()
    {
        $item = "id_categoria";
        $valor = $this->idCategoria;
        $respuesta = "755"; //ControladorProductos::ctrMostrarProductos($item, $valor);
        echo json_encode($respuesta);
    }


    public function ajaxAgregarProducto()
    {
        $item = "id";
        $valor = $this->idProducto;
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor);
        echo json_encode($respuesta);
    }

    //  ELIMINAR PRODUCTO BETA
    public $idEliminarProducto;
    public $codigo;
    public $imagen;
    public function ajaxEliminarProducto()
    {
        $datosEliminar = [
            "idProducto" => $this->idEliminarProducto,
            "codigo" => $this->codigo,
            "imagen" => $this->imagen,
        ];

        $resultado = ControladorProductos::ctrEliminarProducto($datosEliminar);
    }

    public function ajaxActivaDesactivaUnidadMedida()
    {
        $datos = [
            "id" => $_POST["idUnidad"],
            "modo" => $_POST["modo"],
        ];
        $resultado = ControladorProductos::ctrActivaDesactivaUnidadMedida(
            $datos
        );
    }
}

if (isset($_POST["nuevaDescripcion"])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxCrearProducto();
}

// GENERAR CÓDIGO DESDE LA idCategoria
if (isset($_POST["idCategoria"])) {
    $codigoProducto = new AjaxProductos();
    $codigoProducto->idCategoria = $_POST["idCategoria"];
    $codigoProducto->ajaxCrearCodigoProductoFix();   
}

// EDITAR PRODUCTO
if (isset($_POST["idProducto"])) {
    $editarProducto = new AjaxProductos();
    $editarProducto->idProducto = $_POST["idProducto"];
    $editarProducto->ajaxAgregarProducto();
}

// ELIMINAR PRODUCTO
if (isset($_POST["idEliminarProducto"])) {
    $eliminar = new AjaxProductos();
    $eliminar->idEliminarProducto = $_POST["idEliminarProducto"];
    $eliminar->codigo = $_POST["codigo"];
    $eliminar->imagen = $_POST["imagen"];
    $eliminar->ajaxEliminarProducto();
}

if (isset($_POST["idUnidad"])) {
    $actDesa = new AjaxProductos();
    $actDesa->ajaxActivaDesactivaUnidadMedida();
}
