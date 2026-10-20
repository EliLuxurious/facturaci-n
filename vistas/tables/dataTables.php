<?php
session_start();

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
use Controladores\ControladorProdutos;


class DataTables{

  // DATA_TABLE CLIENTES LISTAR CLIENTES
  public  function dtaClientes(){

 $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
 if($action == 'ajax'){
    // escaping, additionally removing everything that could be (html/javascript-) code
    $perfilUsuario = $_REQUEST['perfilOcultoc'];
    $search = $_GET['search'];
    $selectnum = $_GET['selectnum'];
    $aColumns = array('nombre', 'documento', 'ruc');//Columnas de busqueda
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
              if($value['ruc'] != ''){
                $nombreRazon = $value['razon_social'];
                $rucdni = $value['ruc'];
              }else{
                $nombreRazon = $value['nombre'];
                $rucdni = $value['documento'];
              }
           echo  "<tr class='id".$value['id']."'>
               <td> ".(++$key)."</td>
               <td>". $nombreRazon."</td>
               <td>".$rucdni."</td>
               <td> ".$value['email']."</td>
               <td> ".$value['telefono']."</td>
               <td> ".$value['direccion']."</td>
              <!--  <td> ".$value['compras']."</td>
               <td> ".$value['ultima_compra']."</td> -->
               <td> ".date_format(date_create($value['fecha']),'d/m/Y H:i:s')."</td>
                         <td>
                 <div class='btn-group'>

               <button class='btn btn-warning btnEditarCliente'  idCliente=".$value['id']."  data-toggle='modal' data-target='#modalEditarCliente'><i class='fas fa-user-edit'></i></button>";
              
               if($perfilUsuario == 'Administrador'){
             
              echo "<button class='btn btn-danger btnEliminarCliente' idCliente=".$value['id']."><i class='fas fa-trash-alt'></i></button>";
               }
             echo "</div> 
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
    $perfilUsuario = $_REQUEST['perfilOculto'];
    $searchProducto = $_GET['searchProducto'];
    $selectnum = $_GET['selectnum'];
    $aColumns = array('codigo', 'serie', 'descripcion');//Columnas de busqueda
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
    <td> '.$value['serie'].'</td>
    <td>'.$value['descripcion'].'</td>';
   
    $item = 'id';
    $valor = $value['id_categoria'];
    $categoria = ControladorCategorias::ctrMostrarCategorias($item, $valor);
    
   echo '<td>'.$categoria['categoria'].'</td>
    <td> <button class="btn btn-primary btn-stock" idProducto="'.$value["id"].'">'.$value['stock'].'</button></td>
    <td> '.$value['precio_unitario'].'</td>
    <td> '.date_format(date_create($value['fecha']),'d/m/Y H:i:s').'</td>
    <td><div class="btn-group">

    <button class="btn btn-warning btnEditarProducto" idProducto="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarProducto"><i class="fas fa-user-edit"></i></button>';

    if($perfilUsuario == 'Administrador'){

    echo '<button class="btn btn-danger btnEliminarProducto" idProducto="'.$value["id"].'" codigo="'.$value["codigo"].'" imagen="'.$value["imagen"].'" ><i class="fas fa-trash-alt"></i></button>';
    }

    echo '</div>
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
// DATA_TABLE LISTA LOS PRODUCTOS PARA AGREGAR AL CARRITO
      public  function dtaProductosVentas(){

        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        if($action == 'ajax'){
           // escaping, additionally removing everything that could be (html/javascript-) code
           $searchProducto = $_GET['searchProductoV'];
           $selectnum = $_GET['selectnum'];
           $categorias = $_GET['categorias'];
           $aColumns = array('codigo', 'serie', 'descripcion');//Columnas de busqueda
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
          if ( isset($categorias) AND $categorias != NULL )
          {
              $sWhere = "WHERE (";
              for ( $i=0 ; $i<count($aColumns) ; $i++ )
              {
                  $sWhere .= "id_categoria = '$categorias' AND ".$aColumns[$i]." LIKE '%".$searchProducto."%' OR ";
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
          
           $item = 'id';
           $valor = $value['id_categoria'];
           $categoria = ControladorCategorias::ctrMostrarCategorias($item, $valor);
            // <td><img src="'.$value['imagen'].'" alt="" class="img-thumbnail" width="40px"></td>
          echo '<tr class="contenedor-items">
           <td>'.++$key.'</td>
          
           <td> '.$value['codigo'].'</td>
           <td> '.$value['serie'].'</td>
           <td>'.$value['descripcion'].'</td>';
         
          echo '<td>'.$categoria['categoria'].'</td>
           <td> <button class="btn-primary stock'.$value['id'].' btn-stock"  stock="'.$value["stock"].'">'.$value['stock'].'</button></td>
         
           <td>
       
           <input type="number" class="number cantidad-stock" name="cantidad" id="cantidad'.$value['id'].'"  idProducto="'.$value["id"].'" min="1" value="1">
           </td>

           <td> '.$value['precio_unitario'].'</td>           

           <td class="btn-prod">
           <div class="btn-group"  style="; !important; justify-content: center;">       
           <button class="btn btn-primary btn-sm agregarProducto" descripcionP="'.$value["descripcion"].'" idProducto="'.$value["id"].'"><i class="fa fa-plus"></i></button>  
          </div> 
            


           </td>
           <td class="btn-prod">
           <div class="btn-group">   
           <button class="btn btn-primary btn-sm vermasProductos btn-close" idProducto="'.$value["id"].'"><i class="fas fa-tools"></i></button>     
                 
            </div>
    
            </td>            
          
       
         </tr> ';
       
        echo '<tr >
         <td class="" colspan="10">
         
         <div class="super-contenedor-precios super-cont-precios'.$value["id"].'">

         <div class="desc-productos" style=""><h3>'.$value['descripcion'].'</h3></div>
         <div class="contenedor-precios">
               
        <label>Tipo IGV</label>';
         $item = 'codigo';
          $valor = $value['codigoafectacion'];
         $afectacionT = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);

        echo'<select class="tipo_afectacion'.$value["id"].' pre-css" id="tipoAfectacion" idProducto="'.$value["id"].'" tpa="'.$value['codigoafectacion'].'">
        <option value="'.$afectacionT['codigo'].'">'.$afectacionT['descripcion'].'</option>';
         $item = null;
          $valor = null;
         $tipoAfectacion = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
         
         foreach($tipoAfectacion as $k => $afectacion):
        echo'
   
        <option value="'.$afectacion['codigo'].'">'.$afectacion['descripcion'].'</option>
       ';
         endforeach;

        echo' </select>    
        

        <label class="">Precio Unitario</label> 
        <input type="text" class="precio_unitario'.$value['id'].' pre-css" id="precio_unitario" name="" value="'.$value['precio_unitario'].'" idProducto="'.$value["id"].'">
        <label>Valor Unitario</label>
         <input type="text" class="valor_unitario'.$value['id'].' pre-css" name="" value="'.$value['valor_unitario'].'" readonly>
         
         <label>Impuesto a la bolsa plástica</label>
         <div class="contenedor_icbper">

         <div class="modo-contenedor-icbper">
         <label for="siic" id="s'.$value["id"].'" class="s alterno btn-icb-si" idProducto="'.$value["id"].'" val="s" >||</label>              
         <label for="siic" id="n'.$value["id"].'" class="n icbno btn-icb-no" idProducto="'.$value["id"].'" val="n" >
         No</label>
        
         </div>

          <input type="hidden" name="modo" id="modo_icbper" class="modo-icbper'.$value['id'].'" value="n">
          <input type="text" class="icbper'.$value['id'].' pre-css" name="" value="" readonly>
        </div>

        </div>
         <div class="contenedor-precios">
        
        <label>Descuento</label>
         <input type="text" class="descuento_item'.$value['id'].' pre-css" id="descuento_item" name="precio_v" value="0.00" idProducto="'.$value["id"].'">
        <label>Sub total</label>
         <input type="text" class="subtotal'.$value['id'].' pre-css" name="precio_v" value="'.$value['valor_unitario'].'" readonly>

        <label>IGV de la linea</label>
         <input type="text" class="igv'.$value['id'].' pre-css" name="precio_v" value="'.$value['igv'].'" readonly>
         
        <label>Total</label>
         <input type="text" class="total'.$value['id'].' pre-css" name="precio_v" value="'.$value['precio_unitario'].'" readonly>

         <div class="btn-grupos" >   
       <button class="btn btn-primary btn-sm closeModalProductos btn-close" descripcionP="'.$value["descripcion"].'" idProducto="'.$value["id"].'"><i class="fa fa-times-circle"></i></button>  

       <button class="btn btn-primary btn-sm agregarProducto" descripcionP="'.$value["descripcion"].'" idProducto="'.$value["id"].'"><i class="fa fa-plus"></i></button>  
         </div>
         </div>
         </div>
         
         
         </td>
         
         </tr>';
         endforeach;
         
         
                          $paginador = new Paginacion();
                          $paginador = $paginador->paginarProductosVentas($reload, $page, $tpages, $adjacents);  
                   echo"<tr>
                     <td colspan='10' style='text-align:center;'>".$paginador."</td>
                    </tr>";
                   }
                 }
      
// DATA_TABLE LISTA LOS PRODUCTOS PARA AGREGAR AL CARRITO
      public  function dtaProductosGuia(){

        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        if($action == 'ajax'){
           // escaping, additionally removing everything that could be (html/javascript-) code
           $searchProducto = $_GET['searchProductoG'];
           $selectnum = $_GET['selectnum'];
           $aColumns = array('codigo', 'serie', 'descripcion');//Columnas de busqueda
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
          
           $item = 'id';
           $valor = $value['id_categoria'];
           $categoria = ControladorCategorias::ctrMostrarCategorias($item, $valor);
            // <td><img src="'.$value['imagen'].'" alt="" class="img-thumbnail" width="40px"></td>
          echo '<tr class="contenedor-items">
           <td>'.++$key.'</td>
          
           <td> '.$value['codigo'].'</td>
           <td> '.$value['serie'].'</td>
           <td>'.$value['descripcion'].'</td>';
         
          echo '<td>'.$value['codunidad'].'</td>
                
           <td>
           <input type="number" class="number cantidad-stock" name="cantidad" id="cantidad'.$value['id'].'"  idProducto="'.$value["id"].'" min="1" value="1">
           </td>

           <td class="btn-prod">
           <div class="btn-group"  style="; !important; justify-content: center;">       
           <button class="btn btn-primary btn-sm agregarProductoGuia" descripcionP="'.$value["descripcion"].'" idProducto="'.$value["id"].'"><i class="fa fa-plus"></i></button>  
          </div> 
            


           </td>
          
         
         </tr>';
         endforeach;
         
         
                          $paginador = new Paginacion();
                          $paginador = $paginador->paginarProductosGuia($reload, $page, $tpages, $adjacents);  
                   echo"<tr>
                     <td colspan='10' style='text-align:center;'>".$paginador."</td>
                    </tr>";
                   }
                 }
      
      // DATA_TABLE LISTAR LAS VENTAS FACTURAS, BOLETAS NOTAS CD
       public  function dtaVentas(){

        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        if($action == 'ajax'){
           // escaping, additionally removing everything that could be (html/javascript-) code
         
            //$empresa = ControladorEmpresa::ctrModoProduccion();
           	// RUTAS DE CDR Y XML 
              $ruta_xml = "xml";
              $ruta_cdr = "cdr";
            
                       
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
                  $sWhere .= " tipocomp !='02' AND ".$aColumns[$i]." LIKE '%".$searchVentas."%' OR ";
              }
              $sWhere = substr_replace( $sWhere, "", -3 );
              $sWhere .= ')';
          }
          if($fechaInicial != null && $fechaFinal != null){
          if($fechaInicial == $fechaFinal){
            $sWhere = "WHERE tipocomp !='02' AND fecha_emision LIKE '%$fechaFinal%'";

          }
          if($fechaInicial != $fechaFinal){
            $sWhere = "WHERE tipocomp !='02' AND  fecha_emision BETWEEN '$fechaInicial' AND '$fechaFinal'";
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

          $emisor = ControladorEmpresa::ctrEmisor();

          $item = "id";
          $valor = $value["id_nc"];
          $notaC = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);
          
          $item = "id";
          $valor = $value["id_nd"];
          $notaD = ControladorNotaDebito::ctrMostrarNotaDebito($item, $valor);

          $item = 'idenvio';
          $valor = $value['idbaja'];
          $bajasComprobantes = ControladorEnvioSunat::ctrMostrarBajas($item, $valor);

           
           
           
          if($value['tipocomp'] == '01'){            
       

            if(isset($notaC['feestado']) && $value['serie_correlativo'] == $notaC['seriecorrelativo_ref']   && $notaC['feestado'] == 1){

            $nombreRazon = $cliente['ruc']."<br>".$cliente['razon_social'];
            $serieCorrelativo = "NOTA DE CRÉDITO-".$notaC['serie'].'-'.$notaC['correlativo']."
            <br>
            <i class='fas fa-bullseye' style='color:green'></i>
            <span style='font-size:10px; margin-left:3px;'> FACTURA AFECTADA: ".$notaC['serie_ref'].'-'.$notaC['correlativo_ref']."</span>";
           
          }
        
           else if(isset($notaD['feestado']) && $value['serie_correlativo'] == $notaD['seriecorrelativo_ref'] && $notaD['feestado'] == 1 ){

            $nombreRazon = $cliente['ruc']."<br>".$cliente['razon_social'];
            $serieCorrelativo = "NOTA DE DÉBITO-".$notaD['serie'].'-'.$notaD['correlativo']."
            <br>
            <i class='fas fa-bullseye' style='color:green'></i>
            <span style='font-size:10px; margin-left:3px;'> FACTURA AFECTADA: ".$notaD['serie_ref'].'-'.$notaD['correlativo_ref']."</span>";
           
          }else{
            $nombreRazon = $cliente['ruc']."<br>".$cliente['razon_social'];
            $serieCorrelativo = "FACTURA-".$value['serie_correlativo'];
            }

          

          }if($value['tipocomp'] == '03'){
            if(isset($notaC['feestado']) && $value['serie_correlativo'] == $notaC['seriecorrelativo_ref'] && $notaC['feestado'] == 1){

             $nombreRazon = $cliente['documento']."<br>".$cliente['nombre'];
             $serieCorrelativo = "NOTA DE CRÉDITO-".$notaC['serie'].'-'.$notaC['correlativo']."
             <br>
             <i class='fas fa-bullseye' style='color:red'></i>
             <span style='font-size:10px; margin-left:3px;'> BOLETA AFECTADA: ".$notaC['serie_ref'].'-'.$notaC['correlativo_ref']."</span>";
            
            }
           else if(isset($notaD['feestado']) && $value['serie_correlativo'] == $notaD['seriecorrelativo_ref'] && $notaD['feestado'] == 1){

             $nombreRazon = $cliente['documento']."<br>".$cliente['nombre'];
             $serieCorrelativo = "NOTA DE DÉBITO-".$notaD['serie'].'-'.$notaD['correlativo']."
             <br>
             <i class='fas fa-bullseye' style='color:red'></i>
             <span style='font-size:10px; margin-left:3px;'> BOLETA AFECTADA: ".$notaD['serie_ref'].'-'.$notaD['correlativo_ref']."</span>";
            
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

         if(isset($notaC['seriecorrelativo_ref']) && $value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

          echo '<tr>
           <td>'.++$key.'</td>
           <td>'.date_format($fecha,'d-m-Y / H:i:s').'</td>
           <td>'.$serieCorrelativo.'</td>
           <td> '.$nombreRazon.'</td>
           <td> '.$textMoneda.number_format($notaC['total'],2).'</td>
           <td>
           <div class="contenedor-print-comprobantes">
           <form id="printC" name="printC" method="post" action="vistas/print/nc/" target="_blank">
          <input type="radio" class="a4'.$notaC['id'].'" id="a4" name="a4" value="A4">
          <input type="radio" class="tk'.$notaC['id'].'" id="tk" name="a4" value="TK">
          <input type="hidden" id="idCo" name="idCo" value="'.$notaC['id'].'">
           <button class="printA4"  id="printA4" idComp="'.$notaC['id'].'" ></button>
           <button class="printT" id="printT" idComp="'.$notaC['id'].'" ></button>
           </form></div>
           </td>
           <td> ';  
         }
        else if(isset($notaD['seriecorrelativo_ref']) && $value['serie_correlativo'] == $notaD['seriecorrelativo_ref']){

          echo '<tr>
           <td>'.++$key.'</td>
           <td>'.date_format($fecha,'d-m-Y / H:i:s').'</td>
           <td>'.$serieCorrelativo.'</td>
           <td> '.$nombreRazon.'</td>
           <td> '.$textMoneda.number_format($notaD['total'],2).'</td>
           <td>
           <div class="contenedor-print-comprobantes">
           <form id="printC" name="printC" method="post" action="vistas/print/nd/" target="_blank">
          <input type="radio" class="a4'.$notaD['id'].'" id="a4" name="a4" value="A4">
          <input type="radio" class="tk'.$notaD['id'].'" id="tk" name="a4" value="TK">
          <input type="hidden" id="idCo" name="idCo" value="'.$notaD['id'].'">
           <button class="printA4"  id="printA4" idComp="'.$notaD['id'].'" ></button>
           <button class="printT" id="printT" idComp="'.$notaD['id'].'" ></button>
           </form></div>
           </td>
           <td> ';  
         }
         else{
          echo '<tr>
          <td>'.++$key.'</td>
          <td>'.date_format($fecha,'d-m-Y / H:i:s').'</td>
          <td>'.$serieCorrelativo.'</td>
          <td> '.$nombreRazon.'</td>
          <td> '.$textMoneda.number_format($value['total'],2).'</td>
          <td>
          <div class="contenedor-print-comprobantes">
          <form id="printC" name="printC" method="post" action="vistas/print/printer/" target="_blank">
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

            if(isset($notaC['seriecorrelativo_ref']) && $value['serie_correlativo'] == $notaC['seriecorrelativo_ref']){

            $nombre = $emisor['ruc'].'-'.$notaC['tipocomp'].'-'.$notaC['serie'].'-'.$notaC['correlativo'];
              $nombre = $nombre.'.XML';

            $btnXml =  '<a href="./api/'.$ruta_xml.'/'.$nombre.'" target="_blank" class="xml"  id="xml" idComp="'.$value['id'].'" download></a>';

            }
           else if(isset($notaD['seriecorrelativo_ref']) && $value['serie_correlativo'] == $notaD['seriecorrelativo_ref']){

            $nombre = $emisor['ruc'].'-'.$notaD['tipocomp'].'-'.$notaD['serie'].'-'.$notaD['correlativo'];
              $nombre = $nombre.'.XML';

            $btnXml =  '<a href="./api/'.$ruta_xml.'/'.$nombre.'" target="_blank" class="xml"  id="xml" idComp="'.$value['id'].'" download></a>';

            }
            else{
              $btnXml =  '<a href="./api/'.$ruta_xml.'/'.$value['nombrexml'].'" target="_blank" class="xml"  id="xml" idComp="'.$value['id'].'" download></a>';
            }

          }
          if($value['anulado'] =='s'){
            foreach($bajasComprobantes as $k => $v){
              $btnXml = '<a href="./api/'.$ruta_xml.'/'.$v['nombrexml'].'" target="_blank" class="xml"  id="xml" idComp="'.$value['id'].'" download></a>';
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
            $botonEstadoCdr = '<a href="./api/'.$ruta_cdr.'/R-'.$nombre.'" target="_blank" class="cdr"  id="cdr" idComp="'.$notaC['id'].'" ></a>';
           
          }
            else if($value['serie_correlativo'] == $notaD['seriecorrelativo_ref']){

              $nombre = $emisor['ruc'].'-'.$notaD['tipocomp'].'-'.$notaD['serie'].'-'.$notaD['correlativo'];
                $nombre = $nombre.'.XML';
            $botonEstadoCdr = '<a href="./api/'.$ruta_cdr.'/R-'.$nombre.'" target="_blank" class="cdr"  id="cdr" idComp="'.$notaD['id'].'" ></a>';
           
          }else{
            
            $botonEstadoCdr = '<a href="./api/'.$ruta_cdr.'/R-'.$value['nombrexml'].'" target="_blank" class="cdr"  id="cdr" idComp="'.$value['id'].'" ></a>';
            }

          }else{
            foreach($bajasComprobantes as $k => $v){
            $botonEstadoCdr = '<a href="./api/'.$ruta_cdr.'/R-'.$v['nombrexml'].'" target="_blank" class="cdr"  id="cdr" idComp="'.$value['id'].'" ></a>';
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
           }
          else if($value['serie_correlativo'] == $notaD['seriecorrelativo_ref']){

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




    // DATA_TABLE QUE LISTA TODOS LOS RESÚMENES DIARIOS
  public  function dtaResumenDiario(){

    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if($action == 'ajax'){
       // escaping, additionally removing everything that could be (html/javascript-) code
       //$empresa = ControladorEmpresa::ctrModoProduccion();
       	// RUTAS DE CDR Y XML 
         $ruta_xml = "xml";
         $ruta_cdr = "cdr";
      

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
           <td class='t-md'>".date_format(date_create($value['fecha_envio']), 'd-m-Y')."</td>

           <td class='t-md'>".date_format(date_create($value['fecha_emision']), 'd-m-Y')."</td>

           <td>
           <div class='btn-ver_boletas'>
           <button id='btnVerBoletas' class='btn btn-primary' idenvio=".$value['idenvio']." data-toggle='modal' data-target='#modalBoletassssss'><i class='far fa-eye' ></i> VER BOLETAS</button></
           </div>
           </td>

           <td class='t-md'>".$value['ticket']."</td>";
          
          echo '<td>
           <div class="contenedor-print-comprobantes">
  
          <button class="printA4"  id="printA4" idComp="'.$value['idenvio'].'" ></button>
       
         
           </div>
           
           </td>
         <td>
         <div class="contenedor-print-comprobantes">
         <a href="./api/'.$ruta_xml.'/'.$value['nombrexml'].'" target="_blank" class="xml"  id="xml" idComp="'.$value['idenvio'].'" ></a>
       </div>
         
         </td>
         <td>
         <div class="contenedor-print-comprobantes">
         <a href="./api/'.$ruta_cdr.'/R-'.$value['nombrexml'].'" target="_blank" class="cdr"  id="cdr" idComp="'.$value['idenvio'].'" ></a>
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

   // DATA_TABLE QUE LISTA LAS BOLETAS QUE FUERON ENVIADOS POR RESÚMEN DIARIO
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
       $sTable2 = 'venta';
       $sWhere = "";
       if (isset($search) && $search != '')
       {
          $sWhere = "WHERE (serie_correlativo LIKE '%".$search."%') AND idenvio = $idenvio ";
          
       }else{
        $sWhere = "WHERE idenvio = $idenvio";
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
      $totalRegistros   = $pdo->query("SELECT count(*) AS numrows FROM $sTable t1 INNER JOIN $sTable2 t2 ON t1.idventa=t2.id  $sWhere");
      $totalRegistros = $totalRegistros->fetch()['numrows'];
      $tpages = ceil($totalRegistros/$per_page);
      $reload = './index.php';
      //main query to fetch the data
   
      $pdo =  Conexion::conectar();
      $registros = $pdo->prepare("SELECT t1.idventa, t2.id, t2.fecha_emision, t2.tipocomp, t2.serie_correlativo, t2.serie, t2.correlativo, t2.total, t2.id_nc FROM  $sTable t1 INNER JOIN $sTable2 t2 ON t1.idventa=t2.id  $sWhere  LIMIT $offset,$per_page");
      $registros->execute();
   
      $registros=$registros->fetchall(); 

     
      foreach($registros as $k => $value){
        $item = 'id';
        $valor = $value['id_nc'];
        $notac = ControladorNotaCredito::ctrMostrarNotaCredito($item, $valor);

        echo '<tr class="t-md">
        <td>'.date_format(date_create($value['fecha_emision']), 'd-m-Y').'</td>
        <td>'.$value['tipocomp'].'</td>
        <td>'.$value['serie'].'</td>
        <td>'.$value['correlativo'].'</td>
        <td>'.$value['total'].'</td>';
          if($value['id_nc'] !== null){
        echo '<td>Afectado por NC: '.$notac['serie'].'-'.$notac['correlativo'].'</td>';
      }else{
        echo '<td>Adicionar</td>';
      }

        echo '</tr>';
      
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
      if(isset($_REQUEST['dpg'])){
        if($_REQUEST['dpg'] == "dpg"){
        $dataProductos = new DataTables();
        $dataProductos->dtaProductosGuia();
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
