<?php
require_once "../vendor/autoload.php";
use Controladores\ControladorProveedores;
use Controladores\ControladorClientes;
class AjaxProveedores{

    public $idProveedor;
    public function ajaxEditarProveedor(){

        $item = "id";
        $valor = $this->idProveedor;

        $resultado = ControladorProveedores::ctrMostrarProveedores($item, $valor);
        
        echo json_encode($resultado);
    }
    // BUSCAR RUC O DNI
    public $rucProveedor;
    public $tipoDoc;
    public function ajaxBuscarRuc(){       
      
         $numDoc = $this->rucProveedor;
         $tipoDoc = $this->tipoDoc;
        $resultado = ControladorClientes::ctrBuscarRuc($numDoc, $tipoDoc);
        
    }
    // VER SI EXISTE EL CLIENTE EN LA BD
public function ajaxExisteProveedor($numDocumento){
    if (strlen($numDocumento)  == 8 ){
    $item = "documento";
    }
    if (strlen($numDocumento) > 8 ){
    $item = "ruc";
    }
    $valor = $numDocumento;
    $respuesta = ControladorProveedores::ctrMostrarProveedores($item, $valor);
    echo json_encode($respuesta);
}

// BUSCAR CLIENTE PARA COMPROBANTE
public function ajaxBuscarProveedor($numeroDoc){
    $valor = $numeroDoc;
    $respuesta = ControladorProveedores::ctrBucarProveedor($valor);
    // echo json_encode($respuesta);
    foreach($respuesta as $k => $v){
        if($_POST['tipoDocumentoP'] == '1'){
        echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add-p" idProveedor="'.$v['id'].'" >'.++$k.'. '.$v['documento']. ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">'.$v['nombre'].'</b></a></legend>';
        
    }else{
         echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add-p" idProveedor="'.$v['id'].'" >'.++$k.'. '.$v['ruc']. ' - <b style="font-size: 13px; color: #444; font-weight: 600; letter-spacing: 1px;">'.$v['razon_social'].'</b></a></legend>';
    }
    }

}
}

// EDITAR PROVEEDOR
if(isset($_POST['idProveedor'])){

    $idCliente = new AjaxProveedores();
    $idCliente->idProveedor = $_POST['idProveedor'];
    $idCliente->ajaxEditarProveedor();
}
// BUSCAR RUC CLIENTE
if(isset($_POST['rucProveedor'])){
    $rucCliente = new AjaxProveedores();
    $rucCliente-> rucProveedor = trim($_POST['rucProveedor']);
    $rucCliente->tipoDoc = $_POST['tipoDoc'];
    $rucCliente->ajaxBuscarRuc();
}
// SI EXISTE EL  CLIENTE
if(isset($_POST['numDocumentoP'])){    
    $existeCliente = new AjaxProveedores();
    //$existeCliente->numDocumento = $_POST['numDocumento'];
    $existeCliente->ajaxExisteProveedor(trim($_POST['numDocumentoP']));
}
// BUSCAR CLIENTE PARA COMPROBANTE
if(isset($_POST['numeroDocP'])){    
    $existeCliente = new AjaxProveedores();
    //$existeCliente->numeroDoc = $_POST['numDocumento'];
    $existeCliente->ajaxBuscarProveedor(trim($_POST['numeroDocP']));
}



