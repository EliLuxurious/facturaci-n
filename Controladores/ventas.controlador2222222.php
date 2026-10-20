<?php

require_once "cantidad_en_letras.php";

class ControladorVentas{

    // MOSTRAR VENTAS
    public static function ctrMostrarVentas($item, $valor){
        $tabla = "venta";
        $respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
        return $respuesta;
    }
  
    // MOSTRAR VENTAS
    public static function ctrMostrarDetalles($item, $valor){
        $tabla = "detalle";
        $respuesta = ModeloVentas::mdlMostrarDetalles($tabla, $item, $valor);
        return $respuesta;
    }
    // MOSTRAR VENTAS DETALLES PRODUCTOS
    public static function ctrMostrarDetallesProductos($item, $valor){
      
        $respuesta = ModeloVentas::mdlMostrarDetallesProductos($item, $valor);
        return $respuesta;
    }





    // LLENAR CARRITO DE COMPRAS
    public static function ctrLlenarCarrito($item, $valor, $cantidad, $descuentoGlobal, $moneda, $tipo_desc, $descuentoGlobalP, $tipo_cambio){
		
		if($moneda == "PEN"){
			$simboloMoneda = "S/ ";
		}if($moneda == "USD"){
			$simboloMoneda = '$USD ';
		}
	
		
        $tabla = "productos";
        $producto = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor);
        
       
			if(!isset($_SESSION['carrito'])){
				$_SESSION['carrito'] = array();
			}

			$carrito = $_SESSION['carrito'];

			//$item = count($carrito)+1;
            if($cantidad != null){
				$item = count($carrito)+1;
			$cantidad = $cantidad;
			$existe = false;
			foreach ($carrito as $k => $v) {
				if($v['codigo']==$producto['codigo']){
					$item = $k;
					$existe = true;
					break;
				}
			}	
			$cantidad = $cantidad;
		
			$carrito[$item] = array(
						'id'=> $producto['id'],
						'codigo'=> $producto['codigo'],
						'descripcion'=> $producto['descripcion'],
						'valor_unitario'=> $producto['precio_sin_igv'],
						'precio_unitario'=> $producto['precio_venta'],
						'igv' => $producto['igv'],
						'unidad'=> $producto['codunidad'],
						'codigoafectacion'=> $producto['codigoafectacion'],
						'cantidad'=> $cantidad
						);
					
            }
		

			$_SESSION['carrito'] = $carrito;
            //extract($_REQUEST);
        
            foreach($carrito as $k=>$v){
				
				if($moneda == "USD"){
					$v['precio_unitario'] = $v['precio_unitario']/ $tipo_cambio;
					$v['valor_unitario'] = $v['valor_unitario']/ $tipo_cambio;
				}
				$valor_unitario = $v['valor_unitario'];
				$precio_unitario = $v['precio_unitario'];
				if($v['codigoafectacion'] == '10'){

					$total_c = $valor_unitario * $v['cantidad'];

				}else{
					$total_c = $v['precio_unitario']*$v['cantidad'];
				}
				$total_comp = $total_c;
				echo "<tr class='id-eliminar".$k."'>";
				echo "<td>".$v['codigo']."</td><td>".$v['cantidad']."</td><td>".$v['unidad']."</td><td>".$v['descripcion']."</td><td>".round($precio_unitario,2)."</td><td>".round($valor_unitario,2)."</td><td>".round($total_c,2)."</td>";
				echo "<td><button type='button' class='btn btn-danger btn-xs btnEliminarItemCarro' itemEliminar='".$k."'><i class='fas fa-trash-alt'></i></button></td></tr>";

			}
			
            //-------------- INICIO DE CALCULO DE TOTALES -------//
			$op_gravadas=0.00;
			$op_exoneradas=0.00;
			$op_inafectas=0.00;
			$igv = 0.00;
			$igv_porcentaje=0.18;
			
			foreach ($carrito as $K => $v) {
				if($moneda == "USD"){
					$v['precio_unitario'] = $v['precio_unitario']/ $tipo_cambio;
				}
				if($v['codigoafectacion']=='10'){
					$op_gravadas += ($v['valor_unitario']*$v['cantidad']);
										
				}

				if($v['codigoafectacion']=='20'){
					$op_exoneradas += $v['precio_unitario']*$v['cantidad'];
					
				}

				if($v['codigoafectacion']=='30'){
					$op_inafectas = $op_inafectas+$v['precio_unitario']*$v['cantidad'];
					
				}	
				$igv =  $op_gravadas * 0.18;						
			}
			
			
			

			$sub_total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;
			$sub_to = $op_gravadas + $op_exoneradas + $op_inafectas;
			
			//----- FIN DEL CALCULO DE TOTALES --------//
			// ALGORITMO DESCUENTO
	
			if($tipo_desc == 'S/'){
				@$desc_porcentaje = ($descuentoGlobal / $sub_total);	
				@$convertir = (($descuentoGlobal * 100) / $sub_total);
				$opg = $op_gravadas *($convertir/100);
				$ope = $op_exoneradas *($convertir/100);
				$opi = $op_inafectas *($convertir/100);
				$opigv= $igv *($convertir/100);
				$op_desc = $sub_to * ($convertir/100);
				$op_gravadas =  $op_gravadas - $opg;
				$op_exoneradas = $op_exoneradas - $ope;
				$op_inafectas = $op_inafectas - $opi;
				$igv = $igv - $opigv;		
				$descuentoGlobal = $op_desc;
				echo "<script>
				$('#descuentoGlobalP').val('".(round($desc_porcentaje*100,2))."');
				</script>";
				}
		if($tipo_desc == '%'){
		@$desc_porcentaje = ($sub_total * $descuentoGlobal)/100;	
		@$convertir = ($descuentoGlobalP);
		$opg = $op_gravadas * $convertir/100;
		$ope = $op_exoneradas *$convertir/100;
		$opi = $op_inafectas *$convertir/100;
		$opigv= $igv *$convertir/100;
		$op_desc = $sub_to * $convertir/100;
		$op_gravadas =  $op_gravadas - $opg;
		$op_exoneradas = $op_exoneradas - $ope;
		$op_inafectas = $op_inafectas - $opi;
		$igv = $igv - $opigv;		
		$descuentoGlobal = $op_desc;
		echo "<script>
		$('#descuentoGlobal').val('".(round($desc_porcentaje,2))."');
		</script>";

		}
			
		$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;

			$op_gravadas = round($op_gravadas,2);
			$op_exoneradas = round($op_exoneradas,2);
			$op_inafectas = round($op_inafectas,2);
			$descuentoGlobal = round($descuentoGlobal,2);
			$igv = round($igv,2);
			
			$total = round($total,2);
		
		
		
			if($descuentoGlobal > 0){
				echo "<script>
						$('.op-subt').show();
						</script>";
				}else{
					echo "<script>
						$('.op-subt').hide();
						</script>";
				
			}
			if($op_gravadas > 0){
				echo "<script>
                    $('.op-gravadas').show();
					</script>";
			}else{
				echo "<script>
                    $('.op-gravadas').hide();
					</script>";
			}
			if($op_exoneradas > 0){
				echo "<script>
                    $('.op-exoneradas').show();
					</script>";
			}else{
				echo "<script>
                    $('.op-exoneradas').hide();
					</script>";
			}
			if($op_inafectas > 0){
				echo "<script>
                    $('.tabla-totales .totales .op-inafectas').show();
					</script>";
			}else{
				echo "<script>
                    $('.tabla-totales .totales .op-inafectas').hide();
					</script>";
			}
			if(empty($carrito)){
				echo "<script> 
				$('.tabla-totales .totales .op-igv').children().next().html(0);
				$('.tabla-totales .totales .op-total').children().next().html(0);
				$('.tabla-totales .totales .op-descuento').children().next().html(0);
				</script>";
				
			}else{
            echo "<script>
				
                    $('.tabla-totales .totales .op-subt').children().next().html('".$simboloMoneda.round($sub_total,2)."');
                    $('.tabla-totales .totales .op-gravadas').children().next().html('".$simboloMoneda.$op_gravadas."');
                    $('.tabla-totales .totales .op-exoneradas').children().next().html('".$simboloMoneda.$op_exoneradas."');
                    $('.tabla-totales .totales .op-inafectas').children().next().html('".$simboloMoneda.$op_inafectas."');
                    $('.tabla-totales .totales .op-descuento').children().next().html('".$simboloMoneda.$descuentoGlobal."');
                    $('.tabla-totales .totales .op-igv').children().next().html('".$simboloMoneda.$igv."');
                    $('.tabla-totales .totales .op-total').children().next().html('".$simboloMoneda.$total."');
					
					
            
				
                </script>";
			}
    }
	
	    // GUARDAR VENTA
	public static function ctrGuardarVenta($doc, $clienteBd){
	
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
	$emisor = ControladorClientes::ctrEmisor();
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
			$igv_porcentaje = 0.18;
			$op_gf = 0.00;
			$pre_u =0.0;
			$op_gravadas=0.00;
			$op_exoneradas=0.00;
			$op_inafectas=0.00;
			$igv = 0.00;
			$igv_porcentaje=0.18;	
			
			foreach ($carrito as $k => $v){
				
				if($doc['moneda'] == 'USD'){
					$v['precio_venta'] = $v['precio_venta'] / $doc['tipo_cambio'];
					$v['precio'] = $v['precio'] / $doc['tipo_cambio'];
				}

				$item = "codigo";
				$valor = $v['codigo'];
				$producto = ControladorProductos::ctrMostrarProductos($item, $valor);
				
				$item = "codigo";
				$valor = $producto['codigoafectacion'];
				$afectacion = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);

				$igv_detalle =0;
				$factor_porcentaje = 1;

				
				if($producto['codigoafectacion']==10){
			
					$valor_total = ($v['valor_unitario']*$v['cantidad']);
					$igv_detalle = $valor_total * 0.18;

				}
				if($producto['codigoafectacion']==20){
					$valor_total = $v['precio_unitario']*$v['cantidad'];
					$igv_detalle = 0;

				}
				if($producto['codigoafectacion']==30){
					$valor_total = $v['precio_unitario']*$v['cantidad'];
					$igv_detalle = 0;

				}
				

				$itemx = array(
					'item'				=> ++$k,
					'codigo'			=> $v['codigo'],
					'descripcion'		=> $v['descripcion'],
					'cantidad'			=> $v['cantidad'],
					'valor_unitario'	=> round($v['valor_unitario'],2),
					'precio_unitario'	=> round($v['precio_unitario'],2),
					'tipo_precio'		=> $producto['tipo_precio'], //ya incluye igv
					'igv'				=> round($igv_detalle,2),
					'porcentaje_igv'	=> $igv_porcentaje*100,
					'valor_total'		=> round($valor_total,2),
					'importe_total'		=> round($v['precio_unitario'] *  $v['cantidad'],2),
					'unidad'			=> $v['unidad'],//unidad,
					'codigo_afectacion_alt'	=> $producto['codigoafectacion'],
					'codigo_afectacion'	=> $afectacion['codigo_afectacion'],
					'nombre_afectacion'	=> $afectacion['nombre_afectacion'],
					'tipo_afectacion'	=> $afectacion['tipo_afectacion']			 
				);

				$itemx;

				$detalle[] = $itemx;

				

					if($v['codigoafectacion']=='10'){
					
					$op_gravadas += ($v['valor_unitario']*$v['cantidad']);
						
					}
	
					if($v['codigoafectacion']=='20'){
						$op_exoneradas += $v['precio_unitario']*$v['cantidad'];
						
						
					}
	
					if($v['codigoafectacion']=='30'){
						$op_inafectas += $v['precio_unitario']*$v['cantidad'];
						
					}	
					$igv =  $op_gravadas * 0.18;						
				
				
			}
				 //-------------- INICIO DE CALCULO DE TOTALES -------//
			
			$op_exoneradas_r = $op_exoneradas;
			$sub_total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;
			$sub_to = $op_gravadas + $op_exoneradas + $op_inafectas;
			//----- FIN DEL CALCULO DE TOTALES --------//
			// ALGORITMO DESCUENTO
			$subDescuento = $doc['descuento'];
			$descuentoGlobal = $doc['descuento'];
	
		// CÁLCULO DE OPERACIONES EN CASCADA============================
		@$desc_porcentaje = round(($descuentoGlobal / $sub_total), 3);	
		@$convertir = (($descuentoGlobal * 100) / $sub_total);
		$opg = $op_gravadas *($convertir/100);
		$ope = $op_exoneradas *($convertir/100);
		$opi = $op_inafectas *($convertir/100);
		$opigv= $igv *($convertir/100);
		$op_desc = $sub_to * ($convertir/100);
		$op_gravadas =  $op_gravadas - $opg;
		$op_exoneradas = $op_exoneradas - $ope;
		$op_inafectas = $op_inafectas - $opi;
		$igv = $igv - $opigv;		
		$descuentoGlobal = $op_desc;
		// FIN CÁLCULO DE OPERACIONES EN CASCADA============================
	
		// REDONDEAR TOTALES |=================================
			$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;
			 $op_gravadas = round($op_gravadas,2);
			 $op_exoneradas = round($op_exoneradas,2);
			 $op_inafectas = round($op_inafectas,2);
			 $descuentoGlobal = round($descuentoGlobal,2);
			 $igv = round($igv,2);
			
			 $total = round($total,2);
	
		// FIN REDONDEAR TOTALES |=================================
			if($op_gravadas > 0){
				$codigo_tipo = "02";
			}
			if($op_gravadas > 0 AND $op_exoneradas > 0){
				$codigo_tipo = "02";
				$op_exoneradas = $op_exoneradas_r;
				
			}
			

			$item = 'id';
			$valor = $doc['idSerie'];
			$seriex = ControladorSunat::ctrMostrarCorrelativo($item, $valor);
			$comprobante =	array(
					'tipodoc'		=> $seriex['tipocomp'],
					'idserie'		=> $doc['idSerie'],
					'serie'			=> $seriex['serie'],
					'correlativo'	=> $seriex['correlativo']+1,
					'fecha_emision' =>date('Y-m-d'),
					'moneda'		=> $doc['moneda'], //PEN->SOLES; USD->DOLARES
					'total_opgravadas'	=> $op_gravadas,
					'igv'			=> $igv,
					'total_opexoneradas' => $op_exoneradas,
					'total_opinafectas'	=> $op_inafectas,

					'codigo_tipo'	=> $codigo_tipo,
					// 'monto_base'	=> $descuentoGlobal,
					// 'descuento_factor'	=> 1, //1
					'descuento'	=> $descuentoGlobal,
					'monto_base'	=> round($sub_to,2),
					'descuento_factor'	=> $desc_porcentaje, //1
					'descuento'	=> $descuentoGlobal,
					
					'subdescuento'	=> $subDescuento,
					'total'			=>$total,
					'total_texto'	=> CantidadEnLetra($total),
					'codcliente'	=> $idcliente,					
					'codvendedor'	=> $_SESSION['id'],					
					'codigo_doc_cliente' 	=> $cliente['tipodoc'],
					'serie_correlativo'	=> $seriex['serie'].'-'.($seriex['correlativo']+1),
					'metodopago' 	=> $doc['metodopago'],
					'comentario'	=> $doc['comentario']
				);
				//  var_dump($detalle);
				// var_dump($comprobante);
				//  exit();
			
				// VALIDANDO NUMERO DE RUC Y DNI====================
			if($comprobante['total'] > 0)	{
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
		
				// INICIO FACTURACIÓN ELECTRÓNICA
				$nombre = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];

				// RUTAS DE CDR Y XML 
					$ruta_archivo_xml = "../api/xml/";
					$ruta_archivo_cdr = "../api/cdr/";
					$ruta = "../api/xml/";
								

				if($doc['envioSunat'] != 'no'){

					if($doc['envioSunat'] == 'firmar'){

				if($comprobante['tipodoc']=='01' || $comprobante['tipodoc']=='03'){

					$generadoXML = new GeneradorXML();
					$generadoXML->CrearXMLFactura($ruta.$nombre, $emisor, $cliente, $comprobante, $detalle);
					echo "EL COMPROBANTE HA SIDO FIRMADO<br>";
					}
				}
			if($doc['envioSunat'] == 'enviar'){

				if($comprobante['tipodoc']=='01' || $comprobante['tipodoc']=='03'){

					$generadoXML = new GeneradorXML();
					$generadoXML->CrearXMLFactura($ruta.$nombre, $emisor, $cliente, $comprobante, $detalle);
					
				}

				$datos_comprobante = array(
						'codigocomprobante' => $comprobante['tipodoc'],
						'serie' 	=> $comprobante['serie'],
						'correlativo' => $comprobante['correlativo']
				);

					
				$api = new ApiFacturacion();				
					$api->EnviarComprobanteElectronico($emisor,$nombre, "../", $ruta_archivo_xml, $ruta_archivo_cdr, $datos_comprobante);			
				
				$codigosSunat = array(
					"feestado" => $api->codrespuesta,
					"fecodigoerror"  => $api->coderror,
					"femensajesunat"  => $api->mensajeError,
					"nombrexml"  => $api->xml,
					"xmlbase64"  => $api->xmlb64,
					"cdrbase64"  => $api->cdrb64,
				);
			}

		}
		if(empty($codigosSunat)){
			$codigosSunat = array(
			"feestado" => '',
			"fecodigoerror"  => '',
			"femensajesunat"  => '',
			"nombrexml"  => $nombre.'.XML',
			"xmlbase64"  => '',
			"cdrbase64"  =>''
			);
		}
				//FIN FACTURACION ELECTRONICA
				
				$datos = array(
					'id' => $doc['idSerie'],
					'correlativo' 	=> $comprobante['correlativo'],
				);

				$actualizarSerie = ControladorSunat::ctrActualizarCorrelativo($datos);
			//REGISTRO EN BASE DE DATOS
			
			$idemisor = 1;
			$insertarVenta = ModeloVentas::mdlInsertarVenta($idemisor,$comprobante, $codigosSunat);
			
			$venta = ModeloVentas::mdlObtenerUltimoComprobanteId();
			
			$idventa = $venta['id'];
			$_SESSION['idventa'] = $idventa;

			$insertarDetalles = ModeloVentas::mdlInsertarDetalles($idventa, $detalle);
			
			//FIN DE REGISTRO EN BASE DE DATOS
			//echo "VENTA CORRECTA";
			if($insertarVenta == 'ok') {
			echo "
				   <div class='contenedor-print'>
				  <form id='printC' name='printC' method='post' action='vistas/print/printer/' target='_blank'>
				 <input type='radio' id='a4' name='a4' value='A4'>
				 <input type='radio' id='tk' name='a4' value='TK'>
				 <input type='hidden' id='idCo' name='idCo' value='".$venta['id']."'>
				  <button  id='printA4' ></button>
				  <button id='printT'></button>
				  </form></div>";

			}
			echo "<input type='hidden' id='idCo' name='idCo' value='".$idventa."'>";
			echo "<input type='hidden' id='email' name='email' value='".$doc['email']."'>";
			
			$carrito=$_SESSION['carrito'];
			//Asignamos a la variable $carro los valores guardados en la sessión
			unset($_SESSION['carrito']);
			//la función unset borra el elemento de un array que le pasemos por parámetro. En este
			//caso la usamos para borrar el elemento cuyo id le pasemos a la página por la url 
			echo "<input type='hidden' id='idCo' value='".$venta['id']."'>";
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
public function ctrListarVentas(){

	$respuesta = ModeloVentas::mdlListarVentas();
	echo $respuesta;
}
}