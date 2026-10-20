
 // CARGAR DATOS PARA NOTA DE CRÉDITO
// OBTENER SERIE CORRELATIVO
$(".btn-add-serie").on("click", function(e){
    e.preventDefault();
    let numCorrelativo = $('.resultadoSerie').attr("serieCorrelativo");
    ////(numCorrelativo)
     let tipoComprobante = $("#tipoComprobante").val();
    let datos = {"numCorrelativo": numCorrelativo};
    $.ajax({
        url: "ajax/nota-debito.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){
            loadCarrito();

            // AGREGAR CLIENTES Y DESCUENTO A LA NC
            let correlativoSerie = $('.resultadoSerie').attr("serieCorrelativo");
            let tipoComprobante = $("#tipoComprobante").val();
            let datos2 = {"correlativoSerie": correlativoSerie};
               $.ajax({
                url: "ajax/nota-debito.ajax.php",
                method: "POST",
                data : datos2,
                dataType: "json",
                success: function(respuesta){
                    
                    $("#descuentoGlobal").val(respuesta['descuento']);
                    $("#descuentoGlobal").focus();

                    if(tipoComprobante == '01'){
                    $("#idCliente").val(respuesta['idCliente']);
                    $("#docIdentidad").val(respuesta['ruc']);
                    $("#razon_social").val(respuesta['razon_social']);
                    $("#direccion").val(respuesta['direccion']);
                    $("#ubigeo").val(respuesta['ubigeo']);
                    $("#celular").val(respuesta['telefono']);
                    $("#serieNumero").val(respuesta['seriecorrelativo']);
                    $("#serieComprobante").val(respuesta['serie']);
                    $("#numeroComprobante").val(respuesta['correlativo']);
                    $('#tipoDoc').val(6);
                    $(".resultadoSerie").hide();
                    }
                    if(tipoComprobante == '03'){
                    $("#idCliente").val(respuesta['idCliente']);
                    $("#docIdentidad").val(respuesta['dni']);
                    $("#razon_social").val(respuesta['nombre']);
                    $("#direccion").val(respuesta['direccion']);
                    $("#ubigeo").val(respuesta['ubigeo']);
                    $("#celular").val(respuesta['telefono']);
                    $("#serieNumero").val(respuesta['seriecorrelativo']);
                    $("#serieComprobante").val(respuesta['serie']);
                    $("#numeroComprobante").val(respuesta['correlativo']);
                    $('#tipoDoc').val(1);
                    $(".resultadoSerie").hide();
                    }
                
                    // CARGAR DESCUENTO
                    LoadDescuento();
                }
            });
        }
    })
})
   // CARGAR DATOS PARA NOTA DE CRÉDITO
// OBTENER SERIE CORRELATIVO
// CARGA DIRECTA A NOTA DE DÉBITO DESDE REPORTES
window.addEventListener("load",function(){
    let rutaComprobante = $(".fnd #ruta_comprobante").val();
    if(rutaComprobante == "nota-debito"){
    let numCorrelativo = $('.fnd .resultadoSerie').attr("serieCorrelativo");
    ////(numCorrelativo)
     let tipoComprobante = $(".fnd #tipoComp").val();
     
    let datos = {"numCorrelativo": numCorrelativo};
    $.ajax({
        url: "ajax/nota-debito.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){
            loadCarrito();

            // AGREGAR CLIENTES Y DESCUENTO A LA NC
            let correlativoSerie = $('.fnd .resultadoSerie').attr("serieCorrelativo");
            let tipoComprobante = $(".fnd #tipoComp").val();
            let datos2 = {"correlativoSerie": correlativoSerie};
               $.ajax({
                url: "ajax/nota-debito.ajax.php",
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
                    // $(".fnd #descuentoGlobal").val(respuesta['descuento']);
                    // $(".fnd #descuentoGlobal").focus();

                    if(tipoComprobante == '01'){
                    $(".fnd #idCliente").val(respuesta['idCliente']);
                    $(".fnd #docIdentidad").val(respuesta['ruc']);
                    $(".fnd #razon_social").val(respuesta['razon_social']);
                    $(".fnd #direccion").val(respuesta['direccion']);
                    $(".fnd #ubigeo").val(respuesta['ubigeo']);
                    $(".fnd #celular").val(respuesta['telefono']);
                    $(".fnd #serieNumero").val(respuesta['seriecorrelativo']);
                    $(".fnd #serieComprobante").val(respuesta['serie']);
                    $(".fnd #numeroComprobante").val(respuesta['correlativo']);
                     $(".fnd #tipoComprobante").val('01'); 
                     $('.fnd #tipoDoc').val(6);                    
                    $(".fnd #serie > option[value=6]").prop("selected",true);
                    $(".fnd .resultadoSerie").hide();
                    }
                    if(tipoComprobante == '03'){
                    $(".fnd #idCliente").val(respuesta['idCliente']);
                    $(".fnd #docIdentidad").val(respuesta['dni']);
                    $(".fnd #razon_social").val(respuesta['nombre']);
                    $(".fnd #direccion").val(respuesta['direccion']);
                    $(".fnd #ubigeo").val(respuesta['ubigeo']);
                    $(".fnd #celular").val(respuesta['telefono']);
                    $(".fnd #serieNumero").val(respuesta['seriecorrelativo']);
                    $(".fnd #serieComprobante").val(respuesta['serie']);
                    $(".fnd #numeroComprobante").val(respuesta['correlativo']);
                    $('.fnd #tipoDoc').val(1);
                    $(".fnd #tipoComprobante").val('03');
                     $(".fnd #serie > option[value=7]").prop("selected",true);
                    $(".fnd .resultadoSerie").hide();
                    }
                
                    // CARGAR DESCUENTO
                    LoadDescuento();
                }
            });
        }
    })
}
})

$("#tipoComprobante").change(function(){
    let rutaComprobante = $("#ruta_comprobante").val();
    let tipoComprobante = $(this).val();

    if (rutaComprobante ==  "nota-debito"){
    if (tipoComprobante == '01'){
        $('#serie').val(6);
    }
    if (tipoComprobante == '03'){
        $('#serie').val(7);
    }
}
})


   /*================================================================
                GUARDAR NOTA DE DÉBITO
    ===================================================================*/
        $('.btnGuardarND').on('click', function(){
            //let guardarVenta = "guardarVenta";
          let dataForm = $("#formVenta").serialize();
         Swal.fire({
          title: '¿Estás seguro en guardar el comprobante?',
          text: "¡Verifica todo antes de confirmar!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, guardar!',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.isConfirmed) {
            
              $.ajax({
                  method: "POST",
                  url: "ajax/nota-debito.ajax.php",
                  data: dataForm,
                  beforeSend: function() {
                    $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
                  },
                  success: function(respuesta){             
                      //(respuesta);
                         // loadCarrito();
                         Swal.fire({
                          title: 'El comprobante ha sido registrado corréctamente',
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
                        loadCarrito();
                      $("#successCO").html(respuesta); 
                  }
                  
              })     
            
          }
          });
  
  })




