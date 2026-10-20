<?php
namespace Controladores;
use Modelos\ModeloClientes;
class ControladorClientes{

    // MOSTRAR CLIENTES
    public static  function ctrMostrarClientes($item, $valor){
        
        $tabla = "clientes";
        $respuesta = ModeloClientes::mdlMostrarClientes($tabla, $item, $valor);
        return $respuesta;
    }
    

    // CREAR CLIENTE
    public  function ctrCrearCliente(){
           
        if(isset($_POST['nuevoCliente'])){

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
            preg_match('/^[0-9]+$/', $_POST["nuevoDni"]) &&
            preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST["nuevoEmail"])){

                $tabla = "clientes";
                $datos = array("nombre" => $_POST['nuevoCliente'],
                                "documento" => $_POST['nuevoDni'],
                                "email" => $_POST['nuevoEmail'],
                                "telefono" => $_POST['nuevoTelefono'],
                                "direccion" => $_POST['nuevaDireccion'],
                                "ruc" => $_POST['nuevoRuc'],
                                "razon_social" => $_POST['nuevoRS'],
                                "fecha_nacimiento" => $_POST['nuevaFechaNacimiento']);

                     $respuesta = ModeloClientes::mdlCrearCliente($tabla, $datos);

                     if($respuesta == 'ok'){
                        echo "<script>
                        Swal.fire({
                            title: '¡El cliente ha sido guardado corréctamente!',
                            text: '...',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Cerrar'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            loadClientes(1);

                            }
                        })
                        if(window.history.replaceState){
                            window.history.replaceState(null,null, window.location.href);
                            }
                        
                        </script>"; 
                     }

        }else{
            echo "<script>
                    Swal.fire({
                        title: '¡El cliente no puede ir vacío o llevar caracteres especiales!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'clientes';
                        }
                    })</script>"; 
        }
    }     
    }

    // EDITAR CLIENTE
    public function ctrEditarCliente(){

        if(isset($_POST['editarCliente'])){
        if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCliente"]) &&
            preg_match('/^[0-9]+$/', $_POST["editarDni"]) &&
            preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST["editarEmail"])){

                $tabla = "clientes";
                $datos = array("nombre" => $_POST['editarCliente'],
                                "documento" => $_POST['editarDni'],
                                "email" => $_POST['editarEmail'],
                                "telefono" => $_POST['editarTelefono'],
                                "direccion" => $_POST['editarDireccion'],
                                "ruc" => $_POST['editarRuc'],
                                "razon_social" => $_POST['editarRS'],
                                "fecha_nacimiento" => $_POST['editarFechaNacimiento'],
                                "id" => $_POST['id']);

                     $respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);

                     if($respuesta == 'ok'){

                        echo "<script>
                        Swal.fire({
                            title: '¡El cliente ha sido editado corréctamente!',
                            text: '...',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Cerrar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            window.location = 'clientes';
                            }
                        })</script>"; 
                     }

                    }else{
                        echo "<script>
                    Swal.fire({
                        title: '¡El cliente no puede ir vacío o llevar caracteres especiales!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'clientes';
                        }
                    })</script>"; 
                    }
        }

    }

    // ELIMINAR CLIENTE
    public static function ctrEliminarCliente($datos){
        if(isset($datos)){
            $tabla = "clientes";
            $respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);
            if($respuesta == 'ok'){
                echo "success";
            }else{
                echo "error";
            }
        }
    }
    // LISTAR CLIENTES CON BUSCADOR
    public  function ctrListarClient(){        
       
       $respuesta = ModeloClientes::mdlListarClientes();
       echo $respuesta;
            
             
    }
// BUSCAR RUC Y DNI SUNAT - RENIEC
    public static  function ctrBuscarRuc($numDoc,$tipoDoc){
        
        $respuesta = ModeloClientes::mdlBuscarRuc($numDoc,$tipoDoc);
        return $respuesta;
        
    }

   public static function ctrBucarCliente($valor){ 
    $tabla = "clientes";
    $respuesta = ModeloClientes::mdlBuscarCliente($tabla, $valor);
    return $respuesta;
   }



}