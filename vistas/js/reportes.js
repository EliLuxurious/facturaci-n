$(".respuesta-envio-reporte").hide();


$(".comp").change(function(){
    
    loadReportes(1);
    loadReportesCompras(1);

})
$(".ini-calendar-hide").hide();
function loadReportes(page){
    let fechaini = $("#fechaInicial").val();
    let fechafin = $("#fechaFinal").val();
    let tipocomp = $(".comp:checked").val();
    let searchR= $("#searchReportes").val();
    let selectnum= $("#selectnum").val();
    let parametros={"action":"ajax","page":page,"searchR":searchR,"selectnum":selectnum, "reportes":"reportes", "tipocomp":tipocomp, "fechaini":fechaini, "fechafin":fechafin};
    
    $.ajax({
        url: 'vistas/tables/dataTablesReportes.php',
        // method: 'GET',
        data: parametros,   
        beforeSend: function(){
            $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
          },   
          success:function(data){        
            
            $(".reload-all").fadeOut(50);
              $('.body-reporte-ventas').html(data);       
          }
      })
  };
function loadReportesCompras(page){
    let fechaini = $("#fechaInicial").val();
    let fechafin = $("#fechaFinal").val();
    let tipocomp = $(".comp:checked").val();
    let searchRC= $("#searchReportes").val();
    let selectnum= $("#selectnum").val();
    let parametros={"action":"ajax","page":page,"searchRC":searchRC,"selectnum":selectnum, "reportesCompras":"reportesCompras", "tipocomp":tipocomp, "fechaini":fechaini, "fechafin":fechafin};
    
    $.ajax({
        url: 'vistas/tables/dataTablesReportesCompras.php',
        // method: 'GET',
        data: parametros,   
        beforeSend: function(){
            $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
          },   
          success:function(data){        
              
            $(".reload-all").fadeOut(50);
              $('.body-reporte-compras').html(data);       
          }
      })
  };
  $(".calendar-reports").on("click", 'a', function(e){
      e.preventDefault();
     let ini = $(this).attr("ini");     
     let fin = $(this).attr("fin");     
    $(".btn-calendar").html(`<i class="far fa-calendar-alt"></i> Del`+' '+ini+' al '+fin);
    $("#fechaInicial").val(ini); 
    $("#fechaFinal").val(fin);
    let fechaini = $("#fechaInicial").val();
    let fechafin = $("#fechaFinal").val();
    let datos = {"fechaini":fechaini, "fechafin":fechafin};
    
    $.ajax({
        method: "POST",
        url: "vistas/modulos/targets.php",
        data: datos,
        beforeSend: function(){
            $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function(reports){
            $(".reload-all").fadeOut(50);
            $(".widgets-ini").html(reports);
            
        }
    })
})
   function loadReportesInicio(){
    let fechaini = $("#fechaInicial").val();
    let fechafin = $("#fechaFinal").val();
    let datos = {"fechaini":fechaini, "fechafin":fechafin};
    $.ajax({
        method: "POST",
        url: "vistas/modulos/targets.php",
        data: datos,
        beforeSend: function(){
            // $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function(reports){
            // $(".reload-all").fadeOut(50);
            $(".widgets-ini").html(reports);
        }
    })
}

$(".personalizado").on("click",function(){
    $(".ini-calendar-hide").show(500);
})
$(".exit-c").on("click",function(){
    $(".ini-calendar-hide").hide(500);
})

// DESCARGAR EXCEL 
$("#fechaInicial, #fechaFinal").change(function(){
   loadReportesInicio();
    // let fechaInicial = $("#fechaInicial").val();
    // let fechaFinal = $("#fechaFinal").val();
    // let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal};
    // $.ajax({
    //     method: "POST",
    //     url: 'ajax/reportes.ajax.php',
    //     data: datos,
    //     success: function(data){
    //         $(".reporte-ventas-excel a").remove();
    //         $(".reporte-ventas-excel").html(data);
    //     }
    // })
excelReport();
excelReportCompras();
    
})
// DESCARGAR EXCEL 
function excelReport(){
    let fechaInicial = $("#fechaInicial").val();
    let fechaFinal = $("#fechaFinal").val();
    let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal, 'excelVentas': 'excelVentas'};
    $.ajax({
        method: "POST",
        url: 'ajax/reportes.ajax.php',
        data: datos,
        success: function(data){
            $(".reporte-ventas-excel a").remove();
            $(".reporte-ventas-excel").html(data);
        }
    })

    
}
// DESCARGAR EXCEL 
function excelReportCompras(){
    let fechaInicial = $("#fechaInicial").val();
    let fechaFinal = $("#fechaFinal").val();
    let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal, 'excelCompras':'excelCompras'};
    $.ajax({
        method: "POST",
        url: 'ajax/reportes.ajax.php',
        data: datos,
        success: function(data){
            $(".reporte-compras-excel a").remove();
            $(".reporte-compras-excel").html(data);
        }
    })

    
}
excelReport();
excelReportCompras();
loadReportesInicio();
  loadReportes(1);
  loadReportesCompras(1);
 
//   ENVIAR REPORTES AL CORREO===========================
  $(".tablaVentas").on("click", ".senda4", function(e){
    e.preventDefault();
    let tipocomp = $("#tipocomp").val();
   let idCo = $(this).attr("idComp"); 
   
  let datos = {"idCo":idCo, "tipocomp":tipocomp};
  if(tipocomp == "07"){
      var urls =  "vistas/print/sendnc.php";
  }
  else if(tipocomp == "08"){
      var urls =  "vistas/print/sendnd.php";
  }else{
       var urls =  "vistas/print/senda4.php";
  }
  $.ajax({
      method: "POST",
      url: urls,
      data: datos,
      beforeSend: function(){
          $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
      },
      success: function(enviarReport){
        $(".reload-all").fadeOut(50);
          if(enviarReport == 'ok'){
        Swal.fire({
            title: 'El comprobante ha sido enviado corréctamente',
            text: '¡Gracias!',
            icon: 'success',
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar',
        })
    }else{
        Swal.fire({
            title: 'Error al enviar',
            text: '¡Gracias!',
            icon: 'warning',
            html: enviarReport+`<p/>
             <div class="form-group">
            <div class="input-group">
             <span class="input-group-addon"><i class="fas fa-envelope"></i></span>                
            <input type="email" class="form-control" id="sendemail" name="sendemail" placeholder="Ingrese el correo del cliente..." style="border-radius: 0px 15px 15px  0px; font-size:1.2em; height:40px;">           
             </div>              
             <button type="submit" idComp='${idCo}' class="btn btn-primary btn-lg reenvio-reporte" style="margin-top:5px;" onclick="reenviarReporte();"><i class="fas fa-paper-plane fa-lg"></i></button>
             <div class="respuesta-envio"></div>
            </div>`,
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar',
        })
    }
      }
  })
})
//   REENVIAR REPORTES AL CORREO===========================
 function reenviarReporte(){
   let idCo = $(".reenvio-reporte").attr("idComp"); 
   let sendemail = $("#sendemail").val();
   let tipocomp = $("#tipocomp").val();

  let datos = {"idCo":idCo, "sendemail":sendemail};

  if(sendemail == ''){

    $(".respuesta-envio").html("Ingrese un correo");

  }else{

    $(".respuesta-envio").hide();

    if(tipocomp == "07"){
        var urls =  "vistas/print/sendnc.php";
    }
    else if(tipocomp == "08"){
        var urls =  "vistas/print/sendnd.php";
    }else{
         var urls =  "vistas/print/senda4.php";
    }
  $.ajax({
      method: "POST",
      url: urls,
      data: datos,
      beforeSend: function(){
          $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
      },
      success: function(enviarReport){
        $(".reload-all").fadeOut(50);
          if(enviarReport == 'ok'){
        Swal.fire({
            title: 'El comprobante ha sido enviado corréctamente',
            text: '¡Gracias!',
            icon: 'success',
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar',
        })
    }else{
        Swal.fire({
            title: 'Error al enviar',
            text: '¡Gracias!',
            icon: 'error',
            html: enviarReport,
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar',
        })
    }
      }
  })
}
}

// IMPRIMIR EN MODAL
$(".tabla-reportes").on("click",".printA4", function(e) {
    e.preventDefault();
   
    let a4 = $("#a4").val();
    let idCo = $(this).attr('idComp');
    let tipocomp = $("#tipocomp").val();
    let datos = {"a4": a4, "idCo": idCo};
    
    if(tipocomp == "07") {
        let urlc = "vistas/print/print-nc.php";
        $.ajax({
            method: "POST",
            url: urlc,
            data: datos,        
    
            beforeSend: function(){
    
        },
        success: function(respuesta){
            $('.printerhere').html(respuesta);
        }
        })
    }
   else if(tipocomp == "08") {
        let urlc = "vistas/print/print-nd.php";
        $.ajax({
            method: "POST",
            url: urlc,
            data: datos,        
    
            beforeSend: function(){
    
        },
        success: function(respuesta){
            $('.printerhere').html(respuesta);
        }
        })
    }else{
        let urlc = "vistas/print/print.php";
        $.ajax({
            method: "POST",
            url: urlc,
            data: datos,        
    
            beforeSend: function(){
    
        },
        success: function(respuesta){
            $('.printerhere').html(respuesta);
        }
        })
    }
           

})
$(".tabla-reportes").on("click",".printT", function(e) {
    e.preventDefault();
    let tipocomp = $("#tipocomp").val();
    let tk = $("#tk").val();
    let idCo = $(this).attr('idComp');

    let datos = {"tk": tk, "idCo": idCo};

    if(tipocomp == "07") {
        let urlc = "vistas/print/print-nc.php";
        $.ajax({
            method: "POST",
            url: urlc,
            data: datos,        
    
            beforeSend: function(){
    
        },
        success: function(respuesta){
            $('.printerhere').html(respuesta);
        }
        })
        }    
     else if(tipocomp == "08") {
        let urlc = "vistas/print/print-nd.php";
        $.ajax({
            method: "POST",
            url: urlc,
            data: datos,        
    
            beforeSend: function(){
    
        },
        success: function(respuesta){
            $('.printerhere').html(respuesta);
        }
        })
    }
    else{
        let urlc = "vistas/print/print.php";
        $.ajax({
            method: "POST",
            url: urlc,
            data: datos,        
    
            beforeSend: function(){
    
        },
        success: function(respuesta){
            $('.printerhere').html(respuesta);
        }
        })
    }
    

})
// FIN IMPRIMIR EN MODAL
// DASHBOARDS====================================================
$(".calendar-reports").on("click", 'a', function(e){
    e.preventDefault();
   let ini = $(this).attr("ini");     
   let fin = $(this).attr("fin"); 
  $("#fechaInicial").val(ini); 
  $("#fechaFinal").val(fin);
  let fechaini = $("#fechaInicial").val();
  let fechafin = $("#fechaFinal").val();
    datos = {'fechaInicial': ini, 'fechaFinal': fin};
    $.ajax({
        method: "POST",
        url: 'vistas/modulos/dashboard.php',
        data: datos,
        beforeSend: function(){

        },
        success: function(data){
            
            $(".chart-responsive-ventas").html(data);
        }
    })
});
function dashboard(){

    $.ajax({
        method: "POST",
        url: 'vistas/modulos/dashboard.php',
        beforeSend: function(){

        },
        success: function(data){
            
            $(".chart-responsive-ventas").html(data);
        }
    })
};
$("#fechaInicial, #fechaFinal").change(function(){
     let fechaInicial = $("#fechaInicial").val();
     let fechaFinal = $("#fechaFinal").val();
     let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal};
     $.ajax({
         method: "POST",
         url: 'vistas/modulos/dashboard.php',
         data: datos,
         success: function(data){
            $(".chart-responsive-ventas").html(data);
         }
     })
 
     
 })
dashboard();

// FIN DASHBOARDS=====================================
$(document).on('click', '.btn-reporte-pdf',function(){
    let tipocomp = $(".comp:checked").val();
    let fechaInicial = $("#fechaInicial").val();
    let fechaFinal = $("#fechaFinal").val();
   
    let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal, 'tipocomp':tipocomp};
    $.ajax({
        method: "POST",
        url: 'vistas/print/reportes/index.php',
        data: datos,
        success: function(respuesta){
           
            $('.printerhere').html(respuesta);
        }
    })

    
})
$(document).on('click', '.btn-reporte-pdf-compras',function(){
    let tipocomp = $(".comp:checked").val();
    let fechaInicial = $("#fechaInicial").val();
    let fechaFinal = $("#fechaFinal").val();
   
    let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal, 'tipocomp':tipocomp};
    $.ajax({
        method: "POST",
        url: 'vistas/print/reportescompras/index.php',
        data: datos,
        success: function(respuesta){
           
            $('.printerhere').html(respuesta);
        }
    })

    
})
$(document).on('click', '.btn-show-envio',function(){
    Swal.fire({
        title: 'INGRESE EL CORREO ELECTRÓNICO',
        icon: 'info',
        html:
         `<div class="contenedor-reportes-email">
         <input type="email" class="" id="emailReporte">

         <button  class="btn btn-primary btn-enviar-reporte"><i class="far fa-paper-plane fa-lg"></i></button>
         
         <div class="respuesta-envio-reporte"></div>
         </div>
         
         `,
        showCloseButton: false,
        showCancelButton: false,
        focusConfirm: false,
        showConfirmButton : false,
       
        cancelButtonText:
          '<i class="fa fa-thumbs-down"></i>',
        cancelButtonAriaLabel: 'Thumbs down'
      })
    
})
$(document).on('click', '.btn-show-envio-reporte',function(){
    Swal.fire({
        title: 'INGRESE EL CORREO ELECTRÓNICO',
        icon: 'info',
        html:
         `<div class="contenedor-reportes-email">
         <input type="email" class="" id="emailReporte">

         <button  class="btn btn-primary btn-enviar-reporte-compra"><i class="far fa-paper-plane fa-lg"></i></button>
         
         <div class="respuesta-envio-reporte"></div>
         </div>
         
         `,
        showCloseButton: false,
        showCancelButton: false,
        focusConfirm: false,
        showConfirmButton : false,
       
        cancelButtonText:
          '<i class="fa fa-thumbs-down"></i>',
        cancelButtonAriaLabel: 'Thumbs down'
      })
    
})
$(document).on('click', '.btn-enviar-reporte',function(){
    let email = $("#emailReporte").val();
    let tipocomp = $(".comp:checked").val();
    let fechaInicial = $("#fechaInicial").val();
    let fechaFinal = $("#fechaFinal").val();
 
    let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal, 'tipocomp':tipocomp, 'email': email};
    $.ajax({
        method: "POST",
        url: 'vistas/print/reportes/senda4.php',
        data: datos,
        beforeSend: function(){

            $(".respuesta-envio-reporte").css("background", "#fff").fadeIn(50).html("<img src='vistas/img/reload.svg' width='60px'> ");
       
        },
        success: function(respuesta){
            if(respuesta == 'ok'){
            $('.respuesta-envio-reporte').css("background", "#59C345").html('El reporte ha sido enviado al correo: '+email).fadeIn(500, function(){
                $(this).delay(7000).fadeOut(500);
                $("#emailReporte").val('');
            });
            }
            else{
            $('.respuesta-envio-reporte').css("background", "#DC5858").html(respuesta).fadeIn(500, function(){
                $(this).delay(7000).fadeOut(500);
            }); 
            }
        }
    })

    
})
$(document).on('click', '.btn-enviar-reporte-compra',function(){
    let email = $("#emailReporte").val();
    let tipocomp = $(".comp:checked").val();
    let fechaInicial = $("#fechaInicial").val();
    let fechaFinal = $("#fechaFinal").val();
 
    let datos = {'fechaInicial':fechaInicial, 'fechaFinal':fechaFinal, 'tipocomp':tipocomp, 'email': email};
    $.ajax({
        method: "POST",
        url: 'vistas/print/reportescompras/senda4.php',
        data: datos,
        beforeSend: function(){

            $(".respuesta-envio-reporte").css("background", "#fff").fadeIn(50).html("<img src='vistas/img/reload.svg' width='60px'> ");
       
        },
        success: function(respuesta){
            if(respuesta == 'ok'){
            $('.respuesta-envio-reporte').css("background", "#59C345").html('El reporte ha sido enviado al correo: '+email).fadeIn(500, function(){
                $(this).delay(7000).fadeOut(500);
                $("#emailReporte").val('');
            });
            }
            else{
            $('.respuesta-envio-reporte').css("background", "#DC5858").html(respuesta).fadeIn(500, function(){
                $(this).delay(7000).fadeOut(500);
            }); 
            }
        }
    })

    
})

$(document).on('click', '#btnConsultar', function(){
    let idcompro = 2;
    let datos = {'idcompro' : idcompro};
    $.ajax({
        method: "POST",
        url: 'vistas/print/printPrueba.php',
        data: datos,        

        beforeSend: function(){

    },
    success: function(respuesta){
       //(respuesta);
    }
    })
})