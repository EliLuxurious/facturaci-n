<?php
session_start();
require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorProveedores;
use Controladores\ControladorReportes;


class DataTablesReportes{

  // DATA_TABLE CLIENTES LISTAR CLIENTES
  public  function dtaReportesCompras(){

 $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
 if($action == 'ajax'){
    // escaping, additionally removing everything that could be (html/javascript-) code
    $fechaini = $_GET['fechaini'];
    $fechafin = $_GET['fechafin'];
    $fechai = str_replace('/', '-', $fechaini);
    $fechaInicial = date('Y-m-d', strtotime($fechai));

    $fechaf = str_replace('/', '-', $fechafin);
    $fechaFinal = date('Y-m-d', strtotime($fechaf));


    $tipocomp = $_GET['tipocomp'];
    $searchR = $_GET['searchRC'];
    $selectnum = $_GET['selectnum'];
    $aColumns = array('nombre', "CONCAT(serie,'-',correlativo)", 'ruc', 'documento');//Columnas de busqueda

  
    $sTable = 'compra';
    
    $sTable2 = 'proveedores';
    $sWhere = "";

    if($tipocomp != '07' && $tipocomp != '08'){

   if (isset($searchR) && isset($tipocomp))

   {   
     if($tipocomp == '01' || $tipocomp == '02' || $tipocomp == '03'){
       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= "tipocomp=$tipocomp AND anulado = 'n' AND (tipocomp='01' || tipocomp='02' || tipocomp='03')  AND  fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal' AND ".$aColumns[$i]." LIKE '%".$searchR."%' OR ";
       }
       $sWhere = substr_replace( $sWhere, "", -3 );
       $sWhere .= ')';
       }
     if($tipocomp == '00'){
       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= "anulado = 'n' AND (tipocomp='01' || tipocomp='03')  AND  fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal' AND ".$aColumns[$i]." LIKE '%".$searchR."%' OR ";
       }
       $sWhere = substr_replace( $sWhere, "", -3 );
       $sWhere .= ')';
       }
  }

   if(isset($tipocomp) && $searchR == ''){

     if($tipocomp != '00'){
     $sWhere = "WHERE tipocomp=$tipocomp AND anulado = 'n'  AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
     }else{
       $sWhere = "WHERE anulado = 'n' AND (tipocomp='01' || tipocomp='03')  AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
     }
   }
  }
  
  else{
    if (isset($searchR) && isset($tipocomp))
   {

       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= "tipocomp=$tipocomp AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal' AND ".$aColumns[$i]." LIKE '%".$searchR."%' OR ";
       }
       $sWhere = substr_replace( $sWhere, "", -3 );
       $sWhere .= ')';
   

  }
  }
   	//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
   include_once 'pagination-reportes-compras.php';
    //include pagination file
   $per_page = $selectnum; //how much records you want to show
   $adjacents  = 4; //gap between pages after number of adjacents
   $offset = ($page - 1) * $per_page;
  
   //Count the total number of row in your table*/
   $pdo =  Conexion::conectar();
   $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $sTable t1 INNER JOIN $sTable2 t2 ON t1.codproveedor=t2.id  $sWhere");
   $totalRegistros = $totalRegistros->fetch()['numrows'];
   $tpages = ceil($totalRegistros/$per_page);
   $reload = './index.php';
   //main query to fetch the data
   $pdo =  Conexion::conectar();


   
     $registros = $pdo->prepare("SELECT t_compras.id, t_proveedor.nombre, t_compras.igv, t_compras.fecha_emision, t_compras.tipocomp, t_compras.serie, t_compras.codmoneda, t_compras.correlativo, t_compras.subtotal, t_compras.total, t_compras.serie_correlativo, t_compras.codproveedor, t_compras.tipodoc, t_compras.serie_ref, t_compras.correlativo_ref, t_proveedor.razon_social, t_proveedor.ruc, t_proveedor.documento FROM  $sTable t_compras INNER JOIN $sTable2 t_proveedor ON t_compras.codproveedor=t_proveedor.id  $sWhere ORDER BY id DESC LIMIT $offset,$per_page");
   
   
   $registros->execute();

   $registros=$registros->fetchall();     
       
      if($totalRegistros > 0){   

             foreach($registros as $key => $value):
              $item = 'id';
             $valor = $value['codproveedor'];
              $proveedores = ControladorProveedores::ctrMostrarProveedores($item, $valor);
              if($value['tipodoc'] == '6' ){
                $verproveedor = $proveedores['razon_social'].'-'.$proveedores['ruc'];
              }else{
                $verproveedor = $proveedores['nombre'].'-'.$proveedores['documento'];
              }

              if($tipocomp == '07'){
                $serieCorrelativo = "Nota de crédito N°-".$value['serie_correlativo']."<br>
                que afecta a coprobante N°- ".$value['serie_ref']."-".$value['correlativo_ref'];
              }
              else if($tipocomp == '08'){
                $serieCorrelativo = "Nota de dédito N°-".$value['serie_correlativo']."<br>
                que afecta a coprobante N°- ".$value['serie_ref']."-".$value['correlativo_ref'];
              }else{
                $serieCorrelativo = $value['serie_correlativo'];
              }
                echo "<tr>
                      <td>".++$key."</td>
                      <td>".date_format(date_create($value['fecha_emision']), 'd/m/Y')."</td>
                      <td>".$serieCorrelativo."</td>
                      <td>".$verproveedor."</td>
                      <td>".$value['igv']."</td>
                      <td>".$value['subtotal']."</td>
                      <td>".$value['total']."</td>
                      <td style='text-align:center;'><button class='btn btn-print-compra' idCompra='".$value['id']."' data-toggle='modal' data-target='#modalImprimir'>+</button></td>
                      <td style='text-align:center;'>
                      <div class='dropdown'>
                      <button class='btn btn-danger btn-xs dropdown-toggle' type='button' id='dropdownMenu1' 
                              data-toggle='dropdown' aria-expanded='true'>
                        <i class='fa fa-cog fa-lg'></i>
                        <span class='caret'></span>
                      </button>
                      <ul class='dropdown-menu dropdown-menu-right' role='menu' aria-labelledby='dropdownMenu1' style='font-size:17px'>";
                      if($_SESSION['perfil'] == 'Administrador'){
                       echo "<li role='presentation'><a role='menuitem' tabindex='-1' href='#' idCompra='".$value['id']."' class='btn-anular-compra'><i class='fas fa-ban' style='color:red;'></i> Anular compra</a></li>";
                        }
                      echo" <li role='presentation'><a role='menuitem' tabindex='-1' href='nueva-compra'><i class='fa fa-plus'></i> Nueva compra</a></li>
                       
                      </ul>
                    </div>
                                          
                      </td>
                      </tr>";

              $totaligv += $value['igv'];
              $subtotal += $value['subtotal'];
              $total += $value['total'];
             endforeach;
             

             echo "<tr>
                      <td colspan='4'></td>
                      <td colspan=''>".$moneda. number_format($totaligv,2)."</td>
                      <td colspan=''>".$moneda. number_format($subtotal,2)."</td>
                      <td colspan=''>".$moneda. number_format($total,2)."</td>
                  </tr>";

            // widgets-----------
              $tabla = 'compra'; 
              $tipoc = '01';      
               $facturas = ControladorReportes::ctrSumaCompras($tabla, $tipoc, $fechaInicial, $fechaFinal);
              
                 $totalf = $moneda. number_format($facturas['total'],2);
               
                 echo "<script>
                      $('.t-f').html(`{$totalf}`);
                      </script>";
              
              $tabla = 'compra'; 
              $tipoc = '03';
                   
               $boletas = ControladorReportes::ctrSumaCompras($tabla, $tipoc, $fechaInicial, $fechaFinal);
                $totalb = $moneda. number_format($boletas['total'],2);

                   echo "<script>
                      $('.t-b').html(`{$totalb}`);
                      </script>";

              $tabla = 'compra'; 
              $tipoc = '07';      
               $notac = ControladorReportes::ctrSumaCompras($tabla, $tipoc, $fechaInicial, $fechaFinal);
                $totalnc = $moneda. number_format($notac['total'],2);
                
                   echo "<script>
                      $('.t-nc').html(`{$totalnc}`);
                      </script>";

              $tabla = 'compra'; 
              $tipoc = '08';      
               $notad = ControladorReportes::ctrSumaCompras($tabla, $tipoc, $fechaInicial, $fechaFinal);
                $totalnd = $moneda. number_format($notad['total'],2);
                
                    
                   echo "<script>
                      $('.t-nd').html(`{$totalnd}`);
                      </script>";

              $sub_total = ($boletas['total']+$facturas['total']+$notad['total']+$notaventas['total']);
                  $totalneto = $moneda. number_format($sub_total - $notac['total'],2);
                    echo "<script>
                    $('.t-neto').html(`{$totalneto}`);
                    </script>";
              // fin widgets ---------------
                  
                   $paginador = new PaginacionRC();
                   $paginador = $paginador->paginarComprobantes($reload, $page, $tpages, $adjacents);  

              echo"<tr>                
              <td colspan='10' style='text-align:center;'>".$paginador."</td>
             </tr>";

            }else{
             

              echo"<tr>   

              <td colspan='10' style='text-align:center;'> <div class='result-report'></div></td>
             </tr>";
            
              echo "<script>
                  $('.result-report').html(`<i class='fas fa-times'></i> NO SE HA ENCONTRADO RESULTADOS`).fadeIn(500, function(){
                    $('.result-report').delay(5000).fadeOut(500);
                  });                        
               </script>";

               echo "<script>
               $('.t-nc, .t-f, .t-b, .t-nv').html(`S/ 0.00`);
               </script>";
            }
          }
        }



  }
    


       if(isset($_REQUEST['reportesCompras'])){
         if($_REQUEST['reportesCompras'] == "reportesCompras"){
        $dataReportes = new DataTablesReportes();
        $dataReportes->dtaReportesCompras();
       }
      }
     