<?php
namespace Controladores;
use Modelos\ModeloEnvioSunat;
use Modelos\ModeloVentas;
use Controladores\ControladorProductos;
use api\GeneradorXML;
use api\ApiFacturacion;
require_once "cantidad_en_letras.php";

class ControladorEnvioSunat{
    
    public static function ctrActualizarVenta($idVenta){
        
        $item = "id";
        $valor = $idVenta;
        $venta = ControladorVentas::ctrMostrarVentas($item, $valor);

        $item = "id_venta";
        $valor = $venta['id'];
        $ventaCredito = ControladorVentas::ctrMostrarVentasCredito($item, $valor);

        $emisor = ControladorEmpresa::ctrEmisor();

        $item = "idventa";
        $valor = $venta['id'];
        $carrito = ControladorVentas::ctrMostrarDetallesProductos($item, $valor);

        $item = "id";
        $valor = $venta['codcliente'];
        $traerCliente = ControladorClientes::ctrMostrarClientes($item, $valor);
        if($venta['tipodoc'] == 1 || $venta['tipodoc'] == 0 || $venta['tipodoc'] == 4 || $venta['tipodoc'] == 7 ){

            $cliente = array(
                'tipodoc'		=> $venta['tipodoc'],//6->ruc, 1-> dni 
                'ruc'			=> $traerCliente['documento'], 
                'razon_social'  => $traerCliente['nombre'], 
                'direccion'		=> $traerCliente['direccion'],
                'pais'			=> 'PE'
                );	
            }
            if($venta['tipodoc'] == 6){
        
                $cliente = array(
                    'tipodoc'		=> $venta['tipodoc'],//6->ruc, 1-> dni 
                    'ruc'			=> $traerCliente['ruc'], 
                    'razon_social'  => $traerCliente['razon_social'], 
                    'direccion'		=> $traerCliente['direccion'],
                    'pais'			=> 'PE'
                    );	
            }
          
            //extract($_REQUEST);
                    $detalle = array();
                    $op_gratuitas_gravadas = 0.00;
                    $op_gratuitas_exoneradas = 0.00;
                    $op_gratuitas_inafectas = 0.0;
                    $igv_opi = 0.00;
                    $nombreMoneda = 'SOLES';
                                     
                    foreach ($carrito as $k => $v){   
                        if($venta['codmoneda'] == "USD"){
                       
                            $nombreMoneda = 'DÓLARES';
                        }                 
                                
                        $item = "codigo";
                        $valor = $v['codigo_afectacion'];
                        $afectacion = ControladorSunat::ctrMostrarTipoAfectacion($item, $valor);

                    $tipo_precio = $v['tipo_precio'];

                        if($v['codigo_afectacion'] == '10'){
                                $igv_opi = $v['igv'] + $v['icbper'];
                        }
                        
                        if($v['codigo_afectacion'] == '11' || $v['codigo_afectacion'] == '12' || $v['codigo_afectacion'] == '13' || $v['codigo_afectacion'] == '14' || $v['codigo_afectacion'] == '15' || $v['codigo_afectacion'] == '16'){

                                $igv_opi = 0.00;
                                $tipo_precio = '02';
                        }

                        if($v['codigo_afectacion'] == '20'){
                                $igv_opi = 0.00;
                        }

                        if($v['codigo_afectacion'] == '30'){
                                $igv_opi = 0.00;
                        }

                        if($v['codigo_afectacion'] == '31' || $v['codigo_afectacion'] == '32' || $v['codigo_afectacion'] == '33' || $v['codigo_afectacion'] == '34' || $v['codigo_afectacion'] == '35' || $v['codigo_afectacion'] == '36'){

                                $igv_opi = 0.00; 
                                $tipo_precio = '02';
                        }

                     
                       $monto_base = $v['valor_unitario'] * $v['cantidad'];
        
                        $itemx = array(
                            'item'				=> ++$k,
                            'codigo'			=> $v['codigo'],
                            'descripcion'		=> $v['descripcion'],
                            'cantidad'			=> round($v['cantidad'],2),
                            'descuentos' 			=> array(
                                            'codigoTipo' 	=> '00',
                                            'montoBase'	=> round($monto_base,2),
                                            'factor' => $v['descuento_factor'],
                                            'monto' => $v['descuento'],
                            ),
                            'valor_unitario'	=> $v['valor_unitario'],
                            'precio_unitario'	=> $v['precio_unitario'],
                            'tipo_precio'		=> $tipo_precio, //ya incluye igv
                            'igv'				=> $v['igv'],
                            'igv_opi'				=> $igv_opi,
                            'porcentaje_igv'	=> 18,
                            'valor_total'		=> $v['valor_total'],
                            'importe_total'		=> $v['importe_total'],
                            'unidad'			=> $v['codunidad'],//unidad,
                            'codigo_afectacion_alt'	=> $afectacion['codigo'],
                            'codigo_afectacion'	=> $afectacion['codigo_afectacion'],
                            'nombre_afectacion'	=> $afectacion['nombre_afectacion'],
                            'tipo_afectacion'	=> $afectacion['tipo_afectacion'],
                            'icbper'            => $v['icbper'],
                            'id'	=> $v['id'],			 
                        );
        
                        $itemx;
        
                        $detalle[] = $itemx;
                    }
                       
                        // var_dump($detalle);
                        // exit(); 
        
                    $item = 'id';
                    $valor = $venta['idserie'];
                    $seriex = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

                    if(!empty($ventaCredito) && $ventaCredito['tipopago'] == 'Credito'){
                        $tipopago = $ventaCredito['tipopago'];
                        $fechapago = $ventaCredito['fecha'];
                        $cuotapago = $ventaCredito['cuota'];
                    }else{
                        $tipopago = 'Contado';
                        $fechapago = '';
                        $cuotapago = '';
                    }
                   
                        $codigo_tipo = "02";
                    
                    $comprobante =	array(
                            'tipodoc'		=> $venta['tipocomp'],
                            'idserie'		=> $venta['idserie'],
                            'serie'			=> $venta['serie'],
                            'correlativo'	=> $venta['correlativo'],
                            // 'fecha_emision' => date('Y-m-d'),
                            'fecha_emision' => $venta['fecha_emision'],
                            'moneda'		=> $venta['codmoneda'], //PEN->SOLES; USD->DOLARES
                            'total_opgravadas'	=> $venta['op_gravadas'],
                            'igv'			=> $venta['igv'],
                            'total_opexoneradas' => $venta['op_exoneradas'],
                            'total_opinafectas'	=> $venta['op_inafectas'],
                            'total_opgratuitas'	=> $venta['op_gratuitas'],
                            'igv_op'			=> $venta['igv_op'],     
                            'codigo_tipo'	=> $codigo_tipo,
                            'monto_base'	=> $venta['subtotal'],
                            'descuento_factor'	=> $venta['descuento_factor'],
                            'descuento'	=> $venta['descuento'],                            
                            'total'			=>$venta['total'],
                            'total_texto'	=> CantidadEnLetra($venta['total'], $nombreMoneda),
                            'codcliente'	=> $venta['codcliente'],					
                            'codigo_doc_cliente' 	=> $cliente['tipodoc'],
                            'bienesSelva' 	=> $venta['bienesSelva'],
                            'serviciosSelva' => $venta['serviciosSelva'],
                            'icbper'     => $venta['icbper'],
                            'tipocambio'	=> $venta['tipocambio'],
                            'tipopago' 	=> $tipopago,
                            'fecha_cuota' 	=> $fechapago,
                            'cuotas' => $cuotapago
                            
                        );
            
                        // INICIO FACTURACIÓN ELECTRÓNICA
				$nombre = $emisor['ruc'].'-'.$comprobante['tipodoc'].'-'.$comprobante['serie'].'-'.$comprobante['correlativo'];

				
					$ruta_archivo_xml = "../api/xml/";
					$ruta_archivo_cdr = "../api/cdr/";
					$ruta = "../api/xml/";
					

                        $generadoXML = new GeneradorXML();
                        $generadoXML->CrearXMLFactura($ruta.$nombre, $emisor, $cliente, $comprobante, $detalle);
    
                    $datos_comprobante = array(
                            'codigocomprobante' => $comprobante['tipodoc'],
                            'serie' 	=> $comprobante['serie'],
                            'correlativo' => $comprobante['correlativo']
                    );
    
                    $api = new ApiFacturacion();
                    $api->EnviarComprobanteElectronico($emisor,$nombre,$ruta_archivo_xml,$ruta_archivo_cdr, "../");

                    $codigosSunat = array(
                        "feestado" => $api->codrespuesta,
                        "fecodigoerror"  => $api->coderror,
                        "femensajesunat"  => $api->mensajeError,
                        "nombrexml"  => $api->xml,
                        "xmlbase64"  => $api->xmlb64,
                        "cdrbase64"  => $api->cdrb64,
                    );
                // FIN FACTURACIÓN ELECTRÓNICA


        $actualizarVenta = ModeloEnvioSunat::mdlActualizarVenta($idVenta,$codigosSunat);
        if($actualizarVenta == 'ok'){
              
            if($codigosSunat['feestado'] == 2 && !empty($codigosSunat['feestado'])){
                $valor = $idVenta;

                $idventa = $idVenta;
                $actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);
                $actualizarVenta = ControladorVentas::ctrActualizarRechazadoVenta($idventa);
				$actualizarDetalles = ControladorVentas::ctrActualizarRechazadoDetalles($idventa);

                echo "<script>
                Swal.fire({
                    title: 'Rechazado por SUNAT',
                    text: '¡OJO!',
                    icon: 'error',
                    html: `Ocurrio un error con código: {$codigosSunat['fecodigoerror']} <br/> Msje: {$codigosSunat['femensajesunat']}<br/>
                    <h3>Corrija y emita otro comprobante.</h3>
                    <div class='alert alert-success' idVenta='{$idVenta}'>SU STOCK HA SIDO NOMALIZADO</div>
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
           
        }
        
    }

    public static function ctrBajaComprobante($idComprobante){

        $emisor = ControladorEmpresa::ctrEmisor();

        $serie = date('Ymd');
        $item = 'tipocomp';
        $valor = 'RA';
        $fila_serie = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

        $correlativo = 1;
        if($fila_serie['serie']!=$serie){
            $datos = array(
                'id' => $fila_serie['id'],
                'serie' => $serie,
            );
            $objCompartido = ControladorSunat::ctrActualizarSerieResumen($datos);
        }else{
            $correlativo = $fila_serie['correlativo']+1;
        }
        
       
        $cabecera = array(
                    "tipodoc"		=>"RA",
                    "serie"			=>$serie,
                    "correlativo"	=>$correlativo,
                    "fecha_emision" =>date('Y-m-d'),			
                    "fecha_envio"	=>date('Y-m-d'),
                    'idemisor'    => $emisor['id'],
                    'resumen' => '',	
                    'baja' => 3,	
            );


        $items = array();

        $id = $idComprobante;
        $factura1 = ModeloVentas::mdlObtenerComprobantesId($id);
        $i=1;
        foreach($factura1 as $k => $factura){
            
            $items[] = array(
                    "item"				=> $i,
                    "idventa"           => $factura['id'],
                    "tipodoc"			=> $factura["tipocomp"],
                    "serie"				=> $factura["serie"],
                    "correlativo"		=> $factura["correlativo"],
                    "motivo"			=> "ERROR EN DOCUMENTO",
                    'resumen'           => '',	
                    'baja'              => 3,
                );
            $i++;
        }
        	// RUTAS DE CDR Y XML 
            $ruta_archivo_xml = "../api/xml/";
            $ruta_archivo_cdr = "../api/cdr/";
            $ruta = "../api/xml/";
            

        $generadoXML = new GeneradorXML();
        $api = new ApiFacturacion();

        $nombrexml = $emisor['ruc'].'-'.$cabecera['tipodoc'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

        $generadoXML->CrearXmlBajaDocumentos($emisor, $cabecera, $items, $ruta.$nombrexml);

        $ticket = $api->EnviarResumenComprobantes($emisor,$nombrexml,$ruta_archivo_xml,"../");

        $datos_comprobante = array(
            'codigocomprobante' => $cabecera['tipodoc'],
            'serie' 	=> $cabecera['serie'],
            'correlativo' => $cabecera['correlativo']
    );
        $api->ConsultarTicket($emisor, $cabecera, $nombrexml, $ticket, $ruta_archivo_xml, $ruta_archivo_cdr, $datos_comprobante);

               
        
				$codigosSunat = array(
					"feestado" => $api->codrespuesta,
					"fecodigoerror"  => $api->coderror,
					"femensajesunat"  => $api->mensajeError,
					"nombrexml"  => $api->xml,
					"xmlbase64"  => $api->xmlb64,
					"cdrbase64"  => $api->cdrb64,
                    "ticket"  => $api->ticketS
				);       
                
                if($codigosSunat['feestado']== '3'){
                    echo "Vuelve a enviarlo";                    

                }else{
                if($codigosSunat['feestado']=='1'){
                    
                    $datos = array(
                        'id' => $fila_serie['id'],
                        'correlativo' 	=> $correlativo,
                    );
                    $objCompartido = ControladorSunat::ctrActualizarCorrelativo($datos);

                
                 
                $insertarResumenBaja = ModeloEnvioSunat::mdlInsertarResumenBaja($cabecera, $codigosSunat);

                $obtenerUltimoId = ModeloEnvioSunat::mdlObtenerUltimoResumenBajaId();

                $idEnvioBaja = $obtenerUltimoId['idenvio'];

                $insertarDetallesResumenBaja = ModeloEnvioSunat::mdlInsertarDetallesResumenBaja($idEnvioBaja, $items);

                $idVenta = $idComprobante;
                $datos= array(
                    'anulado' => 's',
                    'idbaja'  => $idEnvioBaja
            );
                $actualizarVentaAnulado = ModeloEnvioSunat::mdlActualizarVentaBaja($idVenta, $datos);

                $item = 'idventa';
                $valor = $idVenta;
                $respuestaDetalles = ControladorVentas::ctrMostrarDetalles($item, $valor);
                $detalle = array();
                foreach ($respuestaDetalles as $k => $value){
                $detalle[] = array(
                    'id' => $value['idproducto'],
                    'cantidad'     => $value['cantidad']
                );
            }
                $valor = $idVenta;
				$actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);
               
                 }

                    echo 'ENVÍO REALIZADO';
    
                }
    }
    public static function ctrMostrarBajas($item, $valor){
        $tabla = 'envio_resumen';
        $respuesta = ModeloEnvioSunat::mdlMostrarBajas($tabla, $item, $valor);
        return $respuesta;
    }
    public static function ctrMostrarBaja($item, $valor){
        $tabla = 'envio_resumen';
        $respuesta = ModeloEnvioSunat::mdlMostrarBaja($tabla, $item, $valor);
        return $respuesta;
    }
}