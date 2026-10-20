<?php
namespace Controladores;
use Modelos\ModeloCategorias;
use Modelos\ModeloProductos;

class ControladorCategorias{
    public function ctrCrearCategoria(){
        
        if(isset($_POST['nuevaCategoria'])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST['nuevaCategoria'])){
                $table = "categorias";
                $datos = strtoupper($_POST['nuevaCategoria']);
                 $respuesta = ModeloCategorias::mdlCrearCategoria($table,$datos);
                 if($respuesta == 'ok'){
                    echo "<script>
                    Swal.fire({
                        title: '¡La categoría ha sido agregada corréctamente!',
                        text: '...',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'categorias';
                        }
                    })</script>";   
                 }

        }else{

            echo "<script>
                    Swal.fire({
                        title: '¡La categoría no puede ir vacía o llevar caracteres especiales!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'categorias';
                        }
                    })</script>"; 
        }
    }
    }

    // MOSTRAR, LISTAR CATEGORÍAS
    public static function ctrMostrarCategorias($item, $valor){

        $tabla = "categorias";
        $respuesta = ModeloCategorias::mdlMostrarCategorias($tabla, $item, $valor);
        return $respuesta;
    }

    // EDITAR CATEGORIA
    public function ctrEditarCategoria(){
        
        if(isset($_POST['editarCategoria'])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST['editarCategoria'])){

                $table = "categorias";
                $datos = array('categoria'=> strtoupper($_POST['editarCategoria']),
                                'id' => $_POST['idCategoria']);

                 $respuesta = ModeloCategorias::mdlEditarCategoria($table,$datos);

                 if($respuesta == 'ok'){
                    echo "<script>
                    Swal.fire({
                        title: '¡La categoría ha sido cambiada corréctamente!',
                        text: '...',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'categorias';
                        }
                    })</script>";   
                 }

        }else{

            echo "<script>
                    Swal.fire({
                        title: '¡La categoría no puede ir vacía o llevar caracteres especiales!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'categorias';
                        }
                    })</script>"; 
        }
    }
    }

    public static function ctrEliminarCategoria($datos){
        if(isset($datos)){
            $tabla = 'productos';
            $item = 'id_categoria';
            $valor = $datos;
            $producto = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor);

            if(!$producto){

            $tabla = 'categorias';
            $respuesta = ModeloCategorias::mdlEliminarCategoria($tabla, $datos);
        if($respuesta == 'ok'){
           echo 'success';
        }
    }else{
        echo 'error'; 
    }
        }
    }
}