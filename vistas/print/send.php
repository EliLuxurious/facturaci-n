<?php 
session_start();
require_once "../../pdf/html2pdf.class.php";
date_default_timezone_set('America/Lima');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
//Load Composer's autoloader
require_once('vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//clases de acceso a datos
require_once("../../vendor/autoload.php");
use Conect\Conexion;
use Controladores\ControladorClientes;
use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorCategorias;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorResumenDiario;
use Controladores\ControladorEmpresa;
use Controladores\ControladorSunat;
use Controladores\ControladorUsuarios;
require_once "../../Controladores/cantidad_en_letras.php";


    $item = "id";
    $valor = $_REQUEST['idCo'];
    
    $venta = ControladorVentas::ctrMostrarVentas($item, $valor);
    
    $item = 'idenvio';
    $valor = $venta['idbaja'];
    $ticket = ControladorEnvioSunat::ctrMostrarBaja($item, $valor);
    
    $item = "id";
    $valor = $venta['codcliente'];
    $cliente = ControladorClientes::ctrMostrarClientes($item, $valor);
    
    $emisor= ControladorEmpresa::ctrEmisor();
    
    $item = "idventa";
    $valor = $venta['id'];
    $detalle = ControladorVentas::ctrMostrarDetallesProductos($item, $valor);
        
    $item = "id_venta";
    $valor = $venta['id'];
    $ventaCredito = ControladorVentas::ctrMostrarVentasCredito($item, $valor);

    $valor = $venta['tipocomp'];
    $tipo_comprobante = ControladorSunat::ctrTipoComprobante($valor);

    $item = 'codigo';
    $valor = $venta['metodopago'];
    $metodo_pago = ControladorSunat::ctrMostrarMetodoPago($item,$valor); 
    
    $item = 'id';
    $valor = $_SESSION['id'];
    $vendedor = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
    //Consultar los datos necesarios para mostrar en el PDF - INICIO
  
        $sendemail = $_REQUEST['sendemail'];  
    
    
        ob_start();
    
        require_once("invoiceA4.php");
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', array(210,290), 'fr', true, 'UTF-8', 0);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setTestTdInOnePage(true);
        $html2pdf->writeHTML($html);
        $doc = $html2pdf->output('FACTURA.pdf', 'S');
    $mail = new PHPMailer(true);
    
    $mail = new PHPMailer(true);
    $nombrexml = $serie . '-' . $correlativo;
    try {
        //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->Debugoutput = 'html';
    if($emisor['tipo_envio'] == 'smtp'){
        $mail->isSMTP();                     
        }                       // Send using SMTP
        if($emisor['tipo_envio'] == 'mail'){
        $mail->isMAIL();                     
        }                      // Send using     
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
        $mail->AddStringAttachment($doc,'DOC-'.$venta['tipocomp'].'-'.$nombrexml.'.pdf', 'base64', 'application/pdf');         // Add attachments
        if($venta['tipocomp'] == '01' || $venta['tipocomp'] == '03'){
       
         $mail->addAttachment('../../api/xml/'.$venta['nombrexml'],'DOC-'.$venta['tipocomp'].'-'.$nombrexml.'.xml');     

    }    // Add attachments

        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->AddEmbeddedImage(dirname(__FILE__)."/../img/logo/logo.png", "my-attach", dirname(__FILE__)."/../img/logo/logo.png");
    require_once "templatemail.php";
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
  
