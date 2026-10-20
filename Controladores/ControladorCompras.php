<?php
namespace Controladores;
use Controladores\ControladorProveedores;
use Controladores\ControladorEmpresa;
use Modelos\ModeloCompras;
use Modelos\ModeloProductos;

class ControladorCompras{

    public static function ctrMostrarCompras($item, $valor){
        $tabla = "compra";
        $respuesta = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);
        return $respuesta;
    }
  
    // MOSTRAR COMPRAS
    public static function ctrMostrarDetallesCompras($item, $valor){
        $tabla = "compra_detalle";
        $respuesta = ModeloCompras::mdlMostrarDetallesCompras($tabla, $item, $valor);
        return $respuesta;
    }


    public static function ctrLlenarCarrito($datosCarrito) {
        $cantidad_agregar = 1;
        if(isset($datosCarrito['cantidad'])){
            $cantidad_agregar = $datosCarrito['cantidad'];
        }
        if(!isset($_SESSION['carritoC'])){
            $_SESSION['carritoC'] = array();
        }
        
        $carritoC = $_SESSION['carritoC'];            
          
        $item = count($carritoC)+1;
        $cantidad = $cantidad_agregar;
        $existe = false;
        foreach ($carritoC as $k => $v) {
            if($v['codigo']==$datosCarrito['codigo']){
                $item = $k;
                $existe = true;
                break;
            }
            
        }
        if(isset($datosCarrito['icbper'])){
            $icbper = $datosCarrito['icbper'];
        }else{
            $icbper = 0;
        }
     
            $carritoC[$item] = array(
                    'id' =>$datosCarrito['idProductoc'],
                    'codigo'=>$datosCarrito['codigo'],
                    'descripcion' =>$datosCarrito['descripcion'],
                    'precio_unitario' =>$datosCarrito['precio_unitario'],
                    'valor_unitario' =>$datosCarrito['valor_unitario'],
                    'cantidad'=>$cantidad,
                    'unidad' => $datosCarrito['unidad'],
                    'tipo_afectacion' =>$datosCarrito['tipo_afectacion'],
                    'igv' =>$datosCarrito['igv'],
                    'descuento_item' =>$datosCarrito['descuento_item'],
                    'icbper' =>$icbper,
                    'subtotal' =>$datosCarrito['subtotal'],
                    'total' =>$datosCarrito['total']
                    );

        $carritoC = array_values($carritoC); 
        $_SESSION['carritoC'] = $carritoC;
        // var_dump($carritoC);
       
        $op_gravadas = 0;
        $op_exoneradas = 0;
        $op_gratuitas = 0;
        $op_inafectas = 0;
        $igv = 0;
        $subtotal = 0;
        $desc_items = 0;
        $descuentoGlobal = 0;
        $icbper = 0;
        foreach ($carritoC as $k => $v){
            // if($datosCarrito['moneda'] == "USD"){
            //     $v['valor_unitario'] = $v['valor_unitario']/ $datosCarrito['tipo_cambio'];
            //     $v['precio_unitario'] = $v['precio_unitario']/ $datosCarrito['tipo_cambio'];
            //     $v['igv'] = $v['igv'] / $datosCarrito['tipo_cambio'];
            //     $v['descuento_item'] = $v['descuento_item'] / $datosCarrito['tipo_cambio'];
            // }
            $precio_unitario = $v['precio_unitario'];
            $valor_unitario = $v['valor_unitario'];
            $subtotal = $valor_unitario * $v['cantidad'] + $v['igv'];
            echo "<tr class='id-eliminar".$k."'>";
				echo "<td>".$v['codigo']."</td><td>".$v['cantidad']."</td><td>".$v['unidad']."</td><td>".$v['descripcion']."</td><td>".round($precio_unitario,2)."</td><td>".round($valor_unitario,2)."</td><td>".round($v['subtotal'],2)."</td><td>".round($v['total'],2)."</td>";
				echo "<td><button type='button' class='btn btn-danger btn-xs btnEliminarItemCarroC' itemEliminar='".$k."'><i class='fas fa-trash-alt'></i></button></td></tr>";


             if($v['tipo_afectacion']=='10'){			
					
                    $op_gravadas += $v['subtotal'];					
                                    
            }
            if($v['tipo_afectacion'] == '11' || $v['tipo_afectacion'] == '12' || $v['tipo_afectacion'] == '13' || $v['tipo_afectacion'] == '14' || $v['tipo_afectacion'] == '15' || $v['tipo_afectacion'] == '16'){
        
                $op_gratuitas += $v['subtotal'];

            }
            if($v['tipo_afectacion'] == '31' || $v['tipo_afectacion'] == '32' || $v['tipo_afectacion'] == '33' || $v['tipo_afectacion'] == '34' || $v['tipo_afectacion'] == '35' || $v['tipo_afectacion'] == '36'){
        
                $op_gratuitas += $v['subtotal'];

            }

            if($v['tipo_afectacion']=='20'){
                $op_exoneradas += $v['subtotal'];
                
            }

            if($v['tipo_afectacion']=='30'){
                $op_inafectas += $v['subtotal'];
                
            }	
            $igv +=  $v['igv'];	
            $desc_items += $v['descuento_item'];

     
            $icbper += $v['icbper'];
            
            
        }
            $descuentototal = $descuentoGlobal + $desc_items;
            $subTotal = $op_gravadas + $op_exoneradas + $op_inafectas;
            $total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv + $icbper;
        echo "<script>
                $('#op_gravadas').val('".round($op_gravadas,2)."');
                $('#op_exoneradas').val('".round($op_exoneradas,2)."');
                $('#op_inafectas').val('".round($op_inafectas,2)."');
                $('#op_gratuitas').val('".round($op_gratuitas)."');
                $('#subtotalc').val('".round($subTotal,2)."');
                $('#descuento').val('".round($descuentototal,2)."');
                $('#icbper').val('".round($icbper,2)."');
                $('#igvc').val('".round($igv,2)."');
                $('#totalc').val('".round($total,2)."');

            </script>";

    }

public static function ctrLoadCarro($descuentoGlobal){
    $emisorigv = new ControladorEmpresa();
    $emisorigv->ctrEmisorIgv();
    $op_gravadas = 0;
    $op_exoneradas = 0;
    $op_gratuitas = 0;
    $op_inafectas = 0;
    $igv = 0;
    $subTotal = 0;
    $desc_items = 0;
    $icbper = 0;

    $carritoC = $_SESSION['carritoC'];
    foreach ($carritoC as $k => $v){
        $precio_unitario = $v['precio_unitario'];
        $valor_unitario = $v['valor_unitario'];
        // $subtotal = $valor_unitario * $v['cantidad'] + $v['igv'];
        echo "<tr class='id-eliminar".$k."'>";
            echo "<td>".$v['codigo']."</td><td>".$v['cantidad']."</td><td>".$v['unidad']."</td><td>".$v['descripcion']."</td><td>".round($precio_unitario,2)."</td><td>".round($valor_unitario,2)."</td><td>".round($v['subtotal'],2)."</td><td>".round($v['total'],2)."</td>";
            echo "<td><button type='button' class='btn btn-danger btn-xs btnEliminarItemCarroC' itemEliminar='".$k."'><i class='fas fa-trash-alt'></i></button></td></tr>";


            if($v['tipo_afectacion']=='10'){			
					
                $op_gravadas += $v['subtotal'];	
                			
                                
        }
        if($v['tipo_afectacion'] == '11' || $v['tipo_afectacion'] == '12' || $v['tipo_afectacion'] == '13' || $v['tipo_afectacion'] == '14' || $v['tipo_afectacion'] == '15' || $v['tipo_afectacion'] == '16'){
    
            $op_gratuitas += $v['subtotal'];

        }
        if($v['tipo_afectacion'] == '31' || $v['tipo_afectacion'] == '32' || $v['tipo_afectacion'] == '33' || $v['tipo_afectacion'] == '34' || $v['tipo_afectacion'] == '35' || $v['tipo_afectacion'] == '36'){
    
            $op_gratuitas += $v['subtotal'];

        }

        if($v['tipo_afectacion']=='20'){
            $op_exoneradas += $v['subtotal'];
            
        }

        if($v['tipo_afectacion']=='30'){
            $op_inafectas += $v['subtotal'];
            
        }
       
       
        $igv +=  $v['igv'];	
        $desc_items += $v['descuento_item'];
        $icbper += $v['icbper'];
        
    }
        $subTotal = $op_gravadas + $op_exoneradas + $op_inafectas;  
         $op_gravadas = $op_gravadas - $descuentoGlobal;
        $descuentototal = $descuentoGlobal + $desc_items;
         if($descuentoGlobal > 0){	
         $igv = $op_gravadas * $emisorigv->igv_dos;	
         }
        $total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv + $icbper;
    echo "<script>
            $('#op_gravadas').val('".round($op_gravadas,2)."');
            $('#op_exoneradas').val('".round($op_exoneradas,2)."');
            $('#op_inafectas').val('".round($op_inafectas,2)."');
            $('#op_gratuitas').val('".round($op_gratuitas)."');
            $('#subtotalc').val('".round($subTotal,2)."');
            $('#descuento').val('".round($descuentototal,2)."');
            $('#icbper').val('".round($icbper,2)."');
            $('#igvc').val('".round($igv,2)."');
            $('#totalc').val('".round($total,2)."');

        </script>";
    
}
    public static function ctrGuardarCompra($datos) {
     if($datos['tipoComprobante'] != '' && $datos['serieDoc'] != '' && $datos['correlativoDoc'] != '' && is_numeric($datos['correlativoDoc'])){

    if($datos['docIdentidad'] != '' && strlen($datos['docIdentidad']) >= 8) {

        if($datos['idProveedor'] == '' ){        
        $proveedor = ControladorProveedores::ctrGuardarProveedor($datos);
        if($datos['tipoDoc'] == 1 || $datos['tipoDoc'] == 0 || $datos['tipoDoc'] == 4 || $datos['tipoDoc'] == 7 ){
			$item = 'documento';
		}else{
			$item = 'ruc';
		}
		
		$valor = $datos['docIdentidad'];
		$proveedorExiste = ControladorProveedores::ctrMostrarProveedores($item, $valor);
		$idProveedor =  $proveedorExiste['id'];
	}else{
		$idProveedor = $datos['idProveedor'];
	}
          


        $carritoC = $_SESSION['carritoC'];
        // var_dump($carritoC);
        $detalle = array();

        foreach($carritoC as $k => $v){
            $k++;
            $itemx = array(
                'item' => $k,
                'codigo' =>$v['codigo'],
                'descripcion' =>$v['descripcion'],
                'precio_unitario' =>$v['precio_unitario'],
                'valor_unitario' =>$v['valor_unitario'],
                'cantidad' =>$v['cantidad'],
                'igv' =>$v['igv'],
                'descuento' =>$v['descuento_item'],
                'icbper' =>$v['icbper'],
                'valor_total' =>$v['subtotal'],
                'importe_total' =>$v['total'],
                'codigo_afectacion' =>$v['tipo_afectacion'],
                'id'  => $v['id']
            );
            $itemx;
            $detalle[] = $itemx;
        }
        // var_dump($detalle);
        $tipocomp_ref = '';
        $serie_ref = '';
        $correlativo_ref = '';        
        $codmotivo = ''; 
        $fechamodificado = '';
        if($datos['tipoComprobante'] == '07' || $datos['tipoComprobante'] == '08'){
            $tipocomp_ref = $datos['compModificar'];
            $serie_ref = $datos['serieModificar'];
            $correlativo_ref = $datos['correlativoModificar'];
            $codmotivo = $datos['motivoModificar'];
            $fechamodificado = $datos['fechaModificar'];

        }
        $comprobante = array(
            'serie' =>strtoupper($datos['serieDoc']),
            'correlativo' =>$datos['correlativoDoc'],
            'serie_correlativo' =>strtoupper($datos['serieDoc'].'-'.$datos['correlativoDoc']),
            'op_gravadas' =>$datos['op_gravadas'],
            'op_exoneradas' =>$datos['op_exoneradas'], 
            'op_inafectas' =>$datos['op_inafectas'],
            'op_gratuitas' =>$datos['op_gratuitas'],
            'descuento' =>$datos['descuento'],
            'igv' =>$datos['igvc'],
            'icbper' =>$datos['icbper'],
            'subtotal' =>$datos['subtotalc'],
            'total' =>$datos['totalc'],
            'codproveedor' =>$idProveedor,
            'tipodoc' =>$datos['tipoDoc'],
            'tipocomp' =>$datos['tipoComprobante'],
            'moneda' =>$datos['moneda'],
            'fechadoc' =>$datos['fechaDoc'],
            'tipocomp_ref' =>$datos['compModificar'],
            'serie_ref' =>strtoupper($datos['serieModificar']),
            'correlativo_ref' =>$datos['correlativoModificar'],
            'codmotivo' =>$datos['motivoModificar'],
            'fechamodificado' =>$datos['fechaModificar'],
            'metodopago' =>$datos['metodopago']
        );

        // var_dump($datosTotales);
        $insertarCompra = ModeloCompras::mdlInsertarCompra($comprobante);
			
			$compra = ModeloCompras::mdlObtenerUltimoComprobanteId();
            $idcompra = $compra['id'];
            $insertarDetalles = ModeloCompras::mdlInsertarDetalles($idcompra, $detalle);

            if($insertarCompra == "ok"){
                $valor = $idcompra;
                $actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);
                echo "<script>
		Swal.fire({
			icon: 'success',
			title: 'OK...',
			text: 'COMPRA ALMACENADA CORRÉCTAMENTE'
			//footer: '<a href>Why do I have this issue?</a>'
		  })
          $('.nuevoProductoC table #itemsP').html('');
          $('#formCompra .totales input').val(0.00);
				  $('#descuentoGlobalC').val(0);
				  $('#docIdentidad').val('');
				  $('#razon_social').val('');
				  $('#comentario').val('');
				  $('#direccion, #ubigeo, #celular').val('');
				  $('#serieDoc, #correlativoDoc').val('');
                  $('#rucActivo').hide();

			</script>";
            unset($carritoC);
            $_SESSION['carritoC']=$carritoC;
            }else{
              
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '¡Error al ingresar la compra!'
                    //footer: '<a href>Why do I have this issue?</a>'
                })
                    </script>";  
            }
}else{
    
    echo "<script>
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: '¡Debes ingresar el número de R.U.C. o D.N.I!'
			//footer: '<a href>Why do I have this issue?</a>'
		  })
			</script>";
}
     }else{
         echo "<script>
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: '¡Seleccione el tipo de comprobante, llene la serie y correlativo!'
			//footer: '<a href>Why do I have this issue?</a>'
		  })
			</script>";
     }
    }

    public static function ctrAnularCompra($idcompra){
        $anulado = 's';
        $respuesta = ModeloCompras::mdlAnularCompra($idcompra, $anulado);
        return $respuesta;
    }

    public static function ctrBucarProducto($valor){ 
        $tabla = "productos";
        $respuesta = ModeloCompras::mdlBuscarProducto($tabla, $valor);
        return $respuesta;
       }
}