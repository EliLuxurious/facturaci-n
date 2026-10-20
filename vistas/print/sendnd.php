<?php 
session_start();
require_once "../../pdf/html2pdf.class.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once('vendor/autoload.php');
require_once "../../Controladores/cantidad_en_letras.php";

//clases de acceso a datos
require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorNotaDebito;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;
$item = "id";
$valor = $_REQUEST["idCo"];
$nota = ControladorNotaDebito::ctrMostrarNotaDebito($item, $valor);

$item = "id";
$valor = $nota['codcliente'];
$cliente = ControladorClientes::ctrMostrarClientes($item, $valor);

$emisor= ControladorEmpresa::ctrEmisor();

$item = "idnc";
$valor = $nota['id'];
$detalle = ControladorNotaDebito::ctrDetallesNotaDebitoProductos($item, $valor);



$valor = $nota['tipocomp'];
$tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);
//Consultar los datos necesarios para mostrar en el PDF - INICIO
$item = "tipo";
$valor = "D";
$codigo = $nota['codmotivo'];
$motivoNota = ControladorSunat::ctrMostrarTablaParametrica($item, $valor, $codigo);

if(isset($_REQUEST['sendemail'])){
    $sendemail =trim($_REQUEST['sendemail']);
}else{
    $sendemail = $cliente['email'];
}

ob_start();


    require_once("diseno-nota-credito.php");

    $nombrexml = $ruc . '-' . $tipo_documento . '-' . $serie . '-' . $correlativo.'.XML';

    $html = ob_get_clean();
    $html2pdf = new Html2Pdf('P', 'a4', 'fr', true, 'UTF-8', 0);  

$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->setTestTdInOnePage(true);
$html2pdf->writeHTML($html);
header('Content-type: application/pdf');

$doc = $html2pdf->output('notadebito.pdf', 'S');

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
    $mail->setFrom($emisor['correo_ventas'], 'Ventas-'.$emisor['nombre_comercial']);
    
    $mail->addAddress($sendemail);     // Add a recipient
    // $mail->addAddress('ventasisfact@gmail.com');     // Add a recipient
//    $mail->addAddress('ellen@example.com');               // Name is optional
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

    // Attachments
    $mail->AddStringAttachment($doc,'DOC-'.$nota['tipocomp'].'-'.$nombrexml.'.pdf', 'base64', 'application/pdf');         // Add attachments
    if($nota['tipocomp'] == '08'){
              
             $mail->addAttachment('../../api/xml/'.$nombrexml,'DOC-'.$nota['tipocomp'].'-'.$nombrexml.'.xml');     
               
    }    // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->AddEmbeddedImage(dirname(__FILE__)."/../img/logo/logo.png", "my-attach", dirname(__FILE__)."/../img/logo/logo.png");

require_once "templatemailncd.php";
    // Content
    $mail->isHTML(true);
    $mail->MsgHTML($message);                                  // Set email format to HTML
    $mail->Subject = $nombre_comprobante;
    $mail->Body    = $message;//'<h1>Gracias</h1><br> Se le adjunta su comprobante </body></html>';
    $mail->AltBody = $message;//'<img src="https://dge4uaysoh8oy.cloudfront.net/lp/11/images/Animated%20newsletter%20with%20GIF%20banners.gif" >
    //This is the body in plain text for non-HTML mail clients';
    
    $mail->CharSet = 'UTF-8';
    $mail->send();
    echo 'ok';
    
} catch (Exception $e) {
    echo "Mensaje no enviado. Error: {$mail->ErrorInfo}";
    
}
?>