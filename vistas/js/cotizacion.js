    
function loadCotizaciones(page){
   var searchCotiza= $("#searchCotiza").val();
   var selectnum= $("#selectnum").val();
   var fechaInicial = $('#fechaInicial').val();
   var fechaFinal = $('#fechaFinal').val();
   var parametros={"action":"ajax","page":page,"searchCotiza":searchCotiza,"selectnum":selectnum,"cotizar":"cotizar","fechaInicial":fechaInicial, "fechaFinal":fechaFinal};
   
   $.ajax({
       url: 'vistas/tables/dataTables-cotizaciones.php',
       // method: 'GET',
       data: parametros,  
       
       beforeSend: function(){
        $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
         },   
         success: function(data){      
             $(".reload-all").hide();
             $('.body-listacotiza').html(data);  
         }
     })
 };
   loadCotizaciones(1);  
 
//   ENVIAR REPORTES AL CORREO===========================
$(".tablaCotizaciones").on("click", ".senda4", function(e){
    e.preventDefault();
  
   let idCo = $(this).attr("idComp"); 
   
  let datos = {"idCo":idCo};
 
       var urls =  "vistas/print/sendCotizacion.php";
  
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
             <button type="submit" idComp='${idCo}' class="btn btn-primary btn-lg reenvio-reporte" style="margin-top:5px;" onclick="reenviarCotizacion();"><i class="fas fa-paper-plane fa-lg"></i></button>
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
 function reenviarCotizacion(){
   let idCo = $(".reenvio-reporte").attr("idComp"); 
   let sendemail = $("#sendemail").val();
   let tipocomp = $("#tipocomp").val();

  let datos = {"idCo":idCo, "sendemail":sendemail};

  if(sendemail == ''){

    $(".respuesta-envio").html("Ingrese un correo");

  }else{

    $(".respuesta-envio").hide();

    
         var urls =  "vistas/print/sendCotizacion.php";
    
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
// CARGA DIRECTA A NOTA DE CRÉDITO DESDE REPORTES 
window.addEventListener("load",function(){
  let numCorrelativo = $("#serieCorrelativo").val();
  ////(numCorrelativo)
 
  let datos = {"numCorrelativo": numCorrelativo, 'numcot': 'numcot'};
  $.ajax({
      url: "ajax/cotizacion.ajax.php",
      method: "POST",
      data : datos,
      success: function(respuesta){
          loadCarrito();

          // AGREGAR CLIENTES Y DESCUENTO A LA NC
          let correlativoSerie =  $("#serieCorrelativo").val();
          // let tipoComprobante = $("#tipoComp").val();
          var rutaCompro = $("#ruta_comprobante").val();
          let datos2 = {"correlativoSerie": correlativoSerie, 'ccot': 'ccot'};
             $.ajax({
              url: "ajax/cotizacion.ajax.php",
              method: "POST",
              data : datos2,
              dataType: "json",
              success: function(respuesta){   

                  if(respuesta['descuento'] > 0){
                  $("#descuentoGlobal").val(respuesta['descuento']);
                  $("#descuentoGlobal").focus();
                  }else{
                  $("#descuentoGlobal").val(0);
                  $("#descuentoGlobal").focus();
                  }

                  if(respuesta['tipodoc'] == 6){
                  $("#idCliente").val(respuesta['idCliente']);
                  $("#docIdentidad").val(respuesta['ruc']);
                  $("#razon_social").val(respuesta['razon_social']);
                  $("#direccion").val(respuesta['direccion']);
                  $("#ubigeo").val(respuesta['ubigeo']);
                  $("#celular").val(respuesta['telefono']);
                  $('#tipoDoc').val(6);
                  }
                  else{
                  $("#idCliente").val(respuesta['idCliente']);
                  $("#docIdentidad").val(respuesta['dni']);
                  $("#razon_social").val(respuesta['nombre']);
                  $("#direccion").val(respuesta['direccion']);
                  $("#ubigeo").val(respuesta['ubigeo']);
                  $("#celular").val(respuesta['telefono']);
                  $('#tipoDoc').val(1);
                  //$('#serie').val(5);
                 
                  }
              
                  // CARGAR DESCUENTO
                  LoadDescuento();
            

                  
              }
          });
      }
  })

})