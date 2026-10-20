<?php
session_start();
require_once "../vendor/autoload.php";
use Controladores\ControladorUsuarios;
use Modelos\ModeloUsuarios;

class AjaxUsuarios{
    //LOGIN USUARIOS
    public $ingUsuario;
    public $ingPassword;
    public function ajaxLogin() {
        $user = $this->ingUsuario;
        $pass = $this->ingPassword;
        // $recaptcha = $_POST["recaptcha"];
        @$token = $_POST["token"];
        $conectar = $_POST["conectar"];
        $respuesta = ControladorUsuarios::ctrIngresoUsuario($user, $pass, $token, $conectar);
       
    
    }
    //AGREGAR USUARIOS
    public function ajaxAgregarUsuario() {
       
        if(isset($_POST["nuevoNombre"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"])){             
                   
                    $ruta = "";
                  
			if(isset($_FILES["nuevaFoto"]["tmp_name"])){
                   
                    if(($_FILES["nuevaFoto"]["type"] == "image/jpeg") || 
                        ($_FILES["nuevaFoto"]["type"] == "image/jpg") ||
                        ($_FILES["nuevaFoto"]["type"] == "image/png")
                        ){               

                        //CARPETA DONDE SE GUARDARÁ LA IMAGEN

                    $directorio = "../vistas/img/usuarios/".$_POST['nuevoUsuario'];
                        if(!file_exists($directorio)){
                        mkdir($directorio, 0755, true);
                        }
                       $nombre_img = $_FILES['nuevaFoto']['name'];

                        $ruta = "vistas/img/usuarios/".$_POST['nuevoUsuario']."/".$nombre_img;

                        move_uploaded_file($_FILES['nuevaFoto']['tmp_name'],$directorio."/".$nombre_img);
                    
                }
            }  
                
                $encriptar = crypt($_POST['nuevoPassword'], '$2a$07$usesomesillystringforsalt$');
                $datos = array('nombre' => $_POST["nuevoNombre"], 
                                'usuario' => $_POST["nuevoUsuario"],
                                'password' => $encriptar,
                                'perfil' => $_POST["nuevoPerfil"],
                                'dni' => $_POST["nuevoDni"],
                                'email' => $_POST["nuevoEmail"],
                                "foto" => $ruta,
                                "id_sucursal" => $_SESSION['id_sucursal']);
                                
        $respuesta = ControladorUsuarios::ctrCrearUsuario($datos);
        echo $respuesta;

    }else{
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
                       // window.location = 'usuarios';
                        }
                    })</script>"; 

    }
    }
}
    // EDITAR USARIO|
    public $idUsuario;

    public function ajaxEditarUsuario(){
        $item = 'id';
        $valor= $this->idUsuario;
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        echo json_encode($respuesta);
    }
    // ACTIVAR USUARIO
    public $activarUsuario;
    public $activarId;
    
    public function ajaxActivarUsuario(){

        $tabla = 'usuarios';
        $item1 = 'estado';
        $valor1 = $this->activarUsuario;
        $item2 = 'id';
        $valor2 = $this->activarId;
     
        $respuesta = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);
       echo $respuesta;
    }
    // VALIDAR NO REPETIR USUARIO
    public $validarUsuario;
    public function ajaxValidarUsuario(){

        $item = 'usuario';
        $valor= $this->validarUsuario;
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        echo json_encode($respuesta);
    }
    public $dni;
    public function ajaxBuscarDni(){
        $dni = $this->dni;
        $respuesta = ControladorUsuarios::ctrBuscarDni($dni);
    }

    public function ajaxCerrarSesion(){
        $item = 'id';
        $valor = $_SESSION['id'];
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        if($respuesta['estado'] != 1){
            echo 'ok';
        }
    }
}
    //OBJETO LOGIN USUARIOS
    if(isset($_POST['ingUsuario'])){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    $login = new ajaxUsuarios();
    $login->ingUsuario = test_input($_POST['ingUsuario']);
    $login->ingPassword = test_input($_POST['ingPassword']);
    $login->ajaxLogin();
    }
    // OBJETO AGREGAR USUARIO
    if(isset( $_POST['nuevoUsuario'])){
        $nuevo = new AjaxUsuarios();
        $nuevo-> ajaxAgregarUsuario();

    }
    // OBJETO EDITAR USUARIO
    if(isset( $_POST['idUsuario'])){

        $editar = new AjaxUsuarios();
        $editar-> idUsuario = $_POST['idUsuario'];
        $editar-> ajaxEditarUsuario();

    }
    // OBJETO ACTIVAR USUARIO
    if(isset($_POST['activarUsuario'])){
        
        $activarUsuario = new AjaxUsuarios();
        $activarUsuario->activarId = $_POST['activarId'];
        $activarUsuario->activarUsuario = $_POST['activarUsuario'];
        $activarUsuario->ajaxActivarUsuario();
    }
    // OBJETO VALIDAR NO REPETIR USUARIO
    if(isset($_POST['validarUsuario'])){
    $validarUsuario = new AjaxUsuarios();
    $validarUsuario->validarUsuario = $_POST['validarUsuario'];
    $validarUsuario->ajaxValidarUsuario();
    }
    // OBJETO VALIDAR NO REPETIR USUARIO
    if(isset($_POST['dni'])){
    $objDni = new AjaxUsuarios();
    $objDni->dni = $_POST['dni'];
    $objDni->ajaxBuscarDni();
    }


    if(isset($_POST['cerrarS'])){
        $objCerras = new AjaxUsuarios();
        $objCerras->ajaxCerrarSesion();
    }