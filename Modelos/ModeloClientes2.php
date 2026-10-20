<?php
namespace Modelos;
// require_once "conexion.php";
use Conect\Conexion;
use PDO;

class ModeloClientes{

    // MOSTRAR CLIENTES
    public static function mdlMostrarClientes($tabla, $item, $valor){
        
        if($item != null){

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
    
            $stmt->execute();
            return $stmt->fetch();

        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();

        }
       

        $stmt->close();
        $stmt = null;
    }
    // OBJETO MODELO CREAR CLIENTE
    public static function mdlCrearCliente($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, documento, ruc, razon_social, email, telefono,  direccion, ubigeo, fecha_nacimiento) VALUES (:nombre, :documento, :ruc, :razon_social, :email, :telefono, :direccion, :ubigeo, :fecha_nacimiento)");

        $stmt->bindParam(":nombre", $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos['documento'], PDO::PARAM_STR);
        $stmt->bindParam(":ruc", $datos['ruc'], PDO::PARAM_STR);
        $stmt->bindParam(":razon_social", $datos['razon_social'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeo", $datos['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_nacimiento", $datos['fecha_nacimiento'], PDO::PARAM_STR);

        if($stmt->execute()){

            return "ok";

        }else{

            return "error";
        }
        $stmt->close();
        $stmt = null;
    }
    // EDITAR CLIENTE
    public static function mdlEditarCliente($tabla, $datos){

        $stmt = Conexion::conectar();
        $stmt = $stmt->prepare("UPDATE $tabla SET nombre = :nombre, documento = :documento, ruc = :ruc, razon_social = :razon_social, email = :email, telefono = :telefono, direccion = :direccion, fecha_nacimiento = :fecha_nacimiento WHERE id = :id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $datos['documento'], PDO::PARAM_STR);
        $stmt->bindParam(":ruc", $datos['ruc'], PDO::PARAM_STR);
        $stmt->bindParam(":razon_social", $datos['razon_social'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $datos['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_nacimiento", $datos['fecha_nacimiento'], PDO::PARAM_STR);

        if($stmt->execute()) {

            return "ok";

        }else{

            return "error";

        }

        $stmt->close();
        $stmt = null;
    }

    // ELIMINAR CLIENTE
    public static function mdlEliminarCliente($tabla, $datos){
        
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla  WHERE id=:id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);

        if($stmt->execute()){
            return 'ok';
        }else{
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }
    // LISTAR CLIENTES OTRO MÉTODO
    public static function mdlListarClientes(){
        
        $content =  "<tbody class='body-clientes'></tbody>";
		return $content;

    }



//BUSCAR RUC SUNAT
    public static function mdlBuscarRuc($numDoc, $tipoDoc){

        if($tipoDoc == 6){

// Descargar padron reducido: http://www.sunat.gob.pe/descargaPRR/mrc137_padron_reducido.html
// Resultado al descomprimir: padron_reducido_ruc.txt 
$numDoc = $numDoc;

$token =  'd2acd4088895b8305977e33818d656ea';
    
$curl = curl_init();
curl_setopt_array($curl, array(
   CURLOPT_URL => 'https://api.apifacturacion.com/ruc/'.$numDoc,
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
  CURLOPT_CAINFO => dirname(__FILE__)."/../api/cacert.pem" //Comentar si sube a un hosting 
     //para ejecutar los procesos de forma local en windows
    //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
));

 $response = curl_exec($curl);

curl_close($curl);

$empresa = json_decode($response);

    if(isset($empresa->ruc)){
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

}else{
    echo json_encode('error');
}


    }


    if($tipoDoc == 1){ 
        
        $numDoc = $numDoc; 
        
     $token =  'd2acd4088895b8305977e33818d656ea';
     
     $curl = curl_init();
     curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apifacturacion.com/dni/'.$numDoc,
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
       CURLOPT_CAINFO => dirname(__FILE__)."/../api/cacert.pem" //Comentar si sube a un hosting 
          //para ejecutar los procesos de forma local en windows
         //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
     
     ));
     
      $response = curl_exec($curl);
     
     curl_close($curl);
     
     $empresa = json_decode($response);
     
     if(isset($empresa->dni)){
         $datos = array(
             'ruc' => $empresa->dni, 
             'razon_social' => $empresa->cliente, 
            
     );
     
    echo json_encode($datos);
     
     }else{
         echo json_encode('error');
     }
     
     
    }
 }

// BUSCAR CLIENTE EN LA EMISIÓN DE COMPROBANTES
public static function mdlBuscarCliente($tabla, $valor){
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE nombre LIKE :valor OR (documento LIKE :valor AND ruc == '') OR (ruc LIKE :valor AND documento == '')");
    $parametros = array(':valor'=>'%'.$valor.'%');
    
            $stmt->execute($parametros);
            return $stmt->fetchall();
}
   
}