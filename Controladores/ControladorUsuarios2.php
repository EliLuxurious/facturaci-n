<?php

namespace Controladores;

use Modelos\ModeloUsuarios;

class ControladorUsuarios
{

    // METODO PARA INGRESO DE USUARIO
    public  static function ctrIngresoUsuario($user, $pass, $recaptcha)
    {


        $secret_key = "6Lf4WrAZAAAAAGqQ9mXIdEdErbIqBYjPuFbO27Vb";

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha");
        //var_dump($response);
        $datosCaptcha = json_decode($response, true);

        // var_dump($datosCaptcha);

        //$pass = $_SESSION['clave'] = sha1($_POST['clave']); 

        if ($recaptcha == '') {
            echo '<br><div class="alert alert-danger">Compruebe el reCAPTCHA</div>';
        } else {
            if ($datosCaptcha['success'] == false) {
                echo '<br><div class="alert alert-danger">Error al ingresar el reCAPTCHA, vuelve a intentarlo</div>';
                echo "<script>grecaptcha.reset();</script>";
            } else {

                if (isset($user)) {
                    if (preg_match('/^[a-zA-Z0-9]+$/', $user)  && preg_match("/^[a-zA-Z0-9]+$/", $pass)) {

                        $encriptar = crypt($pass, '$2a$07$usesomesillystringforsalt$');
                        $tabla = "usuarios";
                        $item = "usuario";
                        $valor = $user;

                        $respuesta =    ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);

                        if ($respuesta == true && $respuesta['usuario'] == $user && $respuesta['password'] == $encriptar) {

                            if ($respuesta['estado'] == 1) {
                                session_start();
                                $_SESSION['iniciarSesion'] = 'ok';
                                $_SESSION['id'] = $respuesta['id'];
                                $_SESSION['id_sucursal'] = $respuesta['id_empresa'];
                                $_SESSION['nombre'] = $respuesta['nombre'];
                                $_SESSION['usuario'] = $respuesta['usuario'];
                                $_SESSION['foto'] = $respuesta['foto'];
                                $_SESSION['perfil'] = $respuesta['perfil'];
                                //REGISTRAR FECHA DE ULTIMO LOGIN
                                date_default_timezone_set("America/Lima");

                                $fechaHora = date("Y-m-d H:i:s");
                                // $horas = date("H:i:s");
                                // $fechaHora = $fecha.' '.$horas;

                                $item1 = 'ultimo_login';
                                $valor1 = $fechaHora;
                                $item2 = 'id';
                                $valor2 = $respuesta['id'];

                                $ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);

                                if ($ultimoLogin == 'ok') {

                                    echo "<script>
                            $('.login-box-msg').hide().fadeIn(500).html('Redireccionando...');
                            $('input, button, span').fadeOut(500);
                            $('.g-recaptcha div').hide();

                                $('#resultLogin').hide().fadeIn(500).html('<br><img style=\"width:85px\" src=\"vistas/img/reload.svg\">').delay(2000).fadeOut(500, function(){
                                        window.location = 'inicio';
                                    });                                                        
                                    </script>";
                                }
                            } else {
                                echo '<br><div class="alert alert-danger">Su cuenta está desactivada, póngase en contacto con el administrador</div>';
                            }
                        } else {
                            echo '<br><div class="alert alert-danger">Error al ingresar, vuelve a intentarlo</div>';
                            echo "<script>grecaptcha.reset();</script>";
                        }
                    }
                }
            }
        }
    }
    // REGISTRO DE USUARIO
    public static function ctrCrearUsuario($datos)
    {

        $tabla = 'usuarios';
        $respuesta = ModeloUsuarios::mdlNuevoUsuario($tabla, $datos);
        if ($respuesta == 'ok') {

            echo "<script>
                            Swal.fire({
                                title: '¡El usuario ha sido guardado correctamente!',
                                text: '...',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Cerrar'
                              }).then((result) => {
                                if (result.isConfirmed) {
                                  window.location = 'usuarios';
                                }
                                if(window.history.replaceState){
                                    window.history.replaceState(null,null, window.location.href);
                                    }


                              })</script>";
        }
    }
    // MOSTRAR USUARIOS|
    public static function ctrMostrarUsuarios($item, $valor)
    {

        $tabla = 'usuarios';
        $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);
        return $respuesta;
    }

    // EDITAR USUARIOS|
    public static function ctrEditarUsuario()
    {

        if (isset($_POST["editarUsuario"])) {

            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])) {

                /*=============================================
				VALIDAR IMAGEN
				=============================================*/

                $ruta = $_POST["fotoActual"];

                if (isset($_FILES["editarFoto"]["tmp_name"]) && !empty($_FILES["editarFoto"]["tmp_name"])) {

                    list($ancho, $alto) = getimagesize($_FILES["editarFoto"]["tmp_name"]);

                    $nuevoAncho = 500;
                    $nuevoAlto = 500;

                    /*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

                    $directorio = "vistas/img/usuarios/" . $_POST["editarUsuario"];

                    /*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

                    if (!empty($_POST["fotoActual"])) {

                        unlink($_POST["fotoActual"]);
                    } else {

                        mkdir($directorio, 0755);
                    }

                    /*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

                    if ($_FILES["editarFoto"]["type"] == "image/jpeg") {

                        /*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

                        $aleatorio = mt_rand(100, 999);

                        $ruta = "vistas/img/usuarios/" . $_POST["editarUsuario"] . "/" . $aleatorio . ".jpg";

                        $origen = imagecreatefromjpeg($_FILES["editarFoto"]["tmp_name"]);

                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                        imagejpeg($destino, $ruta);
                    }

                    if ($_FILES["editarFoto"]["type"] == "image/png") {

                        /*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

                        $aleatorio = mt_rand(100, 999);

                        $ruta = "vistas/img/usuarios/" . $_POST["editarUsuario"] . "/" . $aleatorio . ".png";

                        $origen = imagecreatefrompng($_FILES["editarFoto"]["tmp_name"]);

                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                        imagepng($destino, $ruta);
                    }
                }
                $tabla = "usuarios";

                if ($_POST["editarPassword"] != "") {

                    if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["editarPassword"])) {

                        $encriptar = crypt($_POST['editarPassword'], '$2a$07$usesomesillystringforsalt$');
                    } else {

                        echo "<script>
                    Swal.fire({
                        title: '¡La contraseña no puede ir vacío o llevar caracteres especiales!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'usuarios';
                        }
                    })</script>";
                    }
                } else {

                    $encriptar = $_POST["passwordActual"];
                }
                session_start();
                $datos = array(
                    "nombre" => $_POST["editarNombre"],
                    "usuario" => $_POST["editarUsuario"],
                    "password" => $encriptar,
                    "perfil" => $_POST["editarPerfil"],
                    'dni' => $_POST["editarDni"],
                    'email' => $_POST["editarEmail"],
                    "foto" => $ruta,
                    "id_sucursal" => $_SESSION['id_sucursal']
                );

                $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

                if ($respuesta == "ok") {

                    echo "<script>
                            Swal.fire({
                                title: '¡El usuario ha sido actualizado correctamente!',
                                text: '...',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Cerrar'
                              }).then((result) => {
                                if (result.isConfirmed) {
                                  window.location = 'usuarios';
                                }
                                if(window.history.replaceState){
                                    window.history.replaceState(null,null, window.location.href);
                                    }
                              })</script>";
                } else {

                    echo "<script>
                    Swal.fire({
                        title: '¡El usuario no puede ir vacío o llevar caracteres especiales!',
                        text: '...',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'usuarios';
                        }
                    })</script>";
                }
            }
        }
    }

    // BORRAR USUARIO
    public static function ctrBorrarUsuario()
    {
        if (isset($_GET['idUsuario'])) {
            $tabla = 'usuarios';
            $datos = $_GET['idUsuario'];
            if (file_exists($_GET['fotoUsuario'])) {

                unlink($_GET['fotoUsuario']);
                rmdir("vistas/img/usuarios/" . $_GET['usuario']);
            }
            $respuesta = ModeloUsuarios::mdlBorrarUsuario($tabla, $datos);
            if ($respuesta == 'ok') {

                echo "<script>
                        Swal.fire({
                        title: '¡El usuario ha sido eliminado!',
                        text: '...',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'usuarios';
                        }
                    })
                    </script>";
            }
        }
    }
    // BUSCAR DNI USUARIO=========================
    public static function ctrBuscarDni($dni)
    {

        $dni = $dni;

        $token =  'd2acd4088895b8305977e33818d656ea';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apifacturacion.com/dni/' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS  => array('token' => $token),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_CAINFO => dirname(__FILE__) . "/../api/cacert.pem" //Comentar si sube a un hosting 
            //para ejecutar los procesos de forma local en windows
            //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html

        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $empresa = json_decode($response);

        if (isset($empresa->dni)) {
            $datos = array(
                'dni' => $empresa->dni,
                'nombre' => $empresa->cliente,

            );

            echo json_encode($datos);
        } else {
            echo json_encode('error');
        }
    }
}
