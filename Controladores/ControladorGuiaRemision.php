<?php

namespace Controladores;

use Modelos\ModeloGuiaRemision;
use Modelos\ModeloEnvioSunat;
use Modelos\ModeloVentas;
use Modelos\ModeloProductos;
use Modelos\ModeloClientes;
use api\GeneradorXML;
use api\ApiFacturacion;

class ControladorGuiaRemision
{

    public static function ctrMostrar($tabla, $item, $valor)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrar($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrMostrarTraslado($tabla, $item, $valor)
    {
        $respuesta = ModeloGuiaRemision::mdlMostrarTraslado($tabla, $item, $valor);
        return $respuesta;
    }

    public static function ctrMostrarUbigeo($item, $valor)
    {

        $respuesta = ModeloGuiaRemision::mdlMostrarUbigeo($item, $valor);

        return $respuesta;
    }
    public static function ctrMostrarUbigeoSolo($item, $valor)
    {

        $respuesta = ModeloGuiaRemision::mdlMostrarUbigeoSolo($item, $valor);

        return $respuesta;
    }

    public static function ctrBuscarSerieCorrelativo($tabla, $valor)
    {

        $respuesta = ModeloGuiaRemision::mdlBuscarSerieCorrelativo($tabla, $valor);
        return $respuesta;
    }

    // LLENAR CARRITO DE COMPRAS
    public static function ctrLlenarCarritoGuia($carritoG)
    {

        foreach ($carritoG as $k => $v) {


            echo "<tr class='id-eliminar" . $k . "'>";
            echo "<td>" . $v['codigo'] . "</td><td>" . $v['cantidad'] . "</td><td>" . $v['unidad'] . "</td><td>" . $v['descripcion'] . "</td>";

            echo  "</tr>";
        }
    }


    public static function ctrGuardarGuia($datosGuia, $codigosSunat)
    {
        $respuesta = ModeloGuiaRemision::mdlGuardarGuia($datosGuia, $codigosSunat);
        return $respuesta;
    }
    public static function ctrCrearGuia($datosForm)
    {
        //  var_dump($datosForm);
        $emisor = ControladorEmpresa::ctrEmisor();

        $item = 'id';
        $valor = $datosForm['serie'];
        $seriex = ControladorSunat::ctrMostrarCorrelativo($item, $valor);

        if (isset($datosForm))

            $fecha = $_POST['fechaEmision'];
        $fecha2 = str_replace('/', '-', $fecha);
        $fechaEmision = date('Y-m-d', strtotime($fecha2));
        $guia = array(
            'serie' => $seriex['serie'],
            'correlativo' => $seriex['correlativo'] + 1,
            'fechaEmision' => $fechaEmision,
            'horaEmision' => date('H:i:s'),
            'tipoDoc' => '09',
            'observacion' => isset($datosForm['comentario']) ? trim($datosForm['comentario']) : '',
        );


        $docbaja = array(
            'nroDoc' => '',
            'tipoDoc' => ''
        );

        $relDoc = array(
            'nroDoc' => $datosForm['serieCorrelativoReferencial'],
            'tipoDoc' => '01'
        );


        $remitente = array(
            'ruc' => $emisor['ruc'],
            'razonsocial' => $emisor['razon_social'],

        );

        $destinatario = array(
            'tipoDoc' => $datosForm['tipoDoc'],
            'numDoc' => $datosForm['docIdentidad'],
            'nombreRazon' => $datosForm['razon_social']

        );
        $terceros = array(
            'tipoDoc' => '',
            'numDoc' => '',
            'nombreRazon' => ''
        );

        $fecha = $_POST['fechaInicialTraslado'];
        $fecha2 = str_replace('/', '-', $fecha);
        $fechaTraslado = date('Y-m-d', strtotime($fecha2));
        $datosEnvio = array(
            'codTraslado' => $datosForm['motivoTraslado'],
            'descTraslado' => 'VENTA',
            'uniPesoTotal' => 'KGM',
            'pesoTotal' => $datosForm['pesoBruto'],
            'numBultos' => $datosForm['numeroBultos'],
            'indTransbordo' => 'false',
            'modTraslado' => $datosForm['modalidadTraslado'],
            'fechaTraslado' => $fechaTraslado,
            'tipoVehiculo' => isset($datosForm['tipoVehiculo']) ? $datosForm['tipoVehiculo'] : ''

        );
        if ($datosForm['modalidadTraslado'] == '02') {
            $transportista = array(
                'tipoDoc' =>  $datosForm['tipoDocTransporte'],
                'numDoc' => $datosForm['docTransporte'],
                'nombreRazon' => $datosForm['nombreRazon'],
                'apellidosRazon' => $datosForm['apellidosRazon'],
                'placa' => strtoupper($datosForm['placa']),
                'tipoDocChofer' => $datosForm['tipoDocTransporte'],
                'numDocChofer' => $datosForm['docTransporte'],
                'numBreveteChofer' => strtoupper($datosForm['numBrevete']),

            );
        } else {
            $transportista = array(
                'tipoDoc' =>  $datosForm['tipoDocTransporte'],
                'numDoc' => $datosForm['docTransporte'],
                'nombreRazon' => $datosForm['nombreRazon'],
                'apellidosRazon' => '',
                'placa' => '',
                'tipoDocChofer' => $datosForm['tipoDocTransporte'],
                'numDocChofer' => $datosForm['docTransporte'],
                'numBreveteChofer' => ''
            );
        }

        $partida = array(
            'ubigeo' => $datosForm['ubigeoPartida'],
            'direccion' => strtoupper($datosForm['direccionPartida'])
        );
        $llegada = array(
            'ubigeo' => $datosForm['ubigeoLlegada'],
            'direccion' => strtoupper($datosForm['direccionLlegada'])
        );

        $contenedor = array(
            'numContenedor' => isset($datosForm['numeroContenedor']) ? $datosForm['numeroContenedor'] : ''
        );


        $puerto = array(
            'codPuerto' => isset($datosForm['codigoPuerto']) ? $datosForm['codigoPuerto'] : ''
        );


        $datosGuia = array(
            'guia' => $guia,
            'docBaja' =>  $docbaja,
            'relDoc' => $relDoc,
            'remitente' => $remitente,
            'destinatario' => $destinatario,
            'terceros' => $terceros,
            'datosEnvio' => $datosEnvio,
            'transportista' => $transportista,
            'llegada' => $llegada,
            'contenedor' => $contenedor,
            'partida' => $partida,
            'puerto' => $puerto,
            'comp_ref' => $datosForm['serieCorrelativoReferencial'],
            'id_cliente' => $datosForm['idCliente']

        );
        // var_dump($datosGuia);
        if (!isset($_SESSION['carritoG'])) {
            $_SESSION['carritoG'] = array();
        }
        $carritoG = $_SESSION['carritoG'];
        //extract($_REQUEST);
        $detalle = array();
        $carritoG = array_values($carritoG);

        foreach ($carritoG as $k => $v) {
            $itemx = array(
                'index' => ++$k,
                'unidad' => $v['unidad'],
                'cantidad' => $v['cantidad'],
                'descripcion' => $v['descripcion'],
                'codigo' => $v['codigo'],
                'codProdSunat' => '',
                'id_producto' => $v['id'],

            );
            $itemx;

            $detalle[] = $itemx;
        }
        $emisor = ControladorEmpresa::ctrEmisor();

        $nombre = $emisor['ruc'] . '-' . $seriex['tipocomp'] . '-' . $seriex['serie'] . '-' . $guia['correlativo'];

        // RUTAS DE CDR Y XML 
        $ruta_archivo_xml = "../api/xml/";
        $ruta_archivo_cdr = "../api/cdr/";
        $ruta = "../api/xml/";
        if (
            !empty($datosForm['idCliente']) && !empty($datosForm['docIdentidad']) && !empty($datosForm['razon_social'])
            && !empty($datosForm['fechaInicialTraslado']) && !empty($datosForm['pesoBruto']) && !empty($datosForm['numeroBultos'])   && !empty($datosForm['direccionPartida']) && !empty($datosForm['ubigeoPartida']) && !empty($datosForm['direccionLlegada']) && !empty($datosForm['ubigeoLlegada'])
        ) {


            // if (($datosForm['modalidadTraslado'] == '02' && !empty($datosForm['placa'])  && !empty($datosForm['numBrevete'])) || ($datosForm['modalidadTraslado'] == '01' && empty($datosForm['placa']))) {

            if (!empty($detalle)) {


                if ($datosForm['envioSunat'] != 'no') {

                    if ($datosForm['envioSunat'] == 'firmar') {
                        $generadoXML = new GeneradorXML();
                        $generadoXML->CrearXMLGuiaRemision($ruta . $nombre, $datosGuia, $detalle);

                        echo "EL COMPROBANTE HA SIDO FIRMADO";
                    }
                    if ($datosForm['envioSunat'] == 'enviar') {
                        $generadoXML = new GeneradorXML();
                        $generadoXML->CrearXMLGuiaRemision($ruta . $nombre, $datosGuia, $detalle);

                        $api = new ApiFacturacion();
                        $api->EnviarGuiaRemision($emisor, $nombre, $ruta_archivo_xml, $ruta_archivo_cdr, "../");
                        $token = $api->token;
                        $ticket = $api->ticketS;
                        $nombre_archivo = $nombre . '.zip';
                        //CONSULTAR TICKET=============================
                        $obtenerCdr = new ApiFacturacion();
                        $obtenerCdr->ConsultarTicketGuiaRemision($emisor, $ticket, $token, $nombre_archivo, $nombre, $ruta_archivo_cdr);
                        $codigosSunat = array(
                            "feestado" => $obtenerCdr->codrespuesta,
                            "fecodigoerror"  => $obtenerCdr->coderror,
                            "femensajesunat"  => $obtenerCdr->mensajeError,
                            "nombrexml"  => $api->xml,
                            "xmlbase64"  => $obtenerCdr->xmlb64,
                            "cdrbase64"  => $obtenerCdr->cdrb64,
                        );
                    }
                }


                $datos = array(
                    'id' => $datosForm['serie'],
                    'correlativo' => $datosGuia['guia']['correlativo'],
                );

                $actualizarSerie = ControladorSunat::ctrActualizarCorrelativo($datos);
                $guardarGuia = ControladorGuiaRemision::ctrGuardarGuia($datosGuia, $codigosSunat);
                $guiaid = ModeloGuiaRemision::mdlObtenerUltimoComprobanteIdGuia();
                $idGuia = $guiaid['id'];

                $insertarDetalles = ModeloGuiaRemision::mdlInsertarDetallesGuia($idGuia, $detalle);


                if ($guardarGuia == 'ok') {


                    echo "
                       <div class='contenedor-print'>
                      <form id='printC' name='printC' method='post' action='vistas/print/printguia/' target='_blank'>
                     <input type='radio' id='a4' name='a4' value='A4'>
                     <input type='radio' id='tk' name='a4' value='TK'>
                     <input type='hidden' id='idCo' name='idCo' value='" . $idGuia . "'>
                      <button  id='printA4' ></button>
                      </form></div>";


                    echo "<script>
                      
                          $('#formGuia').each(function(){
                            this.reset();	
                            $('.nuevoProducto table #itemsPG').html('');
                        })
                        </script>";
                    unset($_SESSION['carritoG']);
                }
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'SE NECESITA PRODUCTOS O SERVICIOS',
                            text: '',
                            html: `Debes ingresar productos o servicios que se encuentren o van a ir en la FACTURA o BOLETA`
                        })
                            </script>";
            }
            // } else {
            //     echo "<script>
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'CAMPOS OBLIGATORIOS',
            //                 text: 'LLENE TODOS LOS CAMPOS OBLIGATORIOS',
            //                 html: `Debes ingresar todos los campos requeridos (<span style='color:red; font-size: 18px;'>*</span>)`
            //             })
            //                 </script>";
            // }
        } else {

            echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'CAMPOS OBLIGATORIOS',
                            text: '',
                            html: `Debes ingresar todos los campos requeridos (<span style='color:red; font-size: 18px;'>*</span>)`
                        })
                            </script>";
        }
    }
    // MOSTRAR VENTAS DETALLES PRODUCTOS
    public static function ctrMostrarDetallesProductosGuia($item, $valor)
    {

        $respuesta = ModeloGuiaRemision::mdlMostrarDetallesProductosGuia($item, $valor);
        return $respuesta;
    }

    public function ctrListarGuias()
    {
        $respuesta = ModeloGuiaRemision::mdlListarGuias();
        echo $respuesta;
    }
}
