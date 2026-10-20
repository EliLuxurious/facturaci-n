$(document).ready(function(){
    $(".connection").hide();
   
   
// OBTENER EL CORRELATIVO DE LOS COMPROBANTES
$("#serie").on("change", function(){
    let idSerie = $("#serie").val();
    let datos = {"idSerie": idSerie};
    $.ajax({
        url: "ajax/sunat.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){
            $("#correlativo").val(respuesta);
        }
    })
})
// CARGAR EL CORRELATIVO DE LOS COMPROBANTES
function Correlativo(){
    let idSerie = $("#serie").val();
    let datos = {"idSerie": idSerie};
    $.ajax({
        url: "ajax/sunat.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){
            $("#correlativo").val(respuesta);
        }
    })
}
Correlativo();

// OBTENER SERIE CORRELATIVO
$(document).on("keyup", "#serieNumero", function(){
    let serieCorrelativo = $("#serieNumero").val();
    let tipoComprobante = $("#tipoComprobante").val();
    let datos = {"serieCorrelativo": serieCorrelativo, "tipoComprobante": tipoComprobante};
    $.ajax({
        url: "ajax/sunat.ajax.php",
        method: "POST",
        data : datos,
        dataType: "json",
        success: function(respuesta){
            if(respuesta != false && respuesta != null && serieCorrelativo.length > 3){
             
            $(".resultadoSerie").show();
                if(tipoComprobante == '01'){
                $(".resultadoSerie a").html(respuesta['serie_correlativo']+' (FACTURA)');
                $(".resultadoSerie").attr('serieCorrelativo', respuesta['serie_correlativo']);
                }
                if(tipoComprobante == '03'){
                $(".resultadoSerie a").html(respuesta['serie_correlativo']+' (BOLETA DE VENTA)');
                $(".resultadoSerie").attr('serieCorrelativo', respuesta['serie_correlativo']);
                }
           
            }else{
                $(".resultadoSerie").hide();
            }
        }
        
    })
})
})
// COMPROBAR CONEXIÓN
// function Connection(){
//     let conexion = "conexion";
//     let datos = {"conexion": conexion};
//     $.ajax({
//         url: "ajax/sunat.ajax.php",
//         method: "POST",
//         data : datos,
//         success: function(respuesta){
            
//             if(respuesta != 1){
//            $(".connection").html(`<div><i class="fas fa-wifi"></i> NO HAY CONEXIÓN</div>`).fadeIn(500);
//            $(".connection").addClass('connsi');
//             }else{
//             $(".connsi").html(`<div><i class="fas fa-wifi"></i> SE HA RESTAURADO SU CONEXIÓN</div>`).fadeIn(500, function(){
//                 $(this).delay(9000).fadeOut(200);
              
//             });
//             }
//         }
//     })
   
// }
//  Connection();

 $(document).on('click','.btn-printer', function(e){
let dos = 2;

    $.ajax({
        method: 'POST',
        url: 'vistas/print/printPrueba.php',
        success: function(response){
            $('#resultConsulta').html(response);
          }
    })
 })