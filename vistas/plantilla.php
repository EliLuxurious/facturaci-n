<?php
session_start();

use Controladores\ControladorEmpresa;

unset($_SESSION['carrito']);
unset($_SESSION['carritoC']);
unset($_SESSION['carritoG']);

if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
  $emisor = ControladorEmpresa::ctrEmisor();
}
$tiem = time();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?php
          $title = (isset($emisor['nombre_comercial'])) ? $emisor['nombre_comercial'] : 'SISTEMA DE FACTURACIÓN';
          echo $title;
          ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" sizes="76x76" href="vistas/img/logo/<?php $logo = (isset($emisor['logo'])) ? $emisor['logo'] : 'logo.png';
                                                                        echo $logo;
                                                                        ?>">
  <!-- Compiled and minified CSS -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="vistas/pack/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link href="vistas/pack/bower_components/toggle/css/bootstrap-toggle.min.css" rel="stylesheet">

  <link rel="stylesheet" href="vistas/pack/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="vistas/pack/bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="vistas/pack/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="vistas/pack/dist/css/skins/_all-skins.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="vistas/pack/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- DATERANGEPICKER -->

  <!-- DATEPICKER -->
  <link rel="stylesheet" href="vistas/pack/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- DASHBOPARD -->
  <link rel="stylesheet" href="vistas/pack/bower_components/morris.js/morris.css">

  <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="vistas/pack/bower_components/fontawesome-free/css/all.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- <link rel="stylesheet" href="vistas/pack/dist/css/skins/_all-skins.min.css"> -->
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="vistas/pack/plugins/iCheck/all.css">
  <!-- Latest compiled and minified CSS -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->


  <link rel="stylesheet" href="vistas/css/<?php $plantilla = (isset($emisor['plantilla'])) ? $emisor['plantilla'] : 'plantilla.css';
                                          echo $plantilla;
                                          ?>" id="css">
  <link rel="stylesheet" href="vistas/css/carrito.css?q=<?php echo $tiem; ?>">
  <link rel="stylesheet" href="vistas/css/form.css?q=<?php echo $tiem; ?>">

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- daterangepicer -->
  <link rel="stylesheet" href="vistas/pack/bower_components/bootstrap-daterangepicker/daterangepicker.css">

  <!-- jQuery 3 -->
  <script src="vistas/pack/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="vistas/pack/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


  <script src="vistas/pack/bower_components/toggle/js/bootstrap-toggle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="vistas/pack/dist/js/adminlte.js"></script>
  <!-- DataTables -->
  <script src="vistas/pack/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="vistas/pack/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="vistas/pack/bower_components/datatables.net-bs/js/dataTables.responsive.min.js"></script>
  <script src="vistas/pack/bower_components/datatables.net-bs/js/responsive.bootstrap.min.js"></script>

  <!-- SlimScroll -->
  <script src="vistas/pack/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="vistas/pack/bower_components/fastclick/lib/fastclick.js"></script>

  <!-- AdminLTE for demo purposes -->
  <!-- <script src="vistas/pack/dist/js/demo.js"></script> -->
  <!-- iCheck 1.0.1 -->
  <script src="vistas/pack/plugins/iCheck/icheck.min.js"></script>

  <!-- sweet alert -->
  <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->
  <script src="vistas/pack/plugins/sweetalert/sweetalert2.js"></script>

  <!-- Compiled and minified JavaScript -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> -->
  <!-- InputMask -->
  <script src="vistas/pack/plugins/input-mask/jquery.inputmask.js"></script>
  <script src="vistas/pack/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="vistas/pack/plugins/input-mask/jquery.inputmask.extensions.js"></script>

  <!-- Latest compiled and minified JavaScript -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->

  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script> -->



  <!-- daterangepicker -->
  <script src="vistas/pack/bower_components/moment/min/moment.min.js"></script>
  <script src="vistas/pack/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- DTEPICKER -->
  <script src="vistas/pack/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="vistas/pack/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js"></script>

  <!-- Morris.js charts -->
  <script src="vistas/pack/bower_components/raphael/raphael.min.js"></script>
  <script src="vistas/pack/bower_components/morris.js/morris.min.js"></script>


  <script src="vistas/pack/bower_components/chart.js/Chart.min.js"></script>
  <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script> -->

</head>

<body class="hold-transition skin-blue sidebar-mini">


  <?php

  if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
    $tiempoSesion = 20 * 60000;
    echo "<script>
       var timeout;
   document.onmousemove = function(){ 
       clearTimeout(timeout); 
       contadorSesion(); //aqui cargamos la funcion de inactividad
   } 
   function salir() {
       window.location.href = 'salir'; //esta función te saca
   }
   function contadorSesion() {
      timeout = setTimeout(function () {
       Swal.fire({
           title: 'Su sesión se cerrará en 10 segundos!',
           html: 'Cerrando sesión... <b></b> milliseconds.',
           timer: 10000,
           timerProgressBar: true,
           didOpen: () => {
             Swal.showLoading()
             const b = Swal.getHtmlContainer().querySelector('b')
             timerInterval = setInterval(() => {
               b.textContent = Swal.getTimerLeft()
             }, 100)
           },
           willClose: () => {
               clearTimeout(timeout); 
           }
         }).then((result) => {
           /* Read more about handling dismissals below */
           if (result.dismiss === Swal.DismissReason.timer) {
             salir();
           }
         })
   
       },$tiempoSesion);//3 segundos para no demorar tanto 
   }
  
       </script>";
    echo '<div class="reload-all" id="reload-all"></div>';
    echo '<div class="flex super-contenedor">';
    /*=============================================
    CABEZOTE
    =============================================*/

    include "modulos/header.php";

    /*=============================================
    MENU
    =============================================*/

    include "modulos/menu.php";

    /*=============================================
    CONTENIDO
    =============================================*/

    if (isset($_GET["ruta"])) {

      if (
        $_GET["ruta"] == "inicio" ||
        $_GET["ruta"] == "usuarios" ||
        $_GET["ruta"] == "categorias" ||
        $_GET["ruta"] == "productos" ||
        $_GET["ruta"] == "clientes" ||
        $_GET["ruta"] == "ventas" ||
        $_GET["ruta"] == "crear-factura" ||
        $_GET["ruta"] == "crear-boleta" ||
        $_GET["ruta"] == "nota-credito" ||
        $_GET["ruta"] == "nota-debito" ||
        $_GET["ruta"] == "crear-nota" ||
        $_GET["ruta"] == "crear-cotizacion" ||
        $_GET["ruta"] == "crear-guia" ||
        $_GET["ruta"] == "nueva-compra" ||
        $_GET["ruta"] == "reportes" ||
        $_GET["ruta"] == "empresa" ||
        $_GET["ruta"] == "resumen-diario" ||
        $_GET["ruta"] == "reporte-ventas" ||
        $_GET["ruta"] == "reporte-compras" ||
        $_GET["ruta"] == "ver-guias" ||
        $_GET["ruta"] == "listar-cotizaciones" ||
        $_GET["ruta"] == "consulta-comprobante" ||
        $_GET["ruta"] == "unidad-medida" ||
        $_GET["ruta"] == "salir"
      ) {

        include "modulos/" . $_GET["ruta"] . ".php";
      } else {

        include "modulos/404.php";
      }
    } else {

      include "modulos/inicio.php";
    }

    /*=============================================
    FOOTER
    =============================================*/


    include "modulos/footer.php";

    echo '</div>';
  } else {

    include "modulos/login.php";
  }
  $tiempo = time();
  ?>


  <div class="connection"></div>
  <input type="hidden" class="" id="tipo_cambio" name="tipo_cambio" value="">
  <input type="hidden" class="" id="fecha" name="fecha" value="<?php echo date("Y-m-d") ?>">
  <!-- End custom js for this page-->
  <script src="vistas/js/plantilla.js"></script>
  <script src="vistas/js/usuarios.js"></script>
  <script src="vistas/js/categorias.js"></script>
  <script src="vistas/js/productos.js"></script>
  <script src="vistas/js/clientes.js"></script>
  <script src="vistas/js/sunat.js"></script>
  <script src="vistas/js/ventas.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/nota-credito.js"></script>
  <script src="vistas/js/nota-debito.js"></script>
  <script src="vistas/js/envio-sunat.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/resumen-diario.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/reportes.js"></script>
  <script src="vistas/js/empresa.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/descuentos.js"></script>
  <script src="vistas/js/compras.js"></script>
  <script src="vistas/js/proveedores.js"></script>
  <script src="vistas/js/guia.js?q=<?php echo $tiempo; ?>"></script>
  <script src="vistas/js/cuotas.js"></script>
  <script src="vistas/js/cotizacion.js"></script>

  <script>
    $(document).ready(function() {
      $(".reload-all").hide();


    })

    function change(a) {
      var css = document.getElementById("css");
      if (a == 1)
        css.setAttribute("href", "vistas/css/plantilla.css");
      if (a == 2)
        css.setAttribute("href", "vistas/css/plantilla2.css");
    }
    loadGuiasR(1)

    $(document).on('click', ".reload-all", function() {
      $(".reload-all").hide();
    })
  </script>

</body>

</html>