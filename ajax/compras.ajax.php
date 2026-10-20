<?php
session_start();
require_once "../vendor/autoload.php";
use Controladores\ControladorProveedores;
use Controladores\ControladorProductos;
use Controladores\ControladorCompras;
use Controladores\ControladorSunat;

class AjaxCompras{

    public static function ajaxLlenarCarrito(){

        $datosCarrito = $_POST; 
       
        $respuesta = ControladorCompras::ctrLlenarCarrito($datosCarrito);
    }

    public $idEliminarCarroC;
public function ajaxEliminarItemCarritoC(){
    $idEliminar = $this->idEliminarCarroC;
   
    //Como antes, usamos extract() por comodidad, pero podemos no hacerlo tranquilamente
    $carritoC = $_SESSION['carritoC'];
    //Asignamos a la variable $carro los valores guardados en la sessión
    unset($carritoC[$idEliminar]);
 $carritoC = array_values($carritoC);
  $_SESSION['carritoC'] = $carritoC;
  
  $respuesta = ControladorCompras::ctrLoadCarro(null);
    
}
public $descuentoGlobal;
public function ajaxDescuentoGlobal(){
    $descuentoGlobal = $this->descuentoGlobal;
    $respuesta = ControladorCompras::ctrLoadCarro($descuentoGlobal);
}
//  ELIMINAR TODOS LOS ITEMS DEL CARRITO
public $eliminarCarro;
public function ajaxEliminarCarrito(){
    $eliminarCarro = $this->eliminarCarro;

//Como antes, usamos extract() por comodidad, pero podemos no hacerlo tranquilamente
$carritoC=$_SESSION['carritoC'];
//Asignamos a la variable $carro los valores guardados en la sessión
unset($carritoC);
//la función unset borra el elemento de un array que le pasemos por parámetro. En este
//caso la usamos para borrar el elemento cuyo id le pasemos a la página por la url 
$_SESSION['carritoC']=$carritoC;
//Finalmente, actualizamos la sessión, como hicimos cuando agregamos un producto y volvemos al catálogo
//header("Location:catalogo.php?".SID);
$respuesta = ControladorCompras::ctrLoadCarro(null);

}
    public function ajaxGuardarCompra(){

        $datos = $_POST;
         $respuesta = ControladorCompras::ctrGuardarCompra($datos);
       
    }

    public function ajaxNotaCD(){
        $tipoComprobante = $_POST['tipoComprobante'];
        if($tipoComprobante == '07' || $tipoComprobante == '08'){
            echo '
            
            <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">DATOS DEL COMPROBANTE QUE MODIFICA:</legend>

            <div class="col-md-3 col-xs-6">
              <div class="form-group">
              <div class="input-group">                                   
             <span class="input-group-addon"><i class="fa fa-key"></i></span>
           
              <select  class="form-control" name="compModificar" id="compModificar" value="" >
                  <option value="01">Factura</option>
                  <option value="03">Boleta</option>
                 
              </select>                                                                
              </div>
              </div>
            </div>
             <!-- ENTRADA SERIE DOC-->
             <div class="col-md-2 col-xs-6">
            <div class="form-group">
             <div class="input-group">
             <span class="input-group-addon"><i class="fas fa-barcode"></i></span>
             <input type="text" class="form-control" name="serieModificar" id="serieModificar" required placeholder="Serie">
              </div>                               
            </div>
            </div>
                   <!-- ENTRADA CORRELATIVO Modificar-->
            <div class="col-md-2 col-xs-6">
            <div class="form-group">
             <div class="input-group">
             <span class="input-group-addon"><i class="fa fa-file-invoice"></i></span>
             <input type="text" class="form-control" name="correlativoModificar" id="correlativoModificar" required placeholder="Correlativo">
              </div>                               
            </div>
            </div>

            <div class="col-md-3 col-xs-6">
              <div class="form-group">
              <div class="input-group">                                   
             <span class="input-group-addon"><i class="fa fa-file-invoice"></i></span>
           
              <select  class="form-control" name="motivoModificar" id="motivoModificar" value="" >';

              $item = "tipo";
              if($tipoComprobante == '07'){
              $valor = 'C';
              }
              if($tipoComprobante == '08'){
              $valor = 'D';
              }
              $codigo = null;
              $motivo = ControladorSunat::ctrMostrarTablaParametrica($item, $valor, $codigo);
            foreach($motivo as $k => $value) {
               echo '<option value="'.$value['codigo'].'">'.$value['descripcion'].'</option>';
            }   
                 
             echo '</select>                                                                
              </div>
              </div>
            </div>

                     <!-- ENTRADA FECHA DOC-->
                     <div class="col-md-2 col-xs-6">
            <div class="form-group">
             <div class="input-group">
             <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
             <input type="text" class="form-control" name="fechaModificar" id="fechaModificar"  value="'.date("d/m/Y").'" required>
              </div>                               
            </div>
            </div>
            
            
            
            
            ';
            echo "<script>
            $('#fechaModificar').datepicker({
                autoclose: true,
                'language' : 'es'
                })
            </script>";
        }

      

    }
    public function ajaxAnularCompra(){
        $idCompra = $_POST['idCompra'];
        $respuesta = ControladorCompras::ctrAnularCompra($idCompra);
        echo $respuesta;
    } 
    public function ajaxBuscarProducto(){
        $valor = $_POST['buscarP'];
        $respuesta = ControladorCompras::ctrBucarProducto($valor);
        if($respuesta){

            foreach($respuesta as $k => $v){
                echo '<legend style="margin:0px !important; padding:4px !important; font-size: 17px;"><a href="#" class="btn-add-item" idProducto="'.$v['id'].'" >'.++$k.'. '.$v['codigo']. ' - <b style="font-size: 14px; color: #444; font-weight: 600; letter-spacing: 1px;">'.$v['descripcion'].'(S/ '.$v['precio_unitario'].')</b></a></legend>';
            }
        }

     }

     public function ajaxMostrarProductos(){
         $item = 'id';
         $valor = $_POST['idProducto'];
         $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor);
         echo json_encode($respuesta);
     }
}

if (isset($_POST['subtotal'])){
    $objCarrito = new AjaxCompras();
    $objCarrito->ajaxLlenarCarrito();
}
//DESCUENTO GLOBAL
if(isset($_POST['descontarG'])){
    $objDesc = new AjaxCompras();
    $objDesc->descuentoGlobal = $_POST['descuentoGlobalC'];
    $objDesc->ajaxDescuentoGlobal();
}
// ELIMINAR ITEM DEL CARRO
if(isset($_POST['idEliminarCarroC'])){
    $objEliminarItemCarro = new AjaxCompras();
    $objEliminarItemCarro-> idEliminarCarroC = $_POST['idEliminarCarroC'];
    $objEliminarItemCarro->ajaxEliminarItemCarritoC();
}
// ELIMINAR CARRO
if(isset($_POST['eliminarCarro'])){
    $objEliminarCarro = new AjaxCompras();
    $objEliminarCarro-> eliminarCarro = $_POST['eliminarCarro '];
    $objEliminarCarro->ajaxEliminarCarrito();
}
if (isset($_POST['docIdentidad'])){
    $objCompras = new AjaxCompras();
    $objCompras->ajaxGuardarCompra();
}
if (isset($_POST['tipoComprobante'])){
    $objCompras = new AjaxCompras();
    $objCompras->ajaxNotaCD();
}
if (isset($_POST['idCompra'])){
    $objComprasBaja = new AjaxCompras();
    $objComprasBaja->ajaxAnularCompra();
}
if (isset($_POST['buscarProducto'])){
    $objProducto = new AjaxCompras();
    $objProducto->ajaxBuscarProducto();
}
if (isset($_POST['idProducto'])){
    $objProducto = new AjaxCompras();
    $objProducto->ajaxMostrarProductos();
}