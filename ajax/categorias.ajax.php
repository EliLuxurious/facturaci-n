<?php
require_once "../vendor/autoload.php";
use Controladores\ControladorCategorias;
class AjaxCategorias{
// EDITAR CATEGORIA

    public $idCategotia;
    public function ajaxEditarCategoria(){

        $item = 'id';
        $valor = $this->idCategoria;

        $respuesta = ControladorCategorias::ctrMostrarCategorias($item, $valor);

        echo json_encode($respuesta);
    }
    
    public $idEliminar;
    public function ajaxEliminarCategoria(){

        $datos = $this->idEliminar;

        $respuesta = ControladorCategorias::ctrEliminarCategoria($datos);
    }
    // VALIDAR NO REPETIR CATEGORÍA
    public $validarCategoria;
    public function ajaxValidarCategoria(){

        $item = 'categoria';
        $valor= $this->validarCategoria;
        $respuesta = ControladorCategorias::ctrMostrarCategorias($item, $valor);

        echo json_encode($respuesta);
    }
}

// OBJETO EDITAR CATEGORIA
if(isset($_POST['idCategoria'])){

    $categoria = new AjaxCategorias();
    $categoria->idCategoria = $_POST['idCategoria'];
    $categoria->ajaxEditarCategoria();
}
// OBJETO ELIMINAR CATEGORIA
if(isset($_POST['idEliminar'])){

    $categoriaD = new AjaxCategorias();
    $categoriaD->idEliminar = $_POST['idEliminar'];
    $categoriaD->ajaxEliminarCategoria();
}
// VALIDAR CATEGORÍA
if(isset($_POST['validarCategoria'])){

    $validar = new AjaxCategorias();
    $validar -> validarCategoria = $_POST['validarCategoria'];
    $validar->ajaxValidarCategoria();
}
