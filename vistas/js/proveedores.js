$(".resultadoProveedor").hide();
$(".resultadoSerie").hide();
$('#rucActivo').hide();


    // BUSCAR RUC O DNI DE LA BASE DE DATOS SI NO SE ENCUENTRA PASA A BUSCAR EN LAS APIS 
$(".buscarRucP").on('click', function(){
    let numDocumento = $("#docIdentidad").val();
    let tipoDocu = $("#tipoDoc").val();   
    let datos = {"numDocumentoP": numDocumento};
    ////(numDocumento)
    $.ajax({
        method: "POST",
        url: 'ajax/proveedores.ajax.php',
        data: datos,
        dataType: "json",
        
        beforeSend: function(){
            $("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
            
        },
        success: function (respuesta){
            
            if(respuesta != false){
                if(numDocumento.length == 8 && tipoDocu == 1){
                $('#razon_social').val(respuesta['nombre']);
                $('#direccion').val(respuesta['direccion']);
                //$('#ubigeo').val(respuesta['ruc']);
                $('#celular').val(respuesta['telefono']);
                $('#idProveedor').val(respuesta['id']);
                $("#reloadC").hide();
                }
                if(numDocumento.length > 8 && tipoDocu == 6){
                $('#razon_social').val(respuesta['razon_social']);
                $('#direccion').val(respuesta['direccion']);
                //$('#ubigeo').val(respuesta['ubigeo']);
                $('#celular').val(respuesta['telefono']);
                $('#idProveedor').val(respuesta['id']);
                $("#reloadC").hide();
                }

            }else{
                $('#idProveedor').val('');
                let rucProveedor = $("#docIdentidad").val();
                let tipoDoc = $("#tipoDoc").val();    
                let datos = {"rucProveedor": rucProveedor, "tipoDoc": tipoDoc};
                $.ajax({
                    method: "POST",
                    url: 'ajax/proveedores.ajax.php',
                    data: datos,
                    dataType: "json",
                    beforeSend: function(){
                        if(rucProveedor != ''){
                        $("#reloadC").show(5).html("<img src='vistas/img/reload.svg'> ");
                        document.getElementById('reloadC').style.visibility = "visible";
                        }
                    },
                    success: function (respuesta){                   
                       
                        if(respuesta != 'error'){                              
                        
                            $("#reloadC").hide();             
                            //   var json = eval(respuesta);
                            $("#docIdentidad").val(respuesta['ruc']);
                              $('#razon_social').val(respuesta['razon_social']);
                              $('#direccion').val(respuesta['direccion']);
                              $('#ubigeo').val(respuesta['ubigeo']);
                              document.getElementById('reloadC').style.visibility = "hidden";
                              $('#celular').val('');

                              if(respuesta['estado'] == 'ACTIVO'){
                                 $('#rucActivo').show().css("background", "#59C345").html(respuesta['estado']);
                              }else{
                                  if(tipoDocu == '06'){
                                $("#docIdentidad").val('');
                                  $('#rucActivo').show().css("background", "#DC5858").html(respuesta['estado']);
                              }
                            }
                            }else{
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'El DNI/RUC no se encuentra',
                                    showConfirmButton: false,
                                    timer: 2500
                                  })
                                  
                              $("#reloadC").hide();
                              $('#razon_social').val('');
                              $('#direccion').val('');
                              $('#ubigeo').val('');
                              $('#celular').val('');
                            }
                    }
                })
          
            
            }
        }
    })
})
// TIPO DOCUMENTO 0000000 PARA BOLETAS SIN DOCUMENTO
$("#tipoDoc").on('change', function(){
    let numDocumento = "00000000";
    let tipoDocu = $("#tipoDoc").val();   
    let datos = {"numDocumento": "00000000"};
    ////(numDocumento)
    $.ajax({
        method: "POST",
        url: 'ajax/clientes.ajax.php',
        data: datos,
        dataType: "json",
        
        beforeSend: function(){
            $("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
            
        },
        success: function (respuesta){
            ////(respuesta)
            if(respuesta != false){
                if(tipoDocu == 0 ){
                $('#razon_social').val(respuesta['nombre']);
                $('#docIdentidad').val(respuesta['documento']);
                $('#direccion').val(respuesta['direccion']);
                //$('#ubigeo').val(respuesta['ruc']);
                $('#celular').val(respuesta['telefono']);
                $('#idProveedor').val(respuesta['id']);
                $("#reloadC").hide();
                }else{
                    $("#reloadC").hide();
                    $('#razon_social').val('');
                $('#docIdentidad').val('');
                $('#direccion').val('');
                $('#celular').val('');
                $('#ubigeo').val('');
                $('#docIdentidad').focus();
                }
            }
        }
            });
        })

        // BUSCAR CLIENTE PARA COMPROBANTE|
        $("#docIdentidad").keyup(function(){
            let numeroDoc = $(this).val();
            let tipoDocumento = $("#tipoDoc").val();
            let datos = {"numeroDocP": numeroDoc, "tipoDocumentoP": tipoDocumento};
            $.ajax({
                method: "POST",
                url: 'ajax/proveedores.ajax.php',
                data: datos,
                // dataType: "json",
                
                beforeSend: function(){
                    //$("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
                    
                },
                success: function (respuesta){
                  
                    if(respuesta != false){
                    if(numeroDoc != '' && numeroDoc.length > 3){
                        $(".resultadoProveedor").show();   

                    $(".resultadoProveedor").html(respuesta);
          
                }else{
                    $(".resultadoProveedor").hide();
                
            }
    
                }else{
                    $(".resultadoProveedor").hide();
                }
            }
            })
        })

// AGREGAR CLIENTE A LOS INPUTS 
    $(document).on("click",".btn-add-p", function(e){
        e.preventDefault();
        let tipoDocumento = $("#tipoDoc").val();
    let idProveedor = $(this).attr('idProveedor');
    let datos = new FormData();
    datos.append('idProveedor', idProveedor);
    $.ajax({
        url: "ajax/proveedores.ajax.php",
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (respuesta) {  
            //(respuesta);
            if(tipoDocumento == 1){

            $('#idProveedor').val(respuesta['id']);
            $('#razon_social').val(respuesta['nombre']);
            $('#docIdentidad').val(respuesta['documento']);
            $('#direccion').val(respuesta['direccion']);
            $('#ubigeo').val(respuesta['ubigeo']);
            $('#celular').val(respuesta['telefono']);
            $('#email').val(respuesta['email']);
            $(".resultadoProveedor").hide();
            $("#reloadC").hide();
            }else{

            $('#idProveedor').val(respuesta['id']);
            $('#razon_social').val(respuesta['razon_social']);
            $('#docIdentidad').val(respuesta['ruc']);
            $('#direccion').val(respuesta['direccion']);
            $('#ubigeo').val(respuesta['ubigeo']);
            $('#celular').val(respuesta['telefono']);
            $('#email').val(respuesta['email']);
            $(".resultadoProveedor").hide();
            $("#reloadC").hide();
            }
           
        }
    })
})
