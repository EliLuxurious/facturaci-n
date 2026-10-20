<?php
namespace Controladores;
use Modelos\ModeloEnvioSunat;
use Modelos\ModeloVentas;
use Modelos\ModeloProductos;
use Modelos\ModeloClientes;
use api\GeneradorXML;
use api\ApiFacturacion;

class ControladorGuiaRemision{

    public static function GuardarGuia(){

        $guia = array(
            'serie' => 'T001',
            'correlativo' => 1,
            'fechaEmision' => date('Y-m-d'),
            'horaEmision' => date('H:i:s'),
            'tipoDoc' => '09',
            'observacion' => ''            
        );

        $docbaja = array(
            'nroDoc' => '',
            'tipoDoc' => ''
        );

        
    }
}