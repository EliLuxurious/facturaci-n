<?php

namespace Controladores;

use Modelos\ModeloEmpresa;
use api\ApiFacturacion;

class ControladorEmpresa
{


    public static function ctrEmisor()
    {
        $tabla = "emisor";
        $item = 'id';
        $valor = $_SESSION['id_sucursal'];
        $respuesta = ModeloEmpresa::mdlMostrarEmisor($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrEmisorConexion()
    {
        $tabla = "emisor";
        $item = 'id';
        $valor = 1;
        $respuesta = ModeloEmpresa::mdlMostrarEmisor($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrActualizarEmpresa()
    {
        if (isset($_POST["ruc"])) {
            $directorio = "api/certificado/";

            $nombre_cerificado = $_FILES['certificado']['name'];

            move_uploaded_file($_FILES['certificado']['tmp_name'], $directorio . "/" . $nombre_cerificado);

            if ($_POST['logoBD'] != "") {
                $logo = $_POST['logoBD'];
            } else {
                $logo = "";
            }
            if (empty($nombre_cerificado)) {
                $nombre_cerificado = $_POST['certificadobd'];
            }
            $datos = array(
                "id" => $_POST["idEmisor"],
                "ruc" => $_POST["ruc"],
                "razon_social" => $_POST["razon_social"],
                "nombre_comercial" => $_POST["nombre_comercial"],
                "direccion" => $_POST["direccion"],
                "telefono" => $_POST["telefono"],
                "pais" => $_POST["pais"],
                "departamento" => $_POST["departamento"],
                "provincia" => $_POST["provincia"],
                "distrito" => $_POST["distrito"],
                "ubigeo" => $_POST["ubigeo"],
                "usuario_sol" => trim($_POST["usuario_sol"]),
                "clave_sol" => trim($_POST["clave_sol"]),
                "clave_certificado" => trim($_POST["clave_certificado"]),
                "certificado" => $nombre_cerificado,
                "afectoigv" => $_POST["afectoigv"],
                "correo_ventas" => trim($_POST["correo_ventas"]),
                "correo_soporte" => trim($_POST["correo_soporte"]),
                "servidor" => trim($_POST["servidor"]),
                "contrasena" => trim($_POST["contrasena"]),
                "puerto" => $_POST["puerto"],
                "seguridad" => $_POST["seguridad"],
                "tipo_envio" => $_POST["tipo_envio"],
                "logo" => $logo,
                "igv" => $_POST["igvp"],
                "client_id" => trim($_POST["client_id"]),
                "secret_id" => trim($_POST["secret_id"]),
                'conexion' => $_POST["rseguridad"],
                'clavePublica' => trim($_POST["clavePublica"]),
                'clavePrivada' => trim($_POST["clavePrivada"])
            );

            $respuesta = ModeloEmpresa::mdlActualizarDatosEmpresa($datos);
            if ($respuesta == 'ok') {

                echo "<script>
                    Swal.fire({
                        title: '¡Datos de la empresa han sido actualizados corréctamente!',
                        text: '...',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        window.location = 'empresa';
                        }
                    })</script>";
            } else {
            }
        }
    }
    public static function ctrActualizarModoProduccion($datos)
    {
        $tabla = "empresa";
        $item = 'id';
        $valor = $datos['id'];
        $respuesta = ModeloEmpresa::mdlActualizarModoProduccion($item, $valor, $datos);
        return $respuesta;
    }

    public static function ctrModoProduccion()
    {
        $tabla = "emisor";
        $item = "id";
        $valor = $_SESSION['id_sucursal'];
        $respuesta = ModeloEmpresa::mdlMostrarEmisor($tabla, $item, $valor);
        return $respuesta['modo'];
    }
    public static function ctrConsultarComprobante($comprobante)
    {

        $emisor = ControladorEmpresa::ctrEmisor();
        $objapi = new ApiFacturacion();
        $objapi->consultarComprobante($emisor, $comprobante);
        return $objapi;
    }


    // BUSCAR RUC SUNAT=========================
    public static function ctrBuscarRucEmpresa($ruc)
    {
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $numDoc = test_input($ruc);

        $token =  'd2acd4088895b8305977e33818d656ea';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apifacturacion.com/ruc/' . $numDoc,
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

        if (isset($empresa->ruc)) {
            $datos = array(
                'ruc' => $empresa->ruc,
                'razon_social' => $empresa->razon_social,
                'estado' => $empresa->estado,
                'condicion' => $empresa->condicion,
                'direccion' => $empresa->direccion,
                'ubigeo' => $empresa->ubigeo,
                'departamento' => $empresa->departamento,
                'provincia' => $empresa->provincia,
                'distrito' => $empresa->distrito,
                'token' => $empresa->token

            );

            echo json_encode($datos);
        } else {
            echo json_encode('error');
        }
    }

    public static function ctrCambiarLogo($datos)
    {

        $resultado = ModeloEmpresa::mdlCambiarLogo($datos);
        return $resultado;
    }
    public static function ctrEliminarLogo($datos)
    {
        $resultado = ModeloEmpresa::mdlCambiarLogo($datos);
        return $resultado;
    }

    public static function ctrCambiarPlantilla($datos)
    {

        $resultado = ModeloEmpresa::mdlCambiarPlantilla($datos);
        return $resultado;
    }

    public static function ctrBienesServiciosSelva($item, $valor, $itembs, $valorbs)
    {

        $resultado = ModeloEmpresa::mdlActualizarBienesServiciosSelva($item, $valor, $itembs, $valorbs);
        return $resultado;
    }

    // PASAR A MODO PRODUCCIÓN EL SISTEMA
    public static function ctrProduccion()
    {

        $resultado = ModeloEmpresa::mdlProduccion();
        echo $resultado;
        $tablas = ModeloEmpresa::mdlProduccionTablas();
    }
    public static function ctrAgregarCampoTablas()
    {

        $campos = ModeloEmpresa::mdlAgregarCampoTabla();
        return $campos;
    }
    public $igv_uno;
    public $igv_dos;
    public  function ctrEmisorIgv()
    {

        $emisor = ControladorEmpresa::ctrEmisor();

        $this->igv_uno = ($emisor['igv'] / 100) + 1;
        $this->igv_dos = $emisor['igv'] / 100;
    }

    public static function ctrCambiarSeguridad()
    {
        $respuesta = ModeloEmpresa::mdlCambiarSeguridad();
        return $respuesta;
    }
 
}
