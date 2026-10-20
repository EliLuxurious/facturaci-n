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

        $item = "idventa";
        $valor = $venta['id'];
        $detalle = ControladorVentas::ctrMostrarDetallesProductos($item, $valor);
       
        $emisor = ControladorEmpresa::ctrEmisor();
        
                        // INICIO FACTURACIÓN ELECTRÓNICA
				$nombre = $emisor['ruc'].'-'.$venta['tipocomp'].'-'.$venta['serie'].'-'.$venta['correlativo'];

				
					$ruta_archivo_xml = "../api/xml/";
					$ruta_archivo_cdr = "../api/cdr/";
					$ruta = "../api/xml/";
				
                    $api = new ApiFacturacion();
                    $api->EnviarComprobanteElectronico($emisor,$nombre,$ruta_archivo_xml,$ruta_archivo_cdr, "../");

                    $codigosSunat = array(
                        "code" => $api->code,
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
                
          if($codigosSunat['code'] >= 2000 && $codigosSunat['code'] <= 3999 && !empty($codigosSunat['code'])){
                    
                $valor = $idVenta;
                $idventa = $idVenta;
                $actualizarStock = ControladorProductos::ctrActualizarStock($detalle, $valor);
                $actualizarVenta = ControladorVentas::ctrActualizarRechazadoVenta($idventa);
				$actualizarDetalles = ControladorVentas::ctrActualizarRechazadoDetalles($idventa);
            	$valor = null;
				$actualizarMasVendido = ControladorProductos::ctrActualizarMasVendidos($detalle, $valor);

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
				}if($codigosSunat['code'] < 2000 && !empty($codigosSunat['code'])){
					echo "<script>
					Swal.fire({
						title: 'Error SUNAT',
						text: '¡OJO!',
						icon: 'warning',
						html: `Ocurrio un error con código: {$codigosSunat['fecodigoerror']} <br/> Msje: {$codigosSunat['femensajesunat']}<br/>
						<h3>Vuelva a enviar el comprobante.</h3>
						<div class='alert alert-success' idVenta='{$idventa}'>ENVÍE DE NUEVO DESDE ADMINISTRAR VENTAS</div>
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
        $item = "id";
        $valor = $idComprobante;
        $venta = ControladorVentas::ctrMostrarVentas($item, $valor);
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
                    "fecha_emision" =>$venta['fecha_emision'],			
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