<?php
if(isset($_POST['tipo_cambio'])){
$fecha = $_POST['fecha'];
$token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

// Iniciar llamada a API
$curl = curl_init();

curl_setopt_array($curl, array(
   CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha='.$fecha,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer ' . $token
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// Datos listos para usar
$empresa = json_decode($response);
//var_dump($empresa);
$datos = array(
	'compra' => $empresa->compra, 
	'venta' => $empresa->venta, 
	
);
      
	echo json_encode($datos); 
}