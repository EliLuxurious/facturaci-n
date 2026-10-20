<?php
require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorNotaCredito;
use Controladores\ControladorNotaDebito;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorReportes;


class DataTablesReportes{

  // DATA_TABLE CLIENTES LISTAR CLIENTES
  public  function dtaReportes(){

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
    $searchR = $_GET['searchR'];
    $selectnum = $_GET['selectnum'];
    $aColumns = array('nombre', "CONCAT(serie,'-',correlativo)", 'ruc', 'documento');//Columnas de busqueda

    
    if($tipocomp == '07'){
      $sTable = 'nota_credito';
    }
    
    if($tipocomp == '08'){
      $sTable = 'nota_debito';
    }
   if($tipocomp =='01' || $tipocomp == '02' || $tipocomp == '03' || $tipocomp == '00'){
    $sTable = 'venta';
    }
    $sTable2 = 'clientes';
    $sWhere = "";

    if($tipocomp != '07' && $tipocomp != '08'){

   if (isset($searchR) && isset($tipocomp))

   {   
     if($tipocomp == '01' || $tipocomp == '02' || $tipocomp == '03'){
       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= "tipocomp=$tipocomp AND anulado = 'n' AND (tipocomp='01' || tipocomp='02' || tipocomp='03')  AND id_nc IS NULL AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal' AND ".$aColumns[$i]." LIKE '%".$searchR."%' OR ";
       }
       $sWhere = substr_replace( $sWhere, "", -3 );
       $sWhere .= ')';
       }
     if($tipocomp == '00'){
       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= "anulado = 'n' AND (tipocomp='01' || tipocomp='03')  AND id_nc IS NULL AND fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal' AND ".$aColumns[$i]." LIKE '%".$searchR."%' OR ";
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
   include_once 'pagination-reportes.php';
    //include pagination file
   $per_page = $selectnum; //how much records you want to show
   $adjacents  = 4; //gap between pages after number of adjacents
   $offset = ($page - 1) * $per_page;
  
   //Count the total number of row in your table*/
   $pdo =  Conexion::conectar();
   $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $sTable t1 INNER JOIN $sTable2 t2 ON t1.codcliente=t2.id  $sWhere");
   $totalRegistros = $totalRegistros->fetch()['numrows'];
   $tpages = ceil($totalRegistros/$per_page);
   $reload = './index.php';
   //main query to fetch the data
   $pdo =  Conexion::conectar();


   if ($tipocomp == '07' || $tipocomp == '08'){
      $registros = $pdo->prepare("SELECT t_ncd.id, cliente.nombre, t_ncd.igv, t_ncd.fecha_emision, t_ncd.tipocomp, t_ncd.serie, t_ncd.codmoneda, t_ncd.correlativo, t_ncd.total,  cliente.razon_social, t_ncd.tipocomp_ref, t_ncd.seriecorrelativo_ref, cliente.ruc, cliente.documento FROM  $sTable t_ncd INNER JOIN $sTable2 cliente ON t_ncd.codcliente=cliente.id  $sWhere ORDER BY id DESC LIMIT $offset,$per_page");
   }else{
     $registros = $pdo->prepare("SELECT t_ventas.id, t_cliente.nombre, t_ventas.igv, t_ventas.fecha_emision, t_ventas.tipocomp, t_ventas.serie, t_ventas.codmoneda, t_ventas.correlativo, t_ventas.subtotal, t_ventas.total, t_ventas.serie_correlativo, t_ventas.id_nc, t_ventas.id_nd,  t_cliente.razon_social, t_cliente.ruc, t_cliente.documento FROM  $sTable t_ventas INNER JOIN $sTable2 t_cliente ON t_ventas.codcliente=t_cliente.id  $sWhere ORDER BY id DESC LIMIT $offset,$per_page");
   }
   
   $registros->execute();

   $registros=$registros->fetchall();     
       
      if($totalRegistros > 0){   

             foreach($registros as $key => $value):
              

                   

              if($value['codmoneda'] == 'PEN'){
                $moneda = "S/ ";
              }else{
                $moneda = "UD$ ";
              }
           echo  "<tr class='id".$value['id']."'>
               <td> ".(++$key)."</td>
               <td>".date_format(date_create($value['fecha_emision']), 'd/m/Y')."</td>";

               if($tipocomp == '07'){
                  
                   
                  echo "<td>".$value['serie'].'-'.$value['correlativo']." AFECTADO N° ".$value['seriecorrelativo_ref']."</td>";
               }
               else if($tipocomp == '08'){
                 
                  echo "<td>".$value['serie'].'-'.$value['correlativo']." AFECTADO N° ".$value['seriecorrelativo_ref']."</td>";
               }else{ 
                 if($value['id_nc'] !=  null ){
                    $item = "id";
                   $valor = $value['id_nc'];
                   $notaC = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);
                   $comprobante_afectado = $value['serie_correlativo']."<br/> AFECTADA - NOTA DE CRÉDITO N°: ".$notaC['serie']."-".$notaC['correlativo']; 
                   echo "<td>".$comprobante_afectado."</td>";
                 }
                 else if($value['id_nd'] !=  null ){
                    $item = "id";
                   $valor = $value['id_nd'];
                   $notaD = ControladorNotaDebito::ctrMostrarNotaDebito($item, $valor);
                   $comprobante_afectado = $value['serie_correlativo']."<br/> AFECTADA - NOTA DE DÉBITO N°: ".$notaD['serie']."-".$notaD['correlativo']; 
                   echo "<td>".$comprobante_afectado."</td>";
                 }
                
                 else{
                     
                    $serie_correlativo_BF = $value['serie_correlativo'];
                    echo "<td>".$serie_correlativo_BF."</td>";
                 }
               }
               
              
              
               if($tipocomp == '01' || $value['tipocomp_ref'] == '01'){                
                   
                  echo "<td> ".$value['razon_social']."<br>R.U.C. ".$value['ruc']."</td>";
                 
                               
               }
               if($tipocomp == '03' || $value['tipocomp_ref'] == '03'){

              echo "<td> ".$value['nombre']."<br>D.N.I. ".$value['documento']."</td>";

               }
               if($tipocomp == '02' || $value['tipocomp_ref'] == '02'){

              echo "<td> ".$value['nombre']."<br>D.N.I. ".$value['documento']."</td>";

               }
               if($tipocomp == '00' || $value['tipocomp_ref'] == '03' || $value['tipocomp_ref'] == '01'){
                  if($value['tipocomp'] == '03'){
              echo "<td> ".$value['nombre']."<br>D.N.I. ".$value['documento']."</td>";
                  }
                  if($value['tipocomp'] == '01'){
                    echo "<td> ".$value['razon_social']."<br>R.U.C. ".$value['ruc']."</td>";
                  }
               }
              
             echo "<td> ".$moneda. number_format($value['igv'],2)."</td>
               <td> ".$moneda. number_format($value['total'],2)."</td>";      
               
               
              echo'<td>
               
               <div class="contenedor-print-comprobantes">';
               if($tipocomp == '07'){
                echo '<input type="hidden" id="tipocomp" name="tipocomp" value="07">';
               }
               else if($tipocomp == '08'){
                echo '<input type="hidden" id="tipocomp" name="tipocomp" value="08">';
               }
               else{
                 echo '<input type="hidden" id="tipocomp" name="tipocomp" value="">';
               }
               
              echo '<input type="radio" class="a4'.$value['id'].'" id="a4" name="a4" value="A4">
              <input type="radio" class="tk'.$value['id'].'" id="tk" name="a4" value="TK">
              <input type="hidden" id="idCo" name="idCo" value="'.$value['id'].'">
               <button class="printA4"  id="printA4" idComp="'.$value['id'].'" data-toggle="modal" data-target="#modalImprimir" ></button>
               <button class="printT" id="printT" idComp="'.$value['id'].'" data-toggle="modal" data-target="#modalImprimir"></button>
               </form>
               
               </div>
               
               </td>


               <td>
               <div class="contenedor-print-comprobantes">

               <button class="s-success"></button>

               </div>
               
               </td>
               <td class="eliminarnota'.$value['id'].'">

               <div class="contenedor-print-comprobantes">';
               
               if($tipocomp == '07'){
                echo '<input type="hidden" id="tipocomp" name="tipocomp" value="07">';
               }
               else if($tipocomp == '08'){
                echo '<input type="hidden" id="tipocomp" name="tipocomp" value="08">';
               }
               else{
                 echo '<input type="hidden" id="tipocomp" name="tipocomp" value="">';
               }
               echo '<button class="senda4" idComp="'.$value['id'].'"></button>';

               if($tipocomp == '02' && $value['total'] > 0){

                 echo '<button  class="anular-nota" idComp="'.$value['id'].'"><i class="fas fa-trash-alt fa-lg"></i></button>';
               } if($tipocomp == '02' && $value['total'] == 0){
                 echo '<button  class="anulada-nota" idComp="'.$value['id'].'"><i class="fas fa-times-circle fa-lg"></i></button>';
               }
               
               echo '</div>
               
               </td>
                                
             </tr>';
             $totaligv+= $value['igv'];
             $total += $value['total'];
              
             endforeach;
             
             echo "<tr>
                      <td colspan='4'></td>
                      <td colspan=''>".$moneda. number_format($totaligv,2)."</td>
                      <td colspan=''>".$moneda. number_format($total,2)."</td>
                  </tr>";

            // widgets-----------
              $tabla = 'venta'; 
              $tipoc = '01';      
               $facturas = ControladorReportes::ctrSumaFacturas($tabla, $tipoc, $fechaInicial, $fechaFinal);
              
                 $totalf = $moneda. number_format($facturas['total'],2);
               
                

                   echo "<script>
                      $('.t-f').html(`{$totalf}`);
                      </script>";

              $tabla = 'venta'; 
              $tipoc = '02';      
               $notaventas = ControladorReportes::ctrSumaFacturas($tabla, $tipoc, $fechaInicial, $fechaFinal);
                $totalnv = $moneda. number_format($notaventas['total'],2);

                   echo "<script>
                      $('.t-nv').html(`{$totalnv}`);
                      </script>";
              
              $tabla = 'venta'; 
              $tipoc = '03';
                   
               $boletas = ControladorReportes::ctrSumaFacturas($tabla, $tipoc, $fechaInicial, $fechaFinal);
                $totalb = $moneda. number_format($boletas['total'],2);

                   echo "<script>
                      $('.t-b').html(`{$totalb}`);
                      </script>";

              $tabla = 'nota_credito'; 
              $tipoc = '07';      
               $notac = ControladorReportes::ctrSumaNotas($tabla, $tipoc, $fechaInicial, $fechaFinal);
                $totalnc = $moneda. number_format($notac['total'],2);
                
                   echo "<script>
                      $('.t-nc').html(`{$totalnc}`);
                      </script>";

              $tabla = 'nota_debito'; 
              $tipoc = '08';      
               $notad = ControladorReportes::ctrSumaNotas($tabla, $tipoc, $fechaInicial, $fechaFinal);
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
                  
                   $paginador = new PaginacionR();
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
    


       if(isset($_REQUEST['reportes'])){
         if($_REQUEST['reportes'] == "reportes"){
        $dataReportes = new DataTablesReportes();
        $dataReportes->dtaReportes();
       }
      }
     