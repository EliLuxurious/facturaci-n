<?php
require_once "../vendor/autoload.php";
use Controladores\ControladorClientes;
class AjaxClientes{

    // TRAER LOS CLIENTES PARA  EDITAR
    public $idCliente;
    public function ajaxEditarCliente(){

        $item = "id";
        $valor = $this->idCliente;

        $resultado = ControladorClientes::ctrMostrarClientes($item, $valor);
        
        echo json_encode($resultado);
    }

    // ELIMINAR CLIENTE
    public $idEliminarCliente;
    public function ajaxEliminarCliente(){

        $datos = $this->idEliminarCliente;
        $resultado = ControladorClientes::ctrEliminarCliente($datos);

    }
    // BUSCAR RUC O DNI
    public $rucCliente;
    public $tipoDoc;
    public function ajaxBuscarRuc(){       
      
         $numDoc = $this->rucCliente;
         $tipoDoc = $this->tipoDoc;
        $resultado = ControladorClientes::ctrBuscarRuc($numDoc, $tipoDoc);
        
    }
    // VER SI EXISTE EL CLIENTE EN LA BD
public function ajaxExisteCliente($numDocumento){
    if (strlen($numDocumento)  == 8 ){
    $item = "documento";
    }
    if (strlen($numDocumento) > 8 ){
    $item = "ruc";
    }
    $valor = $numDocumento;
    $respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);
    echo json_encode($respuesta);
}

// BUSCAR CLIENTE PARA COMPROBANTE
public function ajaxBuscarCliente($numeroDoc){
    $valor = $numeroDoc;
    $respuesta = ControladorClientes::ctrBucarCliente($valor);
    // echo json_encode($respuesta);
    foreach($respuesta as $k => $v){
        if($_POST['tipoDocumento'] == '6' AND $v['ruc'] != ''){
       
         echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add" idCliente="'.$v['id'].'" > '.$v['ruc']. ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">'.$v['razon_social'].'</b></a></legend>';
    }else{
        if($_POST['tipoDocumento'] != '6' AND $v['documento'] != ''){
        
            echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add" idCliente="'.$v['id'].'" > '.$v['documento']. ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">'.$v['nombre'].'</b></a></legend>';  
}
}
    }

}
}

// EDITAR CLIENTES
if(isset($_POST['idCliente'])){

    $idCliente = new AjaxClientes();
    $idCliente->idCliente = $_POST['idCliente'];
    $idCliente->ajaxEditarCliente();
}
// ELIMINAR CLIENTE
if(isset($_POST['idEliminarCliente'])){

    $eliminar = new AjaxClientes();
    $eliminar->idEliminarCliente = $_POST['idEliminarCliente'];
    $eliminar->ajaxEliminarCliente();
}
// BUSCAR RUC CLIENTE
if(isset($_POST['rucCliente'])){
    $rucCliente = new AjaxClientes();
    $rucCliente-> rucCliente = trim($_POST['rucCliente']);
    $rucCliente->tipoDoc = $_POST['tipoDoc'];
    $rucCliente->ajaxBuscarRuc();
}
// SI EXISTE EL  CLIENTE
if(isset($_POST['numDocumento'])){    
    $existeCliente = new AjaxClientes();
    //$existeCliente->numDocumento = $_POST['numDocumento'];
    $existeCliente->ajaxExisteCliente(trim($_POST['numDocumento']));
}
// BUSCAR CLIENTE PARA COMPROBANTE
if(isset($_POST['numeroDoc'])){    
    $existeCliente = new AjaxClientes();
    //$existeCliente->numeroDoc = $_POST['numDocumento'];
    $existeCliente->ajaxBuscarCliente(trim($_POST['numeroDoc']));
}



