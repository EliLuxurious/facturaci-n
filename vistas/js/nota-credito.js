
 // CARGAR DATOS PARA NOTA DE CRÉDITO
// OBTENER SERIE CORRELATIVO
$(".btn-add-serie").on("click", function(e){
    e.preventDefault();
    let numCorrelativo = $('.resultadoSerie').attr("serieCorrelativo");
    ////(numCorrelativo)
     let tipoComprobante = $("#tipoComp").val();
    let datos = {"numCorrelativo": numCorrelativo};
    $.ajax({
        url: "ajax/nota-credito.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){ 

             if(respuesta == 'error'){
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: '¡Ya se ha emitido una NC para este comprobante',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                }).then((result) => {
                    if (result.isConfirmed) {

                         location.reload();
                    }
                  })
                
               
             }else{
        //    loadCarrito();
            // AGREGAR CLIENTES Y DESCUENTO A LA NC
            let correlativoSerie = $('.resultadoSerie').attr("serieCorrelativo");
            let tipoComprobante = $("#tipoComprobante").val();
            let datos2 = {"correlativoSerie": correlativoSerie};
           
               $.ajax({
                url: "ajax/nota-credito.ajax.php",
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
    }
    })
})
// CARGA DIRECTA A NOTA DE CRÉDITO DESDE REPORTES 
window.addEventListener("load",function(){
    let rutaComprobante = $("#ruta_comprobante").val();
    if(rutaComprobante == "nota-credito"){
    let numCorrelativo = $('.resultadoSerie').attr("serieCorrelativo");
    ////(numCorrelativo)
     let tipoComprobante = $("#tipoComp").val();
    let datos = {"numCorrelativo": numCorrelativo};
    $.ajax({
        url: "ajax/nota-credito.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){
            loadCarrito();

            // AGREGAR CLIENTES Y DESCUENTO A LA NC
            let correlativoSerie = $('.resultadoSerie').attr("serieCorrelativo");
            let tipoComprobante = $("#tipoComp").val();
            let datos2 = {"correlativoSerie": correlativoSerie};
               $.ajax({
                url: "ajax/nota-credito.ajax.php",
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
                    $("#tipoComprobante").val('01');
                    $('#tipoDoc').val(6);
                    //$('#serie').val(4);
                    $("#serie > option[value=4]").prop("selected",true);
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
                    $("#tipoComprobante").val('03');
                    $('#tipoDoc').val(1);
                    //$('#serie').val(5);
                    $("#serie > option[value=5]").prop("selected",true);
                    $(".resultadoSerie").hide();
                    }
                
                    // CARGAR DESCUENTO
                    LoadDescuento();
              

                    
                }
            });
        }
    })
}
})
// CARGA AL CARRITO DESDE VENTAS -BOLETAS
window.addEventListener("load",function(){
    let rutaComprobante = $("#ruta_comprobante").val();
    if(rutaComprobante == "crear-boleta"){
    let numCorrelativo = $("#serieCorrelativo").val();
    let datos = {"numCorrelativo": numCorrelativo};
    $.ajax({
        url: "ajax/nota-credito.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){
            loadCarrito();


                 
        }
    })
}
})
// CARGA AL CARRITO DESDE VENTAS - FACTURA
window.addEventListener("load",function(){
    let rutaComprobante = $("#ruta_comprobante").val();
    if(rutaComprobante == "crear-factura"){
    let numCorrelativo = $("#serieCorrelativo").val();
    let datos = {"numCorrelativo": numCorrelativo};
    $.ajax({
        url: "ajax/nota-credito.ajax.php",
        method: "POST",
        data : datos,
        success: function(respuesta){
            //(respuesta);
            loadCarrito();


                 
        }
    })
}
})

$("#tipoComprobante").change(function(){
    let rutaComprobante = $("#ruta_comprobante").val();
    let tipoComprobante = $(this).val();

    if (rutaComprobante ==  "nota-credito"){
    if (tipoComprobante == '01'){
        $('#serie').val(4);
    }
    if (tipoComprobante == '03'){
        $('#serie').val(5);
    }
}
})


   /*================================================================
                GUARDAR NOTA DE CRÉDITO
    ===================================================================*/
    $('.btnGuardarNC').on('click', function(){
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
                url: "ajax/nota-credito.ajax.php",
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

