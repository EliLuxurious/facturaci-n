
// ESCOGE  LA FECHA DEL RESÚMEN DIARIO
$("#fechaResumen").on("change", function (){
    let fechaResumen = $(this).val();
    let datos = {"fechaResumen":fechaResumen};
    $.ajax({
        method: "POST",
        url: "ajax/resumen-diario.ajax.php",
        data: datos,
        beforeSend: function(){
            // $("body").append(load);
        },
        success: function (result) {
            $(".reloadf").hide();
            $(".resultado-resumen").html(result);
        }
    })
})
// FUNCIÓN PARA ACTUALIZAR LAS BOLETAS ENVIADAS
function resumenBoletas(){
    let fechaResumen = $("#fechaResumen").val();
    let datos = {"fechaResumen":fechaResumen};
    $.ajax({
        method: "POST",
        url: "ajax/resumen-diario.ajax.php",
        data: datos,
        beforeSend: function(){
            // $("body").append(load);
        },
        success: function (result) {
            $(".reloadf").hide();
            $(".resultado-resumen").html(result);
        }
    })
}
// ENVÍA EL RESÚMEN DIARIO A SUNAT
$("#btnEnvioResumen").on("click", function (){
    let fechaResumenEnvio = $("#fechaResumen").val();
    let ruta = $("#ruta_comprobante").val();
    let datos = {"fechaResumenEnvio":fechaResumenEnvio, "ruta":ruta};
    $.ajax({
        method: "POST",
        url: "ajax/resumen-diario.ajax.php",
        data: datos,
        beforeSend: function(){
            $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (respuesta) {
           //(respuesta);
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
              resumenBoletas();
              resumenBoletasDiarios(1);
              loadComrobantesNoEnviados();
        }
    })
})

// CARGA LOS RESÚMENES DIARIOS
function resumenBoletasDiarios(page){
    let searchResumen= $("#searchResumen").val();
    let selectnum= $("#selectnumR").val();
    // let fechaInicial = $('#fechaInicial').val();
    // let fechaFinal = $('#fechaFinal').val();
    let parametros={"action":"ajax","page":page,"searchResumen":searchResumen,"selectnum":selectnum, "rd":"rd"};
    
    $.ajax({
        url: 'vistas/tables/dataTables.php',
        // method: 'GET',
        data: parametros,  
        // cache: false,
        // contentType: false,
        // processData: false, 
        beforeSend: function(){
            $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
        },   
        success:function(data){           
            
            $(".reload-all").fadeOut(50);  

                  $('.body-resumenes').html(data); 
         
            
        }
    })
};
resumenBoletasDiarios(1);

// ENVÍA EL NÚMERO DE RESÚMEN PARA QUE CARGUEN LAS BOLETAS
$(".tablaResumen").on("click","#btnVerBoletas", function (){
    let idenvio = $(this).attr("idenvio");
    let datos = {"idenvio":idenvio};
    $.ajax({
        method: "POST",
        url: "ajax/resumen-diario.ajax.php",
        data: datos,
        beforeSend: function(){

            $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
        },         

        success: function (respuesta) {

            $(".reload-all").fadeOut(50, function () {

          $("#idenvio").val(idenvio);
          $(".text-resumen").html("BOLETAS DEL RESÚMEN N° "+idenvio);
          $("#searchBoleta").val('');
          $('#modalBoletas').modal('show'); // abrir 
          loadResumenBoleta(1)

            });
   
        }
    })
})

// FUNCIÓN QUE CARGA LAS BOLETAS DE LOS RESUMENES DIARIOS
 function loadResumenBoleta(page){
    let idenvio = $("#idenvio").val();
    let searchBoleta = $("#searchBoleta").val();
    let selectnum= $("#selectnum2").val();
    // let fechaInicial = $('#fechaInicial').val();
    // let fechaFinal = $('#fechaFinal').val();
    let parametros = {"idenvio":idenvio, "action":"ajax","page":page,"searchBoleta":searchBoleta,"selectnum":selectnum, "loadBoletas":"loadBoletas"};
    
    $.ajax({
        method: "POST",
        url: 'vistas/tables/dataTables.php',
        data: parametros,
        beforeSend: function(){
            // $("#modalBoletas").append(load);
        },
        success: function (respuesta) {

            $(".reloadf").hide();
                 $(".resultado-ver-boletas").html(respuesta)
            
           
        }
    })
}
