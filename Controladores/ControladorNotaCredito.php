<?php
namespace Controladores;
use Modelos\ModeloNotaCredito;
use api\GeneradorXML;
use api\ApiFacturacion;
require_once "cantidad_en_letras.php";

class ControladorNotaCredito{

	  // MOSTRAR NOTA DE CRÉDITO
	  public static function ctrMostrarNotaCredito($item, $valor){
        $tabla = "nota_credito";
        $respuesta = ModeloNotaCredito::mdlMostrarNotaCredito($tabla, $item, $valor);
        return $respuesta;
    }

    // MOSTRAR  DETALLES NOTA DE CRÉDITO Y PRODUCTOS
    public static function ctrDetallesNotaCreditoProductos($item, $valor){
      
        $respuesta = ModeloNotaCredito::mdlDetallesNotaCreditoProductos($item, $valor);
        return $respuesta;
    }
 
//=======================================
//  GUARDAR NOTA DE CRÉDITO
public static function ctrGuardarNotaCredito($valor, $doc){

		$item = "serie_correlativo";
		$valor = $valor;
		$venta = ControladorVentas::ctrMostrarVentas($item, $valor);

		$item = "id";
		$valor = $venta['codcliente'];
		$clienteDatos = ControladorClientes::ctrMostrarClientes($item, $valor);
		$idcliente =  $clienteDatos['id'];

	$emisor = ControladorEmpresa::ctrEmisor();
	$emisorigv = new ControladorEmpresa();
	$emisorigv->ctrEmisorIgv();

	if($venta['tipocomp'] == "03"){

	$cliente = array(
		'tipodoc'		=> $doc['tipodoc'],//6->ruc, 1-> dni 
		'ruc'			=> $clienteDatos['documento'], 
		'razon_social'  => $clienteDatos['nombre'], 
		'direccion'		=> $clienteDatos['direccion'],
		'pais'			=> 'PE'
		);	
	}
	if($venta['tipocomp'] == "01"){

		$cliente = array(
			'tipodoc'		=> $doc['tipodoc'],//6->ruc, 1-> dni 
			'ruc'			=> $clienteDatos['ruc'], 
			'razon_social'  => $clienteDatos['razon_social'], 
			'direccion'		=> $clienteDatos['direccion'],
			'pais'			=> 'PE'
			);	
	}

	$carrito = $_SESSION['carrito'];
	//extract($_REQUEST);
	$detalle = array();
	$igv_porcentaje = $emisorigv->igv_dos;
	$op_gf = 0.00;
	$pre_u =0.0;
	$op_grav=0.00;
	$op_gravadas=0.00;
	$op_exoneradas=0.00;
	$op_inafectas=0.00;
	$igv = 0.00;
	$igv_op = 0.00;
	$igv_op_g = 0.00;
	$igv_op_i = 0.00;
	$igv_opi = 0.00;
	$desc_factor = 0.0;	
	$total_icbper = 0.0;
	$op_gratuitas_gravadas = 0.00;
	$op_gratuitas_inafectas = 0.00;
	$nombreMoneda = 'SOLES';
	$carrito = array_values($carrito);
				foreach ($carrito as $k => $v){
					if($doc['moneda'] == "USD"){
						$v['valor_unitario'] = $v['valor_unitario']/ $doc['tipo_cambio'];
						$v['precio_unitario'] = $v['precio_unitario']/ $doc['tipo_cambio'];
						$v['igv'] = $v['igv'] / $doc['tipo_cambio'];
						$v['descuento_item'] = $v['descuento_item'] / $doc['tipo_cambio'];
						$nombreMoneda = 'DÓLARES';
					}   

				$item = "codigo";
				$valor = $v['codigo'];
				$producto = ControladorProductos::ctrMostrarProductos($item, $valor);
				
				$item = "codigo";
				$valor = $v['codigoafectacion'];
				$afectacion = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);
				$igv_detalle =0;
				$factor_porcentaje = 1;

					
				$tipo_precio = $producto['tipo_precio'];
				$valor_uni = $v['precio_unitario'] / $emisorigv->igv_uno;
				if($v['codigoafectacion']=='10'){
				
					$valor_total = $valor_uni * $v['cantidad']- $v['descuento_item'];

					$igv_detalle = $v['igv'];
					$igv_opi =  $v['igv'];
					$importe_total = ($valor_uni * $v['cantidad'] ) - $v['descuento_item'] + $igv_detalle;
					
				
					$monto_base  = ($valor_uni * $v['cantidad']);
					$valor_unitario = ($valor_uni);
					
					$factor = ($v['descuento_item'] * 100 / $monto_base) /100;
						$precio_unitario2 = $v['precio_unitario'] * $factor;
						$precio_unitario = $v['precio_unitario'] - $precio_unitario2;
				}

				if($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16'){

					$valor_total = $valor_uni * $v['cantidad'];
					$igv_detalle =  $valor_total * $emisorigv->igv_dos;
					$igv_opi =  0.00;
				
					$importe_total = ($valor_uni * $v['cantidad']);	
				
					$monto_base = ($valor_uni * $v['cantidad']);
					$valor_unitario = 0;
					$tipo_precio = '02';
					
						$precio_unitario = $valor_uni;
				}
				if($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36'){

					$valor_total = $valor_uni * $v['cantidad'];
					$igv_detalle =  0.00;
					$igv_opi =  0.00;
				
					$importe_total = ($valor_uni * $v['cantidad']);	
				
					$monto_base = ($valor_uni * $v['cantidad']);
					$valor_unitario = 0;
					$tipo_precio = '02';
					
						$precio_unitario = $valor_uni;
				}

				if($v['codigoafectacion']=='20'){
					$valor_total = $v['precio_unitario'] * $v['cantidad']- $v['descuento_item'];
					$igv_detalle = 0;
					$igv_opi =  0.00;
					$importe_total = ($v['precio_unitario'] * $v['cantidad'] )- $v['descuento_item'];	
				
					$monto_base = ($v['precio_unitario'] * $v['cantidad']);
					$valor_unitario = ($v['precio_unitario']);

					$factor = ($v['descuento_item'] * 100 / $monto_base) /100;
						$precio_unitario2 = $v['precio_unitario'] * $factor;
						$precio_unitario = $v['precio_unitario'] - $precio_unitario2;

				}

				if($v['codigoafectacion']=='30'){
					$valor_total = $v['precio_unitario']*$v['cantidad']- $v['descuento_item'];
					$igv_detalle = 0;
					$igv_opi =  0.00;
					$importe_total = ($v['precio_unitario'] * $v['cantidad'] )- $v['descuento_item'];

					$monto_base = ($v['precio_unitario'] *  $v['cantidad']);	
					$valor_unitario = ($v['precio_unitario']);

					$factor = ($v['descuento_item'] * 100 / $monto_base) /100;
						$precio_unitario2 = $v['precio_unitario'] * $factor;
						$precio_unitario = $v['precio_unitario'] - $precio_unitario2;
				}

				$itemx = array(
					'item'				=> ++$k,
					'codigo'			=> $v['codigo'],
					'descripcion'		=> $v['descripcion'],
					'cantidad'			=> $v['cantidad'],
					'descuentos' 			=> array(
									'codigoTipo' 	=> '00',
									'montoBase'	=> round($monto_base,2),
									'factor' => round($factor,5),
									'monto' => $v['descuento_item'],
					),
					'valor_unitario'	=> round($valor_unitario,2),
					'precio_unitario'	=> round($precio_unitario,2),
					'tipo_precio'		=> $tipo_precio, //ya incluye igv
					'igv'				=> round($igv_detalle,2),
					'igv_opi'				=> round($igv_opi + $v['icbper'],2),
					'porcentaje_igv'	=> $igv_porcentaje*100,
					'valor_total'		=> round($valor_total,2),
					'importe_total'		=> round($importe_total,2),
					'unidad'			=> $v['unidad'],//unidad,
					'codigo_afectacion_alt'	=> $afectacion['codigo'],
					'codigo_afectacion'	=> $afectacion['codigo_afectacion'],
					'nombre_afectacion'	=> $afectacion['nombre_afectacion'],
					'tipo_afectacion'	=> $afectacion['tipo_afectacion'],
					'id'	=> $v['id'],
					'icbper' 	=> round($v['icbper'],2)		 
				);

				$itemx;

				$detalle[] = $itemx;
				// var_dump($detalle);
				// exit();
				if($v['codigoafectacion']=='10'){					
				
					$op_gravadas += ($valor_uni * $v['cantidad']) - $v['descuento_item'];
					
				}
				if($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16'){
					
					$op_gratuitas_gravadas += $valor_uni * $v['cantidad'];
				
					$igv_op_g =  $op_gratuitas_gravadas * $emisorigv->igv_dos;	
					
				}
				if($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36'){
				
					$op_gratuitas_inafectas += $valor_uni * $v['cantidad'];
				
					$igv_op_i =  0.00;		
					
				}

				if($v['codigoafectacion']=='20'){
					$op_exoneradas += $v['precio_unitario']*$v['cantidad'] - $v['descuento_item'];
					
				
				}

				if($v['codigoafectacion']=='30'){
					$op_inafectas += $v['precio_unitario']*$v['cantidad'] - $v['descuento_item'];
				
					
				}	
									
				$igv +=  $v['igv'];
				$igv_op = $igv_op_g + $igv_op_i;
				$total_icbper += $v['icbper'];

		}
			 //-------------- INICIO DE CALCULO DE TOTALES -------//
		
		$sub_to = $op_gravadas + $op_exoneradas + $op_inafectas;	
		
		$op_gratuitas = $op_gratuitas_gravadas + $op_gratuitas_inafectas;
			//----- FIN DEL CALCULO DE TOTALES --------//
			// ALGORITMO DESCUENTO
			$subDescuento = $doc['descuento'];
			$descuentoGlobal = $doc['descuento'];
	
		// CÁLCULO DE OPERACIONES EN CASCADA============================
		if($descuentoGlobal > 0){
		$desc_factor =($descuentoGlobal * 100 / $sub_to)  / 100;
		@$desc_porcentaje2 = $descuentoGlobal * 100 / $op_gravadas;
	    $desc_porcentaje = $desc_porcentaje2 / 100;		
	    $opg = round($op_gravadas * $desc_porcentaje,2);
		$ope = $op_exoneradas * $desc_porcentaje;
		$opi = $op_inafectas * $desc_porcentaje;
		$opigv= $igv * $desc_porcentaje;
		$op_desc = $op_gravadas * $desc_porcentaje;
		
		$op_gravadas =  $op_gravadas - $opg;	
		$op_exoneradas = $op_exoneradas;		
		$op_inafectas = $op_inafectas;
		$igv = $op_gravadas * $emisorigv->igv_dos;		
		$descuentoGlobal = $op_desc;
		// FIN CÁLCULO DE OPERACIONES EN CASCADA============================
		
		}
		// FIN REDONDEAR TOTALES |=================================
		$codigo_tipo = "02";
			
			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;

			$descuento_factor = round($desc_factor,5);
			$monto_desc = round($descuentoGlobal,2);

			$item = 'id';
			$valor = $doc['idSerie'];
			$seriex = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

			$item = "tipo";
			$valor = 'C';
			$codigo = $doc['motivo'];
			$motivo = ControladorSunat::ctrMostrarTablaParametrica($item, $valor, $codigo);

			$comprobante =	array(
					'tipodoc'		=> $seriex['tipocomp'],
					'idserie'		=> $doc['idSerie'],
					'serie'			=> $seriex['serie'],
					'correlativo'	=> $seriex['correlativo']+1,
					'fecha_emision' => date('Y-m-d'),
					'moneda'		=> $doc['moneda'], //PEN->SOLES; USD->DOLARES
					'total_opgravadas'	=> round($op_gravadas,2),
					'igv'			=> round($igv,2),
					'igv_op'			=> round($igv_op ,2),
					'total_opexoneradas' => round($op_exoneradas,2),
					'total_opinafectas'	=> round($op_inafectas,2),
					'total_opgratuitas'	=> round($op_gratuitas,2),
					'codigo_tipo'	=> $codigo_tipo,
					'monto_base'	=> round($sub_to,2),
					'descuento_factor'	=> round($desc_factor,5), //1
					'descuento'	=> 			$monto_desc,					
					'subdescuento'	=> $subDescuento,
					'total'			=>round($total,2),
					'total_texto'	=> CantidadEnLetra($total, $nombreMoneda),
					'codcliente'	=> $idcliente,
					'tipocomp_ref'	=> $venta['tipocomp'],
					'serie_ref'		=> $venta['serie'],
					'correlativo_ref'=> $venta['correlativo'],
					'codmotivo'		=> $motivo['codigo'],
					'descripcion'	=> $motivo['descripcion'],
					"seriecorrelativo_ref"	=> $venta['serie'].'-'.$venta['correlativo'],
					'tipocambio'	=>		$venta['tipocambio'],
					'icbper' => round($total_icbper,2)			
				);
				
		// var_dump($detalle);
		// var_dump($comprobante);
		// die();

			if($comprobante['total'] > 0)	{
				
		// FIN VALIDANDO NUMERO DE RUC Y DNI====================
		
				// INICIO FACTURACIÓN ELECTRÓNICA
				$nombre = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];

					// RUTAS DE CDR Y XML 
					$ruta_archivo_xml = "../api/xml/";
					$ruta_archivo_cdr = "../api/cdr/";
					$ruta = "../api/xml/";
					
					// COMPROBAR SI HAY INTERNET========
				
					$generadoXML = new GeneradorXML();
					$generadoXML->CrearXMLNotaCredito($ruta.$nombre, $emisor, $cliente, $comprobante, $detalle);
				
				$datos_comprobante = array(
						'codigocomprobante' => $comprobante['tipodoc'],
						'serie' 	=> $comprobante['serie'],
						'correlativo' => $comprobante['correlativo']
				);

				$api = new ApiFacturacion();
				$api->EnviarComprobanteElectronico($emisor,$nombre, $ruta_archivo_xml, $ruta_archivo_cdr,"../");



				$codigosSunat = array(
					"feestado" => $api->codrespuesta,
					"fecodigoerror"  => $api->coderror,
					"femensajesunat"  => $api->mensajeError,
				
				);				
				//FIN FACTURACION ELECTRONICA
			if($codigosSunat['feestado'] == 1){

				$datos = array(
					'id' => $doc['idSerie'],
					'correlativo' 	=> $comprobante['correlativo'],
				);

				$actualizarSerie = ControladorSunat::ctrActualizarCorrelativo($datos);
			//REGISTRO EN BASE DE DATOS
			$idemisor = 1;
			$insertarVenta = ModeloNotaCredito::mdlInsertarNotaCredito($idemisor,$comprobante, $codigosSunat);
		
			
			$notaCredito = ModeloNotaCredito::mdlObtenerUltimaNotaCreditoId();
			
			$idnc = $notaCredito['id'];

			$insertarDetalles = ModeloNotaCredito::mdlInsertarDetallesNotaCredito($idnc, $detalle);
			$item = $venta['id'];
			$valor = $idnc;
			$actualizarVenta = ModeloNotaCredito::mdlActualizarVentaNC($item, $valor);
			//FIN DE REGISTRO EN BASE DE DATOS
			//echo "NOTA DE CRÉDITO REGISTRADA";
			if($insertarVenta == 'ok') {
				if($doc['motivo'] == '01' || $doc['motivo'] == '02' || $doc['motivo'] == '06'){
					$valor = $venta['id'];
					$actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);
					
				}
				echo "
					   <div class='contenedor-print'>
					  <form id='printC' name='printC' method='post' action='vistas/print/nc/' target='_blank'>
					 <input type='radio' id='a4' name='a4' value='A4'>
					 <input type='radio' id='tk' name='a4' value='TK'>
					 <input type='hidden' id='idCo' name='idCo' value='".$notaCredito['id']."'>
					  <button  id='printA4' ></button>
					  <button id='printT'></button>
					  </form></div>";
	
				}
					
			$carrito=$_SESSION['carrito'];
			//Asignamos a la variable $carro los valores guardados en la sessión
			unset($_SESSION['carrito']);
			//la función unset borra el elemento de un array que le pasemos por parámetro. En este
			//caso la usamos para borrar el elemento cuyo id le pasemos a la página por la url 
			echo "<input type='hidden' id='idCo' value='".$notaCredito['id']."'>";
			//Finalmente, actualizamos la sessión,
			}else{
				if($codigosSunat['feestado']==2){
				echo "<script>
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: '¡Mensaje del error!',
				html: '".$codigosSunat['fecodigoerror'].$codigosSunat['femensajesunat']."'
				//footer: '<a href>Why do I have this issue?</a>'
			  })
				</script>";
			}if($codigosSunat['feestado'] == 3)	{
			  echo "<script>
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: '¡Comprueve su conexión o verifíque si está emitiendo bien la nota de crédito!',
				html: ''
				//footer: '<a href>Why do I have this issue?</a>'
			  })
				</script>";
			}
			}		
	



}  
}
}