<?php
namespace Controladores;
use Modelos\ModeloResumenDiario;
use api\GeneradorXML;
use api\ApiFacturacion;
class ControladorResumenDiario{
    
    // MUESTRA TODOS LOS COMPROBANTES QUE SE ENVIARÁN POR RESÚMEN DIARIO
    public static function ctrMostrarComprobantes($fecha){
        $tipocomp = '03';
        $estado = '';
        $respuesta = ModeloResumenDiario::mdlMostrarComprobantes($fecha, $tipocomp, $estado);
        
        foreach($respuesta as $k => $v){
           
            echo "<tr class='t-md'>
            <td>".date_format(date_create($v['fecha_emision']), 'd-m-Y')."</td>
            <td>".$v['tipocomp']."</td>
            <td>".$v['serie']."</td>
            <td>".$v['correlativo']."</td>
            <td>".$v['total']."</td>
            
            </tr>";
        
    }
}
    // ENVÍA EL RESÚMEN DIARIO A SUNAT
    public static function ctrEnviarResumenDiario($fecha){
        $tipocomp = '03';
        $estado = '';
        $respuesta = ModeloResumenDiario::mdlMostrarComprobantes($fecha, $tipocomp, $estado);

        if($respuesta == 'error' || $respuesta == null){
        echo "<script>
        Swal.fire({
			icon: 'warning',
			title: 'Oops...',
			text: '¡No hay comprobantes!'
			//footer: '<a href>Why do I have this issue?</a>'
		  })
            </script>";
        }else{

        $emisor = ControladorEmpresa::ctrEmisor();
     
        $serie = date('Ymd');
        $item = 'tipocomp';
        $valor = 'RC';
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
            "tipodoc"		=>"RC",
            "serie"			=>$serie,
            "correlativo"	=>$correlativo,
            "fecha_emision" =>$fecha,			
            "fecha_envio"	=>date('Y-m-d'),
            'idemisor'    => $emisor['id'],
            'resumen' => 1,	
            'baja' =>	''
    );

        $items = array();
       foreach($respuesta as $k =>$v){

            if($v['op_gravadas'] > 0){
                $tipo_total = '01';
                $codigo_afectacion = "1000";
                $nombre_afectacion = "IGV";
                $tipo_afectacion = "VAT";
            }
            if($v['op_gravadas'] > 0 && $v['op_inafectas'] > 0 && $v['op_exoneradas'] > 0){
                $tipo_total = '01';
                $codigo_afectacion = "1000";
                $nombre_afectacion = "IGV";
                $tipo_afectacion = "VAT";
            }
            if($v['op_gravadas'] == 0 && $v['op_inafectas'] == 0 && $v['op_exoneradas'] > 0){
                
                $tipo_total = '02';
                $codigo_afectacion = "9997";
                $nombre_afectacion = "EXO";
                $tipo_afectacion = "VAT";
            }
            if($v['op_gravadas'] == 0 && $v['op_exoneradas'] == 0 && $v['op_inafectas'] > 0){
                
                $tipo_total = '03';
                $codigo_afectacion = "9998";
                $nombre_afectacion = "INA";
                $tipo_afectacion = "FRE";
            }
            $item = 'id';
            $valor = $v['codcliente'];
            $cliente = ControladorClientes::ctrMostrarClientes($item, $valor);
           $detalles = array(
               "item" => ++$k,
               "tipodoc" => $v['tipocomp'],
               "serie" => $v['serie'],
               "correlativo" => $v['correlativo'],
               "condicion" => 1,
               "moneda" => $v['codmoneda'],
               "importe_total" => $v['total'],
               "op_gravadas" => $v['op_gravadas'],
               "op_exoneradas" => $v['op_exoneradas'],
               "op_inafectas" => $v['op_inafectas'],
               "op_gratuitas" => $v['op_gratuitas'],
               "igv_total" => $v['igv'],
               "tipo_total"		=> $tipo_total, //GRA->01, EXO->02, INA->03
               "codigo_afectacion"	=> $codigo_afectacion,
			   "nombre_afectacion"	=> $nombre_afectacion,
			   "tipo_afectacion"	=> $tipo_afectacion,
               "id" => $v['id'],
               "coddoc" => $v['tipodoc'],
               "numdoc" => $cliente['documento']
           );

           $detalles;
           $items[] = $detalles;
           
       }
       
     	// RUTAS DE CDR Y XML 
        $ruta_archivo_xml = "../api/xml/";
        $ruta_archivo_cdr = "../api/cdr/";
        $ruta = "../api/xml/";
        
    
       $generadoXML = new GeneradorXML();
       $api = new ApiFacturacion();

       $nombrexml = $emisor['ruc'].'-'.$cabecera['tipodoc'].'-'.$cabecera['serie'].'-'.$cabecera['correlativo'];

       $generadoXML->CrearXMLResumenDocumentos($emisor, $cabecera, $items, $ruta.$nombrexml);

       $ticket = $api->EnviarResumenComprobantes($emisor,$nombrexml, $ruta_archivo_xml, "../");

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

               
                
               $insertarResumen = ModeloResumenDiario::mdlInsertarResumen($cabecera, $codigosSunat);

               $obtenerUltimoId = ModeloResumenDiario::mdlObtenerUltimoResumenId();

               $idEnvio = $obtenerUltimoId['idenvio'];

               $insertarDetallesResumenBaja = ModeloResumenDiario::mdlInsertarDetallesResumen($idEnvio, $items);

              // $idVenta = $idComprobante;
               $datos= array(
                   'resumen' => 's',
                   'idbaja'  => $idEnvio
           );
               $actualizarVentaAnulado = ModeloResumenDiario::mdlActualizarVentaResumen($items, $datos);
              
                }

                   echo 'Envío realizado';
   
               }
            
            }
        }
        // MUESTRA TODOS LOS RESÚMENES DIARIOS HECHOS QUE SE CARGARON CON AJAX
        public static function ctrMostrarResumenes(){

            $respuesta = ModeloResumenDiario:: mdlMostrarResumenes();
            echo $respuesta;
            
        }
        
// MÉTODO QUE CARGA LAS BOLETAS DE LOS RESUMENES DIARIOS
        public static function ctrMostrarBoletasResumenenes(){
            
            $respuesta = ModeloResumenDiario::mdlMostrarBoletasResumenes();
            echo $respuesta;
          
           
        }
        }