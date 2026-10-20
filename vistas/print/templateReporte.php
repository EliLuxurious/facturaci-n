<?php

$background = "background-image: linear-gradient( 180.4deg,  rgba(188,120,236,1) -2.2%, rgba(29,133,163,1) 83.5% );";


$message =    $message  = '<html><body>';
   
$message .= "<table width='100%' bgcolor='#fff' cellpadding='0' cellspacing='0' border='0'>";

$message .= "<tr><td>";

$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:100%; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
 
$message .= "<thead >
   <tr height='80'>
    <th colspan='4' style='background-color:#fff; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#767676; font-size:34px;' ><a href='https://apifacturacion.com'><img src='cid:my-attach' width='150px'></img></a></th>
   </tr>
   <tr height='80'>
    <th colspan='4' style='background-color:#fff;  font-family:Verdana, Geneva, sans-serif; color:#767676; font-size:30px; border-radius:15px;letter-spacing: 2px;' >".$emisor['nombre_comercial']."</th>
   </tr>
    
   </thead>";
 
$message .= "<tbody>
   <tr align='center' height='40' style='font-family:Verdana, Geneva, sans-serif;'>
    <th style='".$background." text-align:center;border-radius:15px 15px 0px 0px;'><a href='https://apifacturacion.com' style='color:#fff; text-decoration:none;font-size:20px; letter-spacing: 2px;'>REPORTE DE VENTAS</a></th>
   </tr>
   
   <tr>
    <td colspan='4' style='padding:15px;background-color:#fff; border: 1px solid #EEEEEE;'>
     <p style='font-size:16px;'>Informamos a usted que el reporte, ya se encuentra disponible.</p>
     
     <hr />
     <p style='font-size:20px; color:#696969;'>En el adjunto se encuentra su reporte.</p>";
    //  <img src='https://4.bp.blogspot.com/-rt_1MYMOzTs/VrXIUlYgaqI/AAAAAAAAAaI/c0zaPtl060I/s1600/Image-Upload-Insert-Update-Delete-PHP-MySQL.png' alt='Sending HTML eMail using PHPMailer in PHP' title='Sending HTML eMail using PHPMailer in PHP' style='height:auto; width:100%; max-width:100%;' />
    //  <p style='font-size:15px; font-family:Verdana, Geneva, sans-serif;'></p>
   $message .= "</td>
   </tr>
   
   </tbody>";
 
$message .= "</table>";

$message .= "</td></tr>";
$message .= "</table>";

$message .= "</body></html>";