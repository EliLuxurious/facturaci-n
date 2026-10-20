<?php 
session_start();
require_once "../../../pdf/html2pdf.class.php";
date_default_timezone_set('America/Lima');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once('../vendor/autoload.php');
require_once("../../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorProveedores;
use Controladores\ControladorProductos;
use Controladores\ControladorReportes;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;

// require_once "../../Controladores/cantidad_en_letras.php";
if(isset($_POST['fechaInicial'])){
$email = $_POST['email'];
$tipocomp = $_POST['tipocomp'];
$fechaini = $_POST["fechaInicial"];
$fechaini2 = str_replace('/', '-', $fechaini);
$fechaInicial = date('Y-m-d', strtotime($fechaini2));
$fechafin = $_POST["fechaFinal"];
$fechafin2 = str_replace('/', '-', $fechafin);
$fechaFinal = date('Y-m-d', strtotime($fechafin2));

    $tabla = 'compra';


$resultado = ControladorReportes::ctrReporteComprasPDF($tabla, $fechaInicial, $fechaFinal, $tipocomp);
// var_dump($resultado);
$emisor = ControladorEmpresa::ctrEmisor();



    ob_start();

    require_once("report.php");
    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->setTestTdInOnePage(true);
    $html2pdf->writeHTML($html);
    $doc = $html2pdf->output('reporte.pdf', 'S');
$mail = new PHPMailer(true);

$mail = new PHPMailer(true);


try {
    //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->Debugoutput = 'html';
    if($emisor['tipo_envio'] == 'smtp'){
    $mail->isSMTP();                     
    }                       // Send using SMTP
    if($emisor['tipo_envio'] == 'mail'){
    $mail->isMAIL();                     
    }                       // Send using SMTP
    $mail->Host       = $emisor['servidor'];                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $emisor['correo_ventas'];                     // SMTP username
    $mail->Password   = $emisor['contrasena'];                               // SMTP password
    $mail->SMTPSecure = $emisor['seguridad'];         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = $emisor['puerto'];                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom($emisor['correo_ventas'], 'Reporte de compras-'.$emisor['nombre_comercial']);
    
    $mail->addAddress($email);     // Add a recipient
    // $mail->addAddress('ventasisfact@gmail.com');     // Add a recipient
//    $mail->addAddress('ellen@example.com');               // Name is optional
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

    // Attachments
    $mail->AddStringAttachment($doc,'reporte-compras-'.date('Y-m-d').'.pdf', 'base64', 'application/pdf');
    $mail->AddEmbeddedImage(dirname(__FILE__)."/../../img/logo/logo.png", "my-attach", dirname(__FILE__)."/../img/logo/logo.png");

require_once "../templateReporteCompras.php";
    // Content
    $mail->isHTML(true);
    $mail->MsgHTML($message);                                  // Set email format to HTML
    $mail->Subject = 'Reporte';
    $mail->Body    = $message;
    $mail->AltBody = $message;
    $mail->CharSet = 'UTF-8';
    $mail->send();
    echo 'ok';
    
} catch (Exception $e) {
    echo "Mensaje no enviado. Error: {$mail->ErrorInfo}";
    
}
}
?>