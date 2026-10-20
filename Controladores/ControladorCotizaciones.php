<?php
namespace Controladores;
use Modelos\ModeloCotizaciones;
use Modelos\ModeloProductos;
use Modelos\ModeloClientes;
use api\GeneradorXML;
use api\ApiFacturacion;

require_once "cantidad_en_letras.php";

class ControladorCotizaciones{

    // MOSTRAR VENTAS
    public static function ctrMostrarCotizacion($item, $valor){
        $tabla = "cotizaciones";
        $respuesta = ModeloCotizaciones::mdlMostrarCotizacion($tabla, $item, $valor);
        return $respuesta;
    }
    // MOSTRAR VENTAS
    public static function ctrMostrarVentasCredito($item, $valor){
        $tabla = "pago_credito";
        $respuesta =ModeloCotizaciones::mdlMostrarCotizacion($tabla, $item, $valor);
        return $respuesta;
    }
  
    // MOSTRAR VENTAS
    public static function ctrMostrarDetalles($item, $valor){
        $tabla = "detalle_cotizaciones";
        $respuesta =ModeloCotizaciones::mdlMostrarDetallesCotizaciones($tabla, $item, $valor);
        return $respuesta;
    }
    // MOSTRAR VENTAS DETALLES PRODUCTOS
    public static function ctrMostrarDetallesProductos($item, $valor){
      
        $respuesta =ModeloCotizaciones::mdlMostrarDetallesProductos($item, $valor);
        return $respuesta;
    }
  
	
	    // GUARDAR COTIZACIÓN
	public static function ctrGuardarCotizacion($doc, $clienteBd){
		
		if($doc['numDoc'] != ''){
		$tabla = "clientes";
		$datos = $clienteBd;
		if($datos['id'] == ''){

		$clientes = ModeloClientes::mdlCrearCliente($tabla, $datos);
		if($datos['tipodoc'] == 1 || $datos['tipodoc'] == 0 || $datos['tipodoc'] == 4 || $datos['tipodoc'] == 7 ){
			$item = 'documento';
		}else{
			$item = 'ruc';
		}
		
		$valor = $doc['numDoc'];
		$clienteExiste = ControladorClientes::ctrMostrarClientes($item, $valor);
		$idcliente =  $clienteExiste['id'];
	}else{
		$idcliente = $datos['id'];
	}
	$emisor = ControladorEmpresa::ctrEmisor();
	$emisorigv = new ControladorEmpresa();
	$emisorigv->ctrEmisorIgv();
	$item = 'id';
	$valor = $idcliente;
	$traerCliente = ControladorClientes::ctrMostrarClientes($item, $valor);

	if($datos['tipodoc'] == 1 || $datos['tipodoc'] == 0 || $datos['tipodoc'] == 4 || $datos['tipodoc'] == 7 ){

	$cliente = array(
		'tipodoc'		=> $datos['tipodoc'],//6->ruc, 1-> dni 
		'ruc'			=> $traerCliente['documento'], 
		'razon_social'  => $traerCliente['nombre'], 
		'direccion'		=> $traerCliente['direccion'],
		'pais'			=> 'PE'
		);	
	}
	if($datos['tipodoc'] == 6){

		$cliente = array(
			'tipodoc'		=> $datos['tipodoc'],//6->ruc, 1-> dni 
			'ruc'			=> $traerCliente['ruc'], 
			'razon_social'  => $traerCliente['razon_social'], 
			'direccion'		=> $traerCliente['direccion'],
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
			$op_gratuitas=0.00;
			$op_gratuitas = 0.00;
			$op_gratuitas_gravadas = 0.00;
			$op_gratuitas_exoneradas = 0.00;
			$op_gratuitas_inafectas = 0.0;
			$igv = 0.00;
			$igv_op = 0.00;
			$igv_op_g = 0.00;
			$igv_op_i = 0.00;
			$igv_opi = 0.00;
			$factor = 0.0;
			$desc_factor = 0.0;	
			$total_icbper = 0.0;
			$tipocambio = 1;
			// var_dump($carrito);
			$nombreMoneda = 'SOLES';
			
			$carrito = array_values($carrito);
			foreach ($carrito as $k => $v){
				$k++ ;

				// if($doc['moneda'] == 'USD'){
				// 	$v['precio_venta'] = $v['precio_venta'] / $doc['tipo_cambio'];
				// 	$v['precio'] = $v['precio'] / $doc['tipo_cambio'];

				// }
				if($doc['moneda'] == "USD"){
					$v['valor_unitario'] = $v['valor_unitario']/ $doc['tipo_cambio'];
					$v['precio_unitario'] = $v['precio_unitario']/ $doc['tipo_cambio'];
					$v['igv'] = $v['igv'] / $doc['tipo_cambio'];
					$v['descuento_item'] = $v['descuento_item'] / $doc['tipo_cambio'];
					$nombreMoneda = 'DÓLARES';
				}
				
				$valor_unitario = $v['valor_unitario'];
				$precio_unitario = $v['precio_unitario'] ;

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

					$igv_detalle = $valor_total * $emisorigv->igv_dos;
					$igv_opi =  $valor_total * $emisorigv->igv_dos;
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
					'item'				=> $k,
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
						$valor_uni = $v['precio_unitario'] / $emisorigv->igv_uno;
						$op_gravadas += ($valor_uni * $v['cantidad']) - $v['descuento_item'];
						
					}
					if($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16'){
						$valor_uni = $v['precio_unitario'] / $emisorigv->igv_uno;
						$op_gratuitas_gravadas += $valor_uni * $v['cantidad'];
					
						$igv_op_g =  $op_gratuitas_gravadas * $emisorigv->igv_dos;	
						
					}
					if($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36'){
					
						$op_gratuitas_inafectas += $v['valor_unitario'] * $v['cantidad'];
					
						$igv_op_i =  0.00;		
						
					}

					if($v['codigoafectacion']=='20'){
						$op_exoneradas += $v['precio_unitario']*$v['cantidad'] - $v['descuento_item'];
						
					
					}
	
					if($v['codigoafectacion']=='30'){
						$op_inafectas += $v['precio_unitario']*$v['cantidad'] - $v['descuento_item'];
					
						
					}	
										
					$igv =  $op_gravadas * $emisorigv->igv_dos;
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
	    $opg = $op_gravadas * $desc_porcentaje;
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

			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv + $total_icbper;
	
			$monto_desc = round($descuentoGlobal,2);

			$item = 'id';
			$valor = $doc['idSerie'];
			$seriex = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

			if($doc['moneda'] == "USD"){			
				$tipocambio = $doc['tipo_cambio'];
			}
			$fecha = $doc['fechaDoc'];
            $fecha2 = str_replace('/', '-', $fecha);
            $fechaEmision = date('Y-m-d', strtotime($fecha2));
			$comprobante =	array(
					'tipodoc'		=> $seriex['tipocomp'],
					'idserie'		=> $doc['idSerie'],
					'serie'			=> $seriex['serie'],
					'correlativo'	=> $seriex['correlativo']+1,
					'fecha_emision' => $fechaEmision,
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
					'total_texto'	=> CantidadEnLetra(round($total,2), $nombreMoneda),
					'codcliente'	=> $idcliente,					
					'codvendedor'	=> $_SESSION['id'],					
					'codigo_doc_cliente' 	=> $cliente['tipodoc'],
					'serie_correlativo'	=> $seriex['serie'].'-'.($seriex['correlativo']+1),
					'metodopago' 	=> $doc['metodopago'],
					'comentario'	=> $doc['comentario'],
					'bienesSelva' 	=> $doc['bienesSelva'],
					'serviciosSelva' => $doc['serviciosSelva'],
					'icbper' => round($total_icbper,2),
					'tipocambio'	=> $tipocambio,
					'tipopago' 	=> $doc['tipopago'],
					'fecha_cuota' 	=> $doc['fecha_cuota'],
					'cuotas' => $doc['cuotas']
				);
				// var_dump($detalle);
			    // var_dump($comprobante);
				//  exit();
			
				// VALIDANDO NUMERO DE RUC Y DNI====================
				if(($comprobante['bienesSelva'] == 'si' || $comprobante['serviciosSelva'] == 'si') && $comprobante['total_opgravadas'] > 0){

					echo "<script>
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'La leyenda de Bienes y Servicios Región Selva solo se permite para IGV exonerados | Error al PreValidar INFO : 3284 (nodo: / valor: )'
						//footer: '<a href>Why do I have this issue?</a>'
					  })
						</script>";
				   exit();
				}
			if($comprobante['total_opgravadas'] > 0 || $comprobante['total_opexoneradas'] || $comprobante['total_opinafectas'] || $comprobante['total_opgratuitas']){

				if($comprobante["tipodoc"] == "01" && (strlen($doc["numDoc"]) < 11 || strlen($doc["numDoc"]) > 11)){
					echo "<script>
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: '¡Debes ingresar un R.U.C. válido!'
						//footer: '<a href>Why do I have this issue?</a>'
					})
					$('#tipoDoc').val(6);
						</script>";
						exit();
				};
				if($comprobante["tipodoc"] == "03" && (strlen($doc["numDoc"]) < 8 || strlen($doc["numDoc"]) > 8)){
					echo "<script>
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: '¡Debes ingresar un D.N.I. válido!'
						//footer: '<a href>Why do I have this issue?</a>'
					})
					$('#tipoDoc').val(1);
						</script>";
						exit();
				};
		// FIN VALIDANDO NUMERO DE RUC Y DNI====================
		
				

		
		
				$datos = array(
					'id' => $doc['idSerie'],
					'correlativo' 	=> $comprobante['correlativo'],
				);

				$actualizarSerie = ControladorSunat::ctrActualizarCorrelativo($datos);
			//REGISTRO EN BASE DE DATOS
			
			$idemisor = 1;
			$insertarCotizacion = ModeloCotizaciones::mdlInsertarCotizacion($idemisor,$comprobante);
			
			$cotizacion = ModeloCotizaciones::mdlObtenerUltimoComprobanteId();
			
			$idcotizacion = $cotizacion['id'];
			$_SESSION['idventa'] = $idcotizacion;

			$insertarDetalles = ModeloCotizaciones::mdlInsertarDetallesCotizaciones($idcotizacion, $detalle);
			if($comprobante['tipopago'] == 'Credito'){
			$insertarVentaCredito = ModeloCotizaciones::mdlInsertarVentaCredito($idcotizacion, $comprobante);
			}
			//FIN DE REGISTRO EN BASE DE DATOS
			//echo "VENTA CORRECTA";
			if($insertarCotizacion == 'ok') {

			
			echo "
				   <div class='contenedor-print'>
				  <form id='printC' name='printC' method='post' action='vistas/print/printCot/' target='_blank'>
				 <input type='radio' id='a4' name='a4' value='A4'>
				 <input type='radio' id='tk' name='a4' value='TK'>
				 <input type='hidden' id='idCo' name='idCo' value='".$cotizacion['id']."'>
				  <button  id='printA4' ></button>
				  
				  </form></div>";
				  echo "<script>
				  
				//   $('#formVenta').each(function(){
				// 	this.reset();					
				//});
				$('#descuentoGlobal').val(0);
				  $('#descuentoGlobalP').val(0);
				  $('#docIdentidad').val('');
				  $('#razon_social').val('');
				  $('#comentario').val('');
				  $('#direccion, #ubigeo, #celular').val('');
				  </script>";

			}
			echo "<input type='hidden' id='idCo' name='idCo' value='".$idcotizacion."'>";
			echo "<input type='hidden' id='email' name='email' value='".$doc['email']."'>";
			
			$carrito=$_SESSION['carrito'];
			//Asignamos a la variable $carro los valores guardados en la sessión
			unset($_SESSION['carrito']);
			//la función unset borra el elemento de un array que le pasemos por parámetro. En este
			//caso la usamos para borrar el elemento cuyo id le pasemos a la página por la url 
			echo "<input type='hidden' id='idCo' value='".$cotizacion['id']."'>";
			//Finalmente, actualizamos la sessión,
				
			//MODO DE IMPRESION INICIO
			
			// echo "<script>window.open('./apifacturacion/pdfFacturaElectronica.php?id=".$venta['id']."','_blank')</script>";	
			//MODO DE IMPRESION FIN
		}else{
			echo "<script>
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: '¡Debes ingresar productos o servicios!'
			//footer: '<a href>Why do I have this issue?</a>'
		  })
			</script>";
		}
	}else{
		if($doc["ruta_comprobante"] == "crear-factura"){
		echo "<script>
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: '¡Debes ingresar el número de R.U.C.!'
			//footer: '<a href>Why do I have this issue?</a>'
		  })
			</script>";
		}else{
			echo "<script>
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: '¡Debes ingresar el número de documento o seleccionar sin documento!'
			//footer: '<a href>Why do I have this issue?</a>'
		  })
			</script>";
		}
	}
	
}

// LISTAR VENTAS BOLETAS FACTURAS
public function ctrListarCotizaciones(){

	$respuesta = ModeloCotizaciones::mdlListarCotizaciones();
	echo $respuesta;
}


}