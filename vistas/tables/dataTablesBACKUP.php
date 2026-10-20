<?php
require_once "../../modelos/conexion.php";
require_once "../../modelos/clientes.modelo.php";
require_once "../../controladores/clientes.controlador.php";
require_once "../../controladores/nota-credito.controlador.php";
require_once "../../modelos/categorias.modelo.php";
require_once "../../controladores/categorias.controlador.php";
require_once "../../controladores/envio-sunat.controlador.php";
require_once "../../controladores/resumen-diario.controlador.php";
require_once "../../modelos/envio-sunat.modelo.php";
require_once "../../modelos/resumen-diario.modelo.php";
require_once "../../modelos/nota-credito.modelo.php";

class DataTables{

  // DATA_TABLE CLIENTES LISTAR CLIENTES
  public  function dtaClientes(){

 $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
 if($action == 'ajax'){
    // escaping, additionally removing everything that could be (html/javascript-) code
    $search = $_GET['search'];
    $selectnum = $_GET['selectnum'];
    $aColumns = array('nombre', 'documento');//Columnas de busqueda
    $sTable = 'clientes';
    $sWhere = "";
   if (isset($search))
   {
       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= $aColumns[$i]." LIKE '%".$search."%' OR ";
       }
       $sWhere = substr_replace( $sWhere, "", -3 );
       $sWhere .= ')';
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
   $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page");
   $registros->execute();

   $registros=$registros->fetchall();     
       
         
             foreach($registros as $key => $value):
   
           echo  "<tr class='id".$value['id']."'>
               <td> ".(++$key)."</td>
               <td>". $value['nombre']."</td>
               <td>".$value['documento']."</td>
               <td> ".$value['email']."</td>
               <td> ".$value['telefono']."</td>
               <td> ".$value['direccion']."</td>
               <td> ".$value['fecha_nacimiento']."</td>
               <td> ".$value['compras']."</td>
               <td> ".$value['ultima_compra']."</td>
               <td> ".$value['fecha']."</td>
                         <td>
                 <div class='btn-group'>

               <button class='btn btn-warning btnEditarCliente'  idCliente=".$value['id']."  data-toggle='modal' data-target='#modalEditarCliente'><i class='fas fa-user-edit'></i></button>
                 
               <button class='btn btn-danger btnEliminarCliente' idCliente=".$value['id']."><i class='fas fa-trash-alt'></i></button>
                 </div> 
               </td>  
             </tr>";

             endforeach;
                   $paginador = new Paginacion();
                   $paginador = $paginador->paginarClientes($reload, $page, $tpages, $adjacents);  
            echo"<tr>
              <td colspan='10' style='text-align:center;'>".$paginador."</td>
             </tr>";
            }
          }


// DATA_TABLE LISTAR PRODUCTOS
  public  function dtaProductos(){

 $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
 if($action == 'ajax'){
    // escaping, additionally removing everything that could be (html/javascript-) code
    $searchProducto = $_GET['searchProducto'];
    $selectnum = $_GET['selectnum'];
    $aColumns = array('codigo', 'descripcion');//Columnas de busqueda
    $sTable = 'productos';
    $sWhere = "";
   if ( isset($searchProducto))
   {
       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= $aColumns[$i]." LIKE '%".$searchProducto."%' OR ";
       }
       $sWhere = substr_replace( $sWhere, "", -3 );
       $sWhere .= ')';
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
   $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page");
   $registros->execute();

   $registros=$registros->fetchall();     
       
  
  foreach($registros as $key => $value):
  
   echo '<tr>
    <td>'.++$key.'</td>
    <td><img src="'.$value['imagen'].'" alt="" class="img-thumbnail" width="40px"></td>
    <td> '.$value['codigo'].'</td>
    <td>'.$value['descripcion'].'</td>';
   
    $item = 'id';
    $valor = $value['id_categoria'];
    $categoria = ControladorCategorias::ctrMostrarCategorias($item, $valor);
    
   echo '<td>'.$categoria['categoria'].'</td>
    <td> '.$value['stock'].'</td>
    <td> '.$value['precio_compra'].'</td>
    <td> '.$value['precio_venta'].'</td>
    <td> '.$value['fecha'].'</td>
    <td><div class="btn-group">

    <button class="btn btn-warning btnEditarProducto" idProducto="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarProducto"><i class="fas fa-user-edit"></i></button>

    <button class="btn btn-danger btnEliminarProducto" idProducto="'.$value["id"].'" codigo="'.$value["codigo"].'" imagen="'.$value["imagen"].'" ><i class="fas fa-trash-alt"></i></button>

     </div>
    </td>

  </tr> ';

  
  endforeach;
  
                   $paginador = new Paginacion();
                   $paginador = $paginador->paginarProductos($reload, $page, $tpages, $adjacents);  
            echo"<tr>
              <td colspan='10' style='text-align:center;'>".$paginador."</td>
             </tr>";
        }
      }
// DATA_TABLE PRODUCTOS PARA AGREGAR AL CARRITO
      public  function dtaProductosVentas(){

        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        if($action == 'ajax'){
           // escaping, additionally removing everything that could be (html/javascript-) code
           $searchProducto = $_GET['searchProductoV'];
           $selectnum = $_GET['selectnum'];
           $aColumns = array('codigo', 'descripcion');//Columnas de busqueda
           $sTable = 'productos';
           $sWhere = "";
          if ( isset($searchProducto))
          {
              $sWhere = "WHERE (";
              for ( $i=0 ; $i<count($aColumns) ; $i++ )
              {
                  $sWhere .= $aColumns[$i]." LIKE '%".$searchProducto."%' OR ";
              }
              $sWhere = substr_replace( $sWhere, "", -3 );
              $sWhere .= ')';
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
          $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page");
          $registros->execute();
       
          $registros=$registros->fetchall();     
              
         
         foreach($registros as $key => $value):
         
          echo '<tr>
           <td>'.++$key.'</td>
           <td><img src="'.$value['imagen'].'" alt="" class="img-thumbnail" width="40px"></td>
           <td> '.$value['codigo'].'</td>
           <td>'.$value['descripcion'].'</td>';
          
           $item = 'id';
           $valor = $value['id_categoria'];
           $categoria = ControladorCategorias::ctrMostrarCategorias($item, $valor);
           
          echo '<td>'.$categoria['categoria'].'</td>
           <td> '.$value['stock'].'</td>
           <td><input type="number" class="number" name="cantidad" id="cantidad'.$value['id'].'" min="1" value="1" idProducto="'.$value["id"].'"></td>
           <td> '.$value['precio_venta'].'</td>
          
           <td><div class="btn-group">
       
           <button class="btn btn-primary btn-sm agregarProducto" idProducto="'.$value["id"].'"><i class="fa fa-plus"></i></button>
       
           
       
            </div>
           </td>
       
         </tr> ';
       
         
         endforeach;
         
                          $paginador = new Paginacion();
                          $paginador = $paginador->paginarProductosVentas($reload, $page, $tpages, $adjacents);  
                   echo"<tr>
                     <td colspan='10' style='text-align:center;'>".$paginador."</td>
                    </tr>";
                   }
                 }
      
      // DATA_TABLE LISTAR LAS VENTAS FACTURAS, BOLETAS
       public  function dtaVentas(){

        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        if($action == 'ajax'){
           // escaping, additionally removing everything that could be (html/javascript-) code
         
           $searchVentas = $_GET['searchVentas'];
           $selectnum = $_GET['selectnum'];   
           $fechaInicial = $_GET['fechaInicial'];
           $fechaFinal = $_GET['fechaFinal'];
           
           $aColumns = array('serie_correlativo', 'correlativo');//Columnas de busqueda
           $sTable = 'venta';
           $sWhere = "";
          if(isset($searchVentas))
          {
              $sWhere = "WHERE (";
              for ( $i=0 ; $i<count($aColumns) ; $i++ )
              {
                  $sWhere .= $aColumns[$i]." LIKE '%".$searchVentas."%' OR ";
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
          $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $sTable  $sWhere AND resumen='n'");
          $totalRegistros = $totalRegistros->fetch()['numrows'];
          $tpages = ceil($totalRegistros/$per_page);
          $reload = './index.php';
          //main query to fetch the data
          $pdo =  Conexion::conectar();
          $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere AND resumen='n' ORDER BY id DESC LIMIT $offset,$per_page");
          $registros->execute();
       
          $registros=$registros->fetchall();     
              
         
         foreach($registros as $key => $value):
          $item = "id";
          $valor = $value['codcliente'];
          $cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

          $emisor = ControladorClientes::ctrEmisor();

          $item = "seriecorrelativo_ref";
          $valor = $value["serie_correlativo"];
          $notaC = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);

          $item = 'idenvio';
          $valor = $value['idbaja'];
          $bajasComprobantes = ControladorEnvioSunat::ctrMostrarBajas($item, $valor);
      
           
          if($value['tipocomp'] == '01'){

            if($value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

            $nombreRazon = $cliente['ruc']."<br>".$cliente['razon_social'];
            $serieCorrelativo = "NOTA DE CRÉDITO-".$notaC['serie'].'-'.$notaC['correlativo']."
            <br>
            <i class='fas fa-bullseye' style='color:green'></i>
            <span style='font-size:10px; margin-left:3px;'> FACTURA AFECTADA: ".$notaC['serie_ref'].'-'.$notaC['correlativo_ref']."</span>";
           
          }else{
            $nombreRazon = $cliente['ruc']."<br>".$cliente['razon_social'];
            $serieCorrelativo = "FACTURA-".$value['serie_correlativo'];
            }

          

          }else{
            if($value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

             $nombreRazon = $cliente['documento']."<br>".$cliente['nombre'];
             $serieCorrelativo = "NOTA DE CRÉDITO-".$notaC['serie'].'-'.$notaC['correlativo']."
             <br>
             <i class='fas fa-bullseye' style='color:red'></i>
             <span style='font-size:10px; margin-left:3px;'> BOLETA AFECTADA: ".$notaC['serie_ref'].'-'.$notaC['correlativo_ref']."</span>";
            
            }else{

              $nombreRazon = $cliente['documento']."<br>".$cliente['nombre'];
             $serieCorrelativo = "BOLETA DE VENTA--".$value['serie_correlativo'];
            }
          }
          if($value['codmoneda'] == 'PEN'){
            $textMoneda = "S/ ";
          }else{
             $textMoneda = '$USD ';
          }
         $fecha = date_create($value['fechahora']);

         if($value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

          echo '<tr>
           <td>'.++$key.'</td>
           <td>'.date_format($fecha,'d-m-Y / H:i:s').'</td>
           <td>'.$serieCorrelativo.'</td>
           <td> '.$nombreRazon.'</td>
           <td> '.$textMoneda.$notaC['total'].'</td>
           <td>
           <div class="contenedor-print-comprobantes">
           <form id="printC" name="printC" method="post" action="vistas/print/print-nc.php" target="_blank">
          <input type="radio" class="a4'.$notaC['id'].'" id="a4" name="a4" value="A4">
          <input type="radio" class="tk'.$notaC['id'].'" id="tk" name="a4" value="TK">
          <input type="hidden" id="idCo" name="idCo" value="'.$notaC['id'].'">
           <button class="printA4"  id="printA4" idComp="'.$notaC['id'].'" ></button>
           <button class="printT" id="printT" idComp="'.$notaC['id'].'" ></button>
           </form></div>
           </td>
           <td> ';  
         }else{
          echo '<tr>
          <td>'.++$key.'</td>
          <td>'.date_format($fecha,'d-m-Y / H:i:s').'</td>
          <td>'.$serieCorrelativo.'</td>
          <td> '.$nombreRazon.'</td>
          <td> '.$textMoneda.$value['total'].'</td>
          <td>
          <div class="contenedor-print-comprobantes">
          <form id="printC" name="printC" method="post" action="vistas/print/print.php" target="_blank">
         <input type="radio" class="a4'.$value['id'].'" id="a4" name="a4" value="A4">
         <input type="radio" class="tk'.$value['id'].'" id="tk" name="a4" value="TK">
         <input type="hidden" id="idCo" name="idCo" value="'.$value['id'].'">
          <button class="printA4"  id="printA4" idComp="'.$value['id'].'" ></button>
          <button class="printT" id="printT" idComp="'.$value['id'].'" ></button>
          </form></div>
          </td>
          <td> ';  
        }
          if($value['anulado'] =='n'){

            if($value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

            $nombre = $emisor['ruc'].'-'.$notaC['tipocomp'].'-'.$notaC['serie'].'-'.$notaC['correlativo'];
              $nombre = $nombre.'.XML';

            $btnXml =  '<a href="./api/xml/'.$nombre.'" target="_blank" class="xml"  id="xml" idComp="'.$value['id'].'" ></a>';

            }else{
              $btnXml =  '<a href="./api/xml/'.$value['nombrexml'].'" target="_blank" class="xml"  id="xml" idComp="'.$value['id'].'" ></a>';
            }

          }
          if($value['anulado'] =='s'){
            foreach($bajasComprobantes as $k => $v){
              $btnXml = '<a href="./api/xml/'.$v['nombrexml'].'" target="_blank" class="xml"  id="xml" idComp="'.$value['id'].'" ></a>';
            } 
          }
             echo '<div class="contenedor-print-comprobantes">
                '.$btnXml.'
                </div>       
           </td>';
         
          

           if($value['feestado'] == '1' && $value['anulado'] == 'n'){

            if($value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

              $nombre = $emisor['ruc'].'-'.$notaC['tipocomp'].'-'.$notaC['serie'].'-'.$notaC['correlativo'];
                $nombre = $nombre.'.XML';
            $botonEstadoCdr = '<a href="./api/cdr/R-'.$nombre.'" target="_blank" class="cdr"  id="cdr" idComp="'.$notaC['id'].'" ></a>';
           
          }else{
            
            $botonEstadoCdr = '<a href="./api/cdr/R-'.$value['nombrexml'].'" target="_blank" class="cdr"  id="cdr" idComp="'.$value['id'].'" ></a>';
            }

          }else{
            foreach($bajasComprobantes as $k => $v){
            $botonEstadoCdr = '<a href="./api/cdr/R-'.$v['nombrexml'].'" target="_blank" class="cdr"  id="cdr" idComp="'.$value['id'].'" ></a>';
          }
        }
           if($value['feestado'] == '2'){
            $botonEstadoCdr = "<button class='s-rechazo'></button>";
          }
           if($value['feestado'] == ''){
            $botonEstadoCdr = "<button class='s-getcdr' id='getcdr1'  idVenta='".$value['id']."'></button>";
          }
          echo '<td>     
          <div class="contenedor-print-comprobantes" estadocdr'.$value['id'].'>
           '.$botonEstadoCdr.'
          </div>
       
           </td>';
          //  ESTADO SUNAT ===================
            if($value['feestado'] == '1' && $value['anulado'] == 'n'){
              $botonEstado = "<button class='s-success'></button>";
            }else{
              $botonEstado = '<button class="anulado"></button>';
            }
            if($value['feestado'] == '2'){
              $botonEstado = "<button class='s-rechazo'></button>";
            }
            if($value['feestado'] == '3'){
              $botonEstado = "<button class='s-getcdr' id='getcdr2' idVenta='".$value['id']."'></button>";
            }
            if($value['feestado'] == ''){
              $botonEstado = "<button class='s-getcdr' id='getcdr3' idVenta='".$value['id']."'></button>";
            }
        echo '<td>     
          <div class="contenedor-print-comprobantes estadosunat'.$value['id'].'">
              '.$botonEstado.'
           </div>
       
           </td>';
           if($value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

           $btnMenuFactura =  '<nav class="navbar navbar-static-top">
           <div class="navbar-custom-menu">
           <ul class="nav navbar-nav">
           <li class="dropdown tasks-menu option-menu">
           <a href="#" class="dropdown-toggle option-menu" data-toggle="dropdown">
           </a>
           <ul class="dropdown-menu"  style="width: 220px; color:black;">
          
             <li class="">
             <form class="form" action="crear-factura" method="post">
             <input type="hidden" class="numCorrelativo" name="numCorrelativo" value="'.$value['serie_correlativo'].'">
                        
               <button><i class="fas fa-plus"> </i> <span>Volver a crear</span></button>
             
               </form>
            </li>
            </ul>
           </li>
           </ul>
           </div>
           </nav>';
           }else{
             
             $btnMenuFactura =  '<nav class="navbar navbar-static-top">
           <div class="navbar-custom-menu">
           <ul class="nav navbar-nav">
           <li class="dropdown tasks-menu option-menu">
           <a href="#" class="dropdown-toggle option-menu" data-toggle="dropdown">
           </a>
           <ul class="dropdown-menu"  style="width: 220px; color:black;">
         
            <li class="">
            <button id="bajaDoc" idDoc="'.$value['id'].'">
            <i class="fas fa-times"> </i> <span>Anular comprobante</span>
                      
            </button>
            </li> 
            
            <li class="">
            <form class="form" action="nota-credito" method="post">
            <input type="hidden" class="resultadoSerie" name="resultadoSerie" value="'.$value['serie_correlativo'].'">
            <input type="hidden" id="tipoComprobante" name="tipoComprobante" value="'.$value['tipocomp'].'">
            
              <button><i class="fas fa-plus"> </i> <span>Crear nota de crédito</span></button>
            
              </form>
            </li> 

            <li class="">
            <form class="form" action="nota-debito" method="post">
            <input type="hidden" class="resultadoSerie" name="resultadoSerie" value="'.$value['serie_correlativo'].'">
            <input type="hidden" id="tipoComprobante" name="tipoComprobante" value="'.$value['tipocomp'].'">
            
              <button><i class="fas fa-plus"> </i> <span>Crear nota de débito</span></button>
            
              </form>
            </li>
             <li class="">
             <form class="form" action="crear-factura" method="post">
             <input type="hidden" class="numCorrelativo" name="numCorrelativo" value="'.$value['serie_correlativo'].'">
                        
               <button><i class="fas fa-plus"> </i> <span>Volver a crear</span></button>
             
               </form>
            </li>
            </ul>
           </li>
           </ul>
           </div>
           </nav>';
           }
           if($value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

           $btnMenuBoleta =  '<nav class="navbar navbar-static-top">
           <div class="navbar-custom-menu">
           <ul class="nav navbar-nav">
           <li class="dropdown tasks-menu option-menu">
           <a href="#" class="dropdown-toggle option-menu" data-toggle="dropdown">
           </a>
           <ul class="dropdown-menu"  style="width: 220px; color:black;">
         
             <li class="">
             <form class="form" action="crear-boleta" method="post">
             <input type="hidden" class="numCorrelativo" name="numCorrelativo" value="'.$value['serie_correlativo'].'">
                        
               <button><i class="fas fa-plus"> </i> <span>Volver a crear</span></button>
             
               </form>
            </li>
            </ul>
           </li>
           </ul>
           </div>
           </nav>';

           }else{

           $btnMenuBoleta =  '<nav class="navbar navbar-static-top">
           <div class="navbar-custom-menu">
           <ul class="nav navbar-nav">
           <li class="dropdown tasks-menu option-menu">
           <a href="#" class="dropdown-toggle option-menu" data-toggle="dropdown">
           </a>
           <ul class="dropdown-menu"  style="width: 220px; color:black;">
         
             
            <li class="">
            <form class="form" action="nota-credito" method="post">
            <input type="hidden" class="resultadoSerie" name="resultadoSerie" value="'.$value['serie_correlativo'].'">
            <input type="hidden" id="tipoComprobante" name="tipoComprobante" value="'.$value['tipocomp'].'">
            
              <button><i class="fas fa-plus"> </i> <span>Crear nota de crédito</span></button>
            
              </form>
            </li> 

            <li class="">
            <form class="form" action="nota-debito" method="post">
            <input type="hidden" class="resultadoSerie" name="resultadoSerie" value="'.$value['serie_correlativo'].'">
            <input type="hidden" id="tipoComprobante" name="tipoComprobante" value="'.$value['tipocomp'].'">
            
              <button><i class="fas fa-plus"> </i> <span>Crear nota de débito</span></button>
            
              </form>
            </li>
             <li class="">
             <form class="form" action="crear-boleta" method="post">
             <input type="hidden" class="numCorrelativo" name="numCorrelativo" value="'.$value['serie_correlativo'].'">
                        
               <button><i class="fas fa-plus"> </i> <span>Volver a crear</span></button>
             
               </form>
            </li>
            </ul>
           </li>
           </ul>
           </div>
           </nav>';
           }

              if($value['anulado'] == 's'){
                $btnAnulado = '<button class="anulado"></button>';
              }
              if($value['anulado'] == 'n' && $value['feestado'] == '1' && $value['tipocomp'] == '01'){
                $btnAnulado = $btnMenuFactura;
                // '<button class="option-menu" id="bajaDoc" idDoc="'.$value['id'].'"></button>';
              }
              if($value['anulado'] == 'n' && $value['feestado'] == '1' && $value['tipocomp'] == '03'){
                $btnAnulado = $btnMenuBoleta;   
                          
// '<button class="option-menu"></button>'

              }
              if($value['anulado'] == 'n' && $value['feestado'] == ''){
                $btnAnulado = '<button class="option-menu"></button>';
              }
              echo '<td>     
                <div class="contenedor-print-comprobantes">
                    '.$btnAnulado.'
                </div>
       
             </td>
       
         </tr> ';
       
         
         endforeach;
         
                          $paginador = new Paginacion();
                          $paginador = $paginador->paginarVentas($reload, $page, $tpages, $adjacents);  
                   echo"<tr>
                     <td colspan='10' style='text-align:center;'>".$paginador."</td>
                    </tr>";
                   }
                 }




    // DATA_TABLE RESUMEN DIARIO BOLETAS
  public  function dtaResumenDiario(){

    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if($action == 'ajax'){
       // escaping, additionally removing everything that could be (html/javascript-) code
       $search = $_REQUEST['searchResumen'];
       $selectnum = $_REQUEST['selectnum'];
       $aColumns = array('correlativo');//Columnas de busqueda
       $sTable = 'envio_resumen';
       $sWhere = "";
      if (isset($search))
      {
          $sWhere = "WHERE (";
          for ( $i=0 ; $i<count($aColumns) ; $i++ )
          {
              $sWhere .= $aColumns[$i]." LIKE '%".$search."%' OR ";
          }
          $sWhere = substr_replace( $sWhere, "", -3 );
          $sWhere .= ')';
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
      $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $sTable  $sWhere AND resumen=1");
      $totalRegistros = $totalRegistros->fetch()['numrows'];
      $tpages = ceil($totalRegistros/$per_page);
      $reload = './index.php';
      //main query to fetch the data
      $pdo =  Conexion::conectar();
      $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere AND resumen=1 ORDER BY idenvio DESC LIMIT $offset,$per_page");
      $registros->execute();
   
      $registros=$registros->fetchall(); 

      foreach ($registros as $k => $value):
        echo "<tr>
           <td>".++$k."</td>
           <td>".date_format(date_create($value['fecha_envio']), 'd-m-Y')."</td>

           <td>".date_format(date_create($value['fecha_emision']), 'd-m-Y')."</td>

           <td>
           <div class='btn-ver_boletas'>
           <button id='btnVerBoletas' class='btn btn-primary' idenvio=".$value['idenvio']." data-toggle='modal' data-target='#modalBoletas'><i class='far fa-eye' ></i> VER BOLETAS</button></
           </div>
           </td>

           <td>".$value['ticket']."</td>";
          
          echo '<td>
           <div class="contenedor-print-comprobantes">
           <form id="printC" name="printC" method="post" action="vistas/print/print-nc.php" target="_blank">
          <input type="radio" class="a4'.$value['idenvio'].'" id="a4" name="a4" value="A4">
          <button class="printA4"  id="printA4" idComp="'.$value['idenvio'].'" ></button>
       
           </form>
           </div>
           
           </td>
         <td>
         <div class="contenedor-print-comprobantes">
         <a href="./api/xml/'.$value['nombrexml'].'" target="_blank" class="xml"  id="xml" idComp="'.$value['idenvio'].'" ></a>
       </div>
         
         </td>
         <td>
         <div class="contenedor-print-comprobantes">
         <a href="./api/cdr/R-'.$value['nombrexml'].'" target="_blank" class="cdr"  id="cdr" idComp="'.$value['idenvio'].'" ></a>
       </div>';

         //  ESTADO SUNAT ===================
      if($value['feestado'] == '1'){
          $botonEstado = "<button class='s-success'></button>";
        }else{
          $botonEstado = '<button class="anulado"></button>';
        }
        if($value['feestado'] == '2'){
          $botonEstado = "<button class='s-rechazo'></button>";
        }
        if($value['feestado'] == '3'){
          $botonEstado = "<button class='s-getcdr' id='getcdr2' idVenta='".$value['id']."'></button>";
        }
        echo '<td>
        <div class="contenedor-print-comprobantes">
          '.$botonEstado.'
        </div>
        
        </td>
         </td>
           </tr>';

      endforeach;

           $paginador = new Paginacion();
           $paginador = $paginador->paginarResumenesDiarios($reload, $page, $tpages, $adjacents);  
       echo"<tr>
        <td colspan='8' style='text-align:center;'>".$paginador."</td>
         </tr>";
      
    } 
  }

   // DATA_TABLE RESUMEN DIARIO BOLETAS
   public  function dtaResumenBoletas(){

    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if($action == 'ajax'){
       // escaping, additionally removing everything that could be (html/javascript-) code
    
       
       $idenvio = $_REQUEST['idenvio'];
      //  $respuesta = ModeloResumenDiario::mdlMostrarDetallesResumenenes($tabla, $idenvio);

     $search = $_REQUEST['searchBoleta'];
       $selectnum = $_REQUEST['selectnum'];
       $aColumns = array('correlativo');//Columnas de busqueda
       $sTable = 'envio_resumen_detalle';
       $sWhere = "";
      // if (isset($search) && $search != '')
      // {
      //     // $sWhere = "WHERE idventa = $search  AND idenvio = $idenvio";
          
      // }else{
        $sWhere = "WHERE idenvio = $idenvio";
      // }
      
  
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
      $registros = $pdo->prepare("SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page");
      $registros->execute();
   
      $registros=$registros->fetchall(); 

      foreach($registros as $k => $v){
        $tabla = 'venta';
        $item = "id";
        $valor = $v['idventa'];
        $boletas = ModeloResumenDiario::mdlMostrarVentasResumenes($tabla, $item, $valor,$search);
        foreach ($boletas as $k => $value):
        echo '<tr>
        <td>'.$value['fecha_emision'].'</td>
        <td>'.$value['tipocomp'].'</td>
        <td>'.$value['serie'].'</td>
        <td>'.$value['correlativo'].'</td>
        <td>'.$value['total'].'</td>
        </tr>';
      endforeach;
      }
    }
    $paginador = new Paginacion();
    $paginador = $paginador->paginarResumenBoletas($reload, $page, $tpages, $adjacents);  
echo"<tr>
 <td colspan='8' style='text-align:center;'>".$paginador."</td>
  </tr>";


    }
  }
    


       if(isset($_REQUEST['dc'])){
         if($_REQUEST['dc'] == "dc"){
        $dataClientes = new DataTables();
        $dataClientes->dtaClientes();
       }
      }
      if(isset($_REQUEST['dp'])){
        if($_REQUEST['dp'] == "dp"){
        $dataProductos = new DataTables();
        $dataProductos->dtaProductos();
        }
      }
      if(isset($_REQUEST['dpv'])){
        if($_REQUEST['dpv'] == "dpv"){
        $dataProductos = new DataTables();
        $dataProductos->dtaProductosVentas();
        }
      }
      if(isset($_REQUEST['dv'])){
        if($_REQUEST['dv'] == "dv"){
        $dataPVentas = new DataTables();
        $dataPVentas->dtaVentas();
        }
      }
      if(isset($_REQUEST['rd'])){
        if($_REQUEST['rd'] == "rd"){
        $dataPVentas = new DataTables();
        $dataPVentas->dtaResumenDiario();
        }
      }
      if(isset($_REQUEST['loadBoletas'])){
       
        $dataPVentas = new DataTables();
        $dataPVentas->dtaResumenBoletas();
        
      }
?>
<script>
//  $("#modalBoletas").dialog({
//   modal: true,
//   buttons: {
//     Ok: function() {
//       $( this ).dialog( "close" );
//     }
//   }
// })
</script>