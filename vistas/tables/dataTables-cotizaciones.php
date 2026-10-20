<?php
session_start();

require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;



class DataTablesCotizaciones{

    public function dtaListarCotizaciones(){

        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        if($action == 'ajax'){
           // escaping, additionally removing everything that could be (html/javascript-) code
   
                       
                       
          $searchCotiza = $_GET['searchCotiza'];
           $selectnum = $_GET['selectnum'];   
           $fechaInicial = $_GET['fechaInicial'];
           $fechaFinal = $_GET['fechaFinal'];
           
           $aColumns = array('serie', 'correlativo');//Columnas de busqueda
           $sTable = 'cotizaciones';
           $sWhere = "";
          if(isset($searchCotiza))
          {
              $sWhere = "WHERE (";
              for ( $i=0 ; $i<count($aColumns) ; $i++ )
              {
                  $sWhere .= $aColumns[$i]." LIKE '%".$searchCotiza."%' OR ";
              }
              $sWhere = substr_replace( $sWhere, "", -3 );
              $sWhere .= ')';
          }
          if($fechaInicial != null && $fechaFinal != null){
          if($fechaInicial == $fechaFinal){
            $sWhere = "WHERE fecha_emision LIKE '%$fechaFinal%'";

          }
          if($fechaInicial != $fechaFinal){
            $sWhere = "WHERE fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
          }
          }
            //pagination variables
           $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
          include_once 'pagination.php';
           //include pagination file
          $per_page = $selectnum; //how much records you want to show
          $adjacents  = 4; //gap between pages after number of adjacents
          $offset = ($page - 1) * $per_page;
         
          //Count the total number of row in your table*/
          $pdo =  Conexion::conectar();
          $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $sTable  $sWhere");
          $totalRegistros = $totalRegistros->fetch()['numrows'];
          $tpages = ceil($totalRegistros/$per_page);
          $reload = './index.php';
          //main query to fetch the data
          $pdo =  Conexion::conectar();
          $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere ORDER BY id DESC LIMIT $offset,$per_page");
          $registros->execute();
       
          $registros=$registros->fetchall(); 

         foreach($registros as $k => $v){
            $item = 'id';
            $valor = $v['codcliente'];
            $cliente = ControladorClientes::ctrMostrarClientes($item, $valor);
            if($v['tipodoc'] == 6) {
               $nombreRazon = $cliente['razon_social'];
                $doc = $cliente['ruc'];
            }else{
                
             $nombreRazon = $cliente['nombre'];
            $doc = $cliente['documento'];
            }
            if($v['codmoneda'] == 'PEN'){
                $moneda = 'S/';

            }else{
                $moneda = 'US$';
            }

           
                echo '<tr>
                <td>'.++$k.'</td>
                <td>'.date_format(date_create($v['fechahora']),'d/m/Y H:i:s').'</td>
                <td>Cotización N°: '.$v['serie'].'-'.$v['correlativo'].'</td>
                <td>'.$nombreRazon.'<br>'.$doc.'</td>
                <td>'.$moneda.' '.number_format($v['total'],2).'</td>
                <td>
                <div class="contenedor-print-comprobantes">
                <form id="printC" name="printC" method="post" action="vistas/print/printCot/" target="_blank">
              
               <input type="hidden" id="idCo" name="idCo" value="'.$v['id'].'">
                <button class="printA4"  id="printA4" idComp="'.$v['id'].'" ></button>
                
                </form></div>
                </td>
                <td>
                <div class="contenedor-print-comprobantes">
                
                <button class="senda4" idComp="'.$v['id'].'"></button>
                
                </div>
                </td>
               <td styele="text-align: center;">';
               if($v['tipodoc']== 6){
                echo '<form class="form" action="crear-factura" method="post">';
                $nomC = 'Crear Factura';
               }else{
                echo '<form class="form" action="crear-boleta" method="post">';
                $nomC = 'Crear Boleta';
               }
               

               echo '<input type="hidden" class="numCorrelativo" name="numCorrelativo" value="'.$v['serie_correlativo'].'">
                          
                 <button class="btn btn-primary center-block"><i class="fas fa-plus"> </i> <span>' .$nomC.'</span></button>
               
                 </form>

               </td>
                </tr>';
         }
 }
          $paginador = new Paginacion();
          $paginador = $paginador->paginarCotizaciones($reload, $page, $tpages, $adjacents);  
            echo"<tr>
                <td colspan='8' style='text-align:center;'>".$paginador."</td>
                </tr>";
   

}
}


    if($_REQUEST['cotizar'] == 'cotizar'){
    $guias = new DataTablesCotizaciones();
    $guias-> dtaListarCotizaciones();
    }

?>