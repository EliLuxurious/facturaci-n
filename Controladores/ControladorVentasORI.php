<?php
namespace Controladores;
use Modelos\ModeloVentas;
use Modelos\ModeloProductos;
use Modelos\ModeloClientes;
use api\GeneradorXML;
use api\ApiFacturacion;

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
    public static function ctrLlenarCarrito($item, $valor, $datosCarrito){
		$simboloMoneda = '';
		if($datosCarrito['moneda'] == "PEN"){
			$simboloMoneda = "S/ ";
		}if($datosCarrito['moneda'] == "USD"){
			$simboloMoneda = '$USD ';
		}
	
		
        $tabla = "productos";
        $producto = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor);
        
       
			if(!isset($_SESSION['carrito'])){
				$_SESSION['carrito'] = array();
			}

			$carrito = $_SESSION['carrito'];

			//$item = count($carrito)+1;
            if($datosCarrito['cantidad'] != null){
				$item = count($carrito)+1;
			$datosCarrito['cantidad'] = $datosCarrito['cantidad'];
			$existe = false;
			foreach ($carrito as $k => $v) {
				if($v['codigo']==$producto['codigo']){
					$item = $k;
					$existe = true;
					break;
				}
			}	
			$cantidad = $datosCarrito['cantidad'];
		
			$carrito[$item] = array(
						'id'=> $producto['id'],
						'codigo'=> $producto['codigo'],
						'descripcion'=> $producto['descripcion'],
						'valor_unitario'=> $datosCarrito['valor_unitario'],
						'precio_unitario'=> $datosCarrito['precio_unitario'],
						'igv' => $datosCarrito['igv'],
						'unidad'=> $producto['codunidad'],
						'codigoafectacion'=> $datosCarrito['tipo_afectacion'],
						'cantidad'=> $datosCarrito['cantidad'],
						'descuento_item'	=> $datosCarrito['descuento_item'],
						'tipo_afectacion'	=> $datosCarrito['tipo_afectacion'],
						'icbper'	=> $datosCarrito['icbper']
						);
					
            }
		

			$_SESSION['carrito'] = $carrito;
            //extract($_REQUEST);
        
            foreach($carrito as $k=>$v){
				
				if($datosCarrito['moneda'] == "USD"){
					$v['valor_unitario'] = $v['valor_unitario']/ $datosCarrito['tipo_cambio'];
					$v['precio_unitario'] = $v['precio_unitario']/ $datosCarrito['tipo_cambio'];
					$v['igv'] = $v['igv'] / $datosCarrito['tipo_cambio'];
					$v['descuento_item'] = $v['descuento_item'] / $datosCarrito['tipo_cambio'];
				}
				
				$valor_unitario = $v['valor_unitario'];
				$precio_unitario = $v['precio_unitario'] ;
				if($v['codigoafectacion'] == '10'){
			
					$total_c = $valor_unitario * $v['cantidad'] - $v['descuento_item'];
					
				}
				if($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16'){
			
					$total_c = $v['valor_unitario']*$v['cantidad'];
					$valor_unitario = $valor_unitario;
					$precio_unitario = $valor_unitario;
				}
				if($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36'){
			
					$total_c = $v['valor_unitario']*$v['cantidad'];
					$valor_unitario = $valor_unitario;
					$precio_unitario = $valor_unitario;
				}
				if ($v['codigoafectacion'] == '20'){
					$total_c = $v['precio_unitario']*$v['cantidad']- $v['descuento_item'];
				}
				if ($v['codigoafectacion'] == '30'){
					$total_c = $v['precio_unitario']*$v['cantidad']- $v['descuento_item'];
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
			$op_gratuitas=0.00;
			$igv = 0.00;
			$igv_porcentaje=0.18;
			$descuento_item_total =0.00;
			$icbper = 0.00;
			$total_icbper = 0.00;
			
						
			foreach ($carrito as $K => $v) {
				
				if($datosCarrito['moneda'] == "USD"){
					$v['valor_unitario'] = $v['valor_unitario']/ $datosCarrito['tipo_cambio'];
					$v['precio_unitario'] = $v['precio_unitario']/ $datosCarrito['tipo_cambio'];
					$v['igv'] = $v['igv'] / $datosCarrito['tipo_cambio'];
					$v['descuento_item'] = $v['descuento_item'] / $datosCarrito['tipo_cambio'];
				}
				if($v['codigoafectacion']=='10'){			
					
						$op_gravadas += ($v['valor_unitario'] * $v['cantidad'])- $v['descuento_item'];					
										
				}
				if($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16'){
			
					$op_gratuitas += $v['valor_unitario']*$v['cantidad'];

				}
				if($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36'){
			
					$op_gratuitas += $v['valor_unitario']*$v['cantidad'];

				}

				if($v['codigoafectacion']=='20'){
					$op_exoneradas += $v['precio_unitario']*$v['cantidad'] - $v['descuento_item'];
					
				}

				if($v['codigoafectacion']=='30'){
					$op_inafectas += $v['precio_unitario']*$v['cantidad'] - $v['descuento_item'];
					
				}	
				$igv +=  $v['igv'];	
				$descuento_item_total += $v['descuento_item'];	
				
				$total_icbper += $v['icbper'];
							
			}
			// $igv = round($igv,2);
			
			

			$sub_total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;
			$sub_to = $op_gravadas + $op_exoneradas + $op_inafectas;
			$op_gr = $op_gravadas;
			//----- FIN DEL CALCULO DE TOTALES --------//
			// ALGORITMO DESCUENTO
			$descuentoGlobal = $datosCarrito['descuentoG'];
			$descuentoGlobalP = $datosCarrito['descuentoGP'];

			if($datosCarrito['tipo_desc'] == 'S/' && $descuentoGlobal > 0 && $op_gravadas > 0){
				@$desc_porcentaje = ($descuentoGlobal / $op_gravadas);	
				@$convertir = (($descuentoGlobal * 100) / $sub_total);
				$op_desc = $op_gravadas * ($convertir/100);
				$op_gravadas =  $op_gravadas - $descuentoGlobal;
				$op_exoneradas = $op_exoneradas;
				$op_inafectas = $op_inafectas;
				$igv = $op_gravadas * 0.18;		
				$descuentoGlobal = $descuentoGlobal;
				echo "<script>
				$('#descuentoGlobalP').val('".(round($desc_porcentaje*100,5))."');
				</script>";
				}
		if($datosCarrito['tipo_desc'] == '%' && $descuentoGlobalP > 0 &&  $op_gravadas > 0){
		
		$desc_porcentaje = $descuentoGlobalP / 100;
		// $desc_factor =($desc_porcentaje * $sub_to);		
		$opg = $op_gravadas * $desc_porcentaje;
		$op_desc = $op_gravadas * $desc_porcentaje;
		$op_gravadas =  $op_gravadas - $opg;
		$op_exoneradas = $op_exoneradas;
		$op_inafectas = $op_inafectas;
		$igv = $op_gravadas * 0.18;		
		$descuentoGlobal = $op_desc;
		echo "<script>
		$('#descuentoGlobal').val('".(round($desc_porcentaje * $op_gr,2))."');
		</script>";

		}
		

		$total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv + $total_icbper;

			$op_gravadas = number_format($op_gravadas,2);
			$op_exoneradas = number_format($op_exoneradas,2);
			$op_inafectas = number_format($op_inafectas,2);
			$descuentoGlobal = number_format($descuentoGlobal,2);
			$igv = number_format($igv,2);
			
			$total = number_format($total, 2);
		
			if($op_gravadas > 0){
				echo "<script>
						$('#descuentoGlobal').prop('readonly',false);
						$('#descuentoGlobalP').prop('readonly',false);
						</script>";
			}
			
			if(($op_exoneradas > 0 || $op_inafectas > 0) && $op_gravadas == 0){
				echo "<script>
						$('#descuentoGlobal').prop('readonly',true);
						$('#descuentoGlobalP').prop('readonly',true);
						$('#descuentoGlobal').val(0);
						$('#descuentoGlobalP').val(0);
						</script>";
			}
			if(($op_exoneradas > 0 || $op_inafectas > 0) && $op_gravadas > 0){
				echo "<script>
						$('#descuentoGlobal').prop('readonly',false);
						$('#descuentoGlobalP').prop('readonly',false);
						</script>";
			}
		
			if($descuentoGlobal > 0){
				echo "<script>
						$('.op-subt').show();
						</script>";
				}else{
					echo "<script>
						$('.op-subt').hide();
						</script>";
				
			}
			if($total > 0){
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
			if($descuento_item_total > 0){
				echo "<script>
                    $('.op-descuento-item').show();
					</script>";
			}else{
				echo "<script>
                    $('.op-descuento-item').hide();
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
			if($op_gratuitas > 0){
				echo "<script>
                    $('.tabla-totales .totales .op-gratuitas').show();
					</script>";
			}else{
				echo "<script>
                    $('.tabla-totales .totales .op-gratuitas').hide();
					</script>";
			}
			if($total_icbper > 0){
				echo "<script>
                    $('.tabla-totales .totales .icbper').show();
					</script>";
			}else{
				echo "<script>
                    $('.tabla-totales .totales .icbper').hide();
					</script>";
			}
			if(empty($carrito)){
				echo "<script> 
				$('.tabla-totales .totales .op-igv').children().next().html('0.00');
				$('.tabla-totales .totales .op-total').children().next().html('0.00');
				$('.tabla-totales .totales .op-descuento').children().next().html('0.00');
				</script>";
				
			}else{
		
            echo "<script>
				
                    $('.tabla-totales .totales .op-subt').children().next().html('".$simboloMoneda.number_format($sub_total,2)."');
                    $('.tabla-totales .totales .op-descuento-item').children().next().html('".$simboloMoneda.number_format($descuento_item_total,2)."');
                    $('.tabla-totales .totales .op-gravadas').children().next().html('".$simboloMoneda.$op_gravadas."');
                    $('.tabla-totales .totales .op-exoneradas').children().next().html('".$simboloMoneda.$op_exoneradas."');
                    $('.tabla-totales .totales .op-inafectas').children().next().html('".$simboloMoneda.$op_inafectas."');
                    $('.tabla-totales .totales .op-gratuitas').children().next().html('".$simboloMoneda.$op_gratuitas."');
                    $('.tabla-totales .totales .op-descuento').children().next().html('".$simboloMoneda.$descuentoGlobal."');
                    $('.tabla-totales .totales .icbper').children().next().html('".$simboloMoneda.$total_icbper."');
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
	$emisor = ControladorEmpresa::ctrEmisor();
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
			$igv_porcentaje=0.18;	
			$total_icbper = 0.0;
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

				if($v['codigoafectacion']=='10'){
				
					$valor_total = $v['valor_unitario'] * $v['cantidad']- $v['descuento_item'];

					$igv_detalle = $v['igv'];
					$igv_opi =  $v['igv'];
					$importe_total = ($v['valor_unitario'] * $v['cantidad'] ) - $v['descuento_item'] + $igv_detalle;
					
				
					$monto_base  = ($v['valor_unitario'] * $v['cantidad']);
					$valor_unitario = ($v['valor_unitario']);
					
					$factor = ($v['descuento_item'] * 100 / $monto_base) /100;
						$precio_unitario2 = $v['precio_unitario'] * $factor;
						$precio_unitario = $v['precio_unitario'] - $precio_unitario2;
				}

				if($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16'){

					$valor_total = $v['valor_unitario'] * $v['cantidad'];
					$igv_detalle =  $valor_total * 0.18;
					$igv_opi =  0.00;
				
					$importe_total = ($v['valor_unitario'] * $v['cantidad']);	
				
					$monto_base = ($v['valor_unitario'] * $v['cantidad']);
					$valor_unitario = 0;
					$tipo_precio = '02';
					
						$precio_unitario = $v['valor_unitario'];
				}
				if($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36'){

					$valor_total = $v['valor_unitario'] * $v['cantidad'];
					$igv_detalle =  0.00;
					$igv_opi =  0.00;
				
					$importe_total = ($v['valor_unitario'] * $v['cantidad']);	
				
					$monto_base = ($v['valor_unitario'] * $v['cantidad']);
					$valor_unitario = 0;
					$tipo_precio = '02';
					
						$precio_unitario = $v['valor_unitario'];
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
				
						$op_gravadas += ($v['valor_unitario'] * $v['cantidad']) - $v['descuento_item'];
						
					}
					if($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16'){
						
						$op_gratuitas_gravadas += $v['valor_unitario'] * $v['cantidad'];
					
						$igv_op_g =  $op_gratuitas_gravadas * 0.18;	
						
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
	    $opg = $op_gravadas * $desc_porcentaje;
		$op_desc = $op_gravadas * $desc_porcentaje;		
		$op_gravadas =  $op_gravadas - $opg;	
		$op_exoneradas = $op_exoneradas;		
		$op_inafectas = $op_inafectas;
		 $igv = $op_gravadas * 0.18;		
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
			$comprobante =	array(
					'tipodoc'		=> $seriex['tipocomp'],
					'idserie'		=> $doc['idSerie'],
					'serie'			=> $seriex['serie'],
					'correlativo'	=> $seriex['correlativo']+1,
					'fecha_emision' =>date('Y-m-d'),
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
					'icbper' => round($total_icbper,2)
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
				$valor = null;
				$actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);

				if($codigosSunat['feestado'] == 2 && !empty($codigosSunat['feestado'])){
					$valor = $idventa;
					$actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);

					echo "<script>
					Swal.fire({
						title: 'Rechazado por SUNAT',
						text: '¡OJO!',
						icon: 'error',
						html: `Ocurrio un error con código: {$codigosSunat['fecodigoerror']} <br/> Msje: {$codigosSunat['femensajesunat']}<br/>
						<h3>Corrija y emita otro comprobante.</h3>
						<div class='alert alert-success' idVenta='{$idventa}'>SU STOCK HA SIDO NOMALIZADO</div>
						`,			
						showCancelButton: true,
						showConfirmButton: false,
						allowOutsideClick: false,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						cancelButtonText: 'Cerrar',
					})
					</script>";
				}
			
			echo "
				   <div class='contenedor-print'>
				  <form id='printC' name='printC' method='post' action='vistas/print/printer/' target='_blank'>
				 <input type='radio' id='a4' name='a4' value='A4'>
				 <input type='radio' id='tk' name='a4' value='TK'>
				 <input type='hidden' id='idCo' name='idCo' value='".$venta['id']."'>
				  <button  id='printA4' ></button>
				  <button id='printT'></button>
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

public static function ctrComprobantesNoEnviados(){
	$respuesta = ModeloVentas::mdlComprobantesNoEnviados();
	return $respuesta;
}

}