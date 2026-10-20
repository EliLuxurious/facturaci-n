
$(".tablaVentas").on("click","#getcdr1, #getcdr2, #getcdr3", function(e){
e.preventDefault();
    let idVenta = $(this).attr("idVenta");
   var activo = $('#active').attr("idP");

    let datos = {"idVenta": idVenta};
  
   $.ajax({
       method: "POST",
       url: "ajax/envio-sunat.ajax.php",
       data: datos,
       beforeSend: function(){
        $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
       },
       success: function(respuesta){
        console.log(respuesta);
        Swal.fire({
            title: 'El comprobante ha sido enviado',
            text: '¡Gracias!',
            icon: 'success',
            html:
              '<div id="successCO"></div>',
            showCancelButton: true,
            showConfirmButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar',
          })
          $(".reload-all").fadeOut(50);
          $('#successCO').html(respuesta);
          loadVentas(activo);
          loadComrobantesNoEnviados();
       }

   })
});

$(".tablaVentas").on("click","#bajaDoc", function(e){
  Swal.fire({
    title: '¿Estás seguro de anular el comprobante?',
    text: "¡Verifica todo antes de confirmar!",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, guardar!',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {

  let idComprobante = $(this).attr("idDoc");
  var activo = $('#active').attr("idP");
  let datos ={"idComprobante":idComprobante};

  $.ajax({
    method: "POST",
    url: "ajax/envio-sunat.ajax.php",
    data: datos,
    beforeSend: function(){
      $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
    },
    success: function(respuesta){

     Swal.fire({
         title: 'COMUNICACIÓN DE BAJA',
         text: '¡Gracias!',
         icon: 'success',
         html:
           '<div id="successCO"></div>',
         showCancelButton: true,
         showConfirmButton: false,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         cancelButtonText: 'Cerrar',
       })
       $(".reload-all").fadeOut(50);
       $('#successCO').html(respuesta);
          loadVentas(activo);
    }
  })
}
})
})
