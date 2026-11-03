<?php
// MAD-001 (aditivo): registrar abonos de cuotas
session_start();
require_once "../vendor/autoload.php";

use Controladores\ControladorCuentasCobrar;

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] != 'ok') {
    http_response_code(401);
    echo json_encode(array('ok' => false, 'mensaje' => 'Sesión expirada.'));
    exit;
}

if (isset($_POST['registrarAbono'])) {
    echo json_encode(ControladorCuentasCobrar::ctrRegistrarAbono(
        $_POST['idPagoCredito'] ?? 0,
        $_POST['nroCuota'] ?? 0,
        $_POST['montoAbono'] ?? 0
    ));
}
