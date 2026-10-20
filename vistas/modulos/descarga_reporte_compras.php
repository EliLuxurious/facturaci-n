<?php
// require_once "../../modelos/conexion.php";
// require_once "../../modelos/clientes.modelo.php";
// require_once "../../controladores/clientes.controlador.php";
// require_once "../../controladores/nota-credito.controlador.php";
// require_once "../../modelos/categorias.modelo.php";
// require_once "../../controladores/categorias.controlador.php";
// require_once "../../controladores/envio-sunat.controlador.php";
// require_once "../../controladores/resumen-diario.controlador.php";
// require_once "../../controladores/reporte-ventas.controlador.php";
// require_once "../../modelos/envio-sunat.modelo.php";
// require_once "../../modelos/resumen-diario.modelo.php";
// require_once "../../modelos/nota-credito.modelo.php";
// require_once "../../modelos/reporte-ventas.modelo.php";
require_once("../../vendor/autoload.php");
use Controladores\ControladorReportes;
$ojReporte = ControladorReportes::ctrDescargaReporteComprasExcel();