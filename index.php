<?php
require_once("vendor/autoload.php");
use Controladores\ControladorPlantilla;

date_default_timezone_set('America/Lima');

$plantilla = new ControladorPlantilla();
$plantilla->ctrPlantilla();



 