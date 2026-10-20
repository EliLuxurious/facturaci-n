$(".resultadoCliente").hide();
$(".resultadoSerie").hide();
$('#rucActivo').hide();
// EDITAR CLIENTE
$(document).on('click', '.btnEditarCliente', function (){
    let idCliente = $(this).attr('idCliente');
    let datos = new FormData();
    datos.append('idCliente', idCliente);
    $.ajax({
        url: "ajax/clientes.ajax.php",
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (respuesta) {
           
               $("#id").val(respuesta['id']);
               $("#editarCliente").val(respuesta['nombre']);
               $("#editarDni").val(respuesta['documento']);
               $("#editarEmail").val(respuesta['email']);
               $("#editarTelefono").val(respuesta['telefono']);
               $("#editarDireccion").val(respuesta['direccion']);
               $("#editarFechaNacimiento").val(respuesta['fecha_nacimiento']);
               $("#editarRuc").val(respuesta['ruc']);
               $("#editarRS").val(respuesta['razon_social']);
           
        }
    })
})
// ELIMINAR CLIENTE
$(document).on('click', '.btnEliminarCliente', function (){
    let idCliente = $(this).attr('idCliente');
    let datos = new FormData();
    datos.append('idEliminarCliente', idCliente);

    Swal.fire({
        title: '¿Estás seguro de eliminar este cliente?',
        text: "¡Si no lo está puede  cancelar la acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!'
      }).then((result) => {
        if (result.isConfirmed) {
    $.ajax({
        url: "ajax/clientes.ajax.php",
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
         
            if (respuesta == 'success') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'El cliente ha sido eliminado',
                    showConfirmButton: false,
                    timer: 1500
                });

                $('.id'+idCliente).fadeOut(1500, function(){
                    load(1);
                });
                 
                   
                    
            }//if success
        }//succes

    })
}
      })
})
// LISTAR CLIENTES CON BUSCADOR
let perfilOcultoc = $('#perfilOcultoc').val();

    function loadClientes(page){
        let search= $("#search").val();
        let selectnum= $("#selectnum").val();
        let parametros={"action":"ajax","page":page,"search":search,"selectnum":selectnum, "dc":"dc", "perfilOcultoc":perfilOcultoc};
   
    $.ajax({
        url: 'vistas/tables/dataTables.php',
        // method: 'GET',
        data: parametros,  
        // cache: false,
        // contentType: false,
        // processData: false,  
        beforeSend: function(){
            //  $("body").append(loadcl);
        },   
        success:function(data){           
          
                $(".reloadcl").hide();
                $('.body-clientes').html(data); 
             
             
        }
    })
};

    loadClientes(1);

    // BUSCAR RUC O DNI DE LA BASE DE DATOS SI NO SE ENCUENTRA PASA A BUSCAR EN LAS APIS 
$(".buscarRuc").on('click', function(){
    cerrarSession();
    let numDocumento = $("#docIdentidad").val();
    let tipoDocu = $("#tipoDoc").val();   
    let datos = {"numDocumento": numDocumento};
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
            
            if(respuesta != false){
                if(numDocumento.length == 8 && tipoDocu == 1){
                $('#razon_social').val(respuesta['nombre']);
                $('#direccion').val(respuesta['direccion']);
                //$('#ubigeo').val(respuesta['ruc']);
                $('#celular').val(respuesta['telefono']);
                $('#idCliente').val(respuesta['id']);
                $("#reloadC").hide();
                }
                if(numDocumento.length > 8 && tipoDocu == 6){
                $('#razon_social').val(respuesta['razon_social']);
                $('#direccion').val(respuesta['direccion']);
                //$('#ubigeo').val(respuesta['ubigeo']);
                $('#celular').val(respuesta['telefono']);
                $('#idCliente').val(respuesta['id']);
                $("#reloadC").hide();
                }

            }else{
                $('#idCliente').val('');
                let rucCliente = $("#docIdentidad").val();
                let tipoDoc = $("#tipoDoc").val();    
                let datos = {"rucCliente": rucCliente, "tipoDoc": tipoDoc};
                $.ajax({
                    method: "POST",
                    url: 'ajax/clientes.ajax.php',
                    data: datos,
                    dataType: "json",
                    beforeSend: function(){
                        if(rucCliente != ''){
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
    cerrarSession();
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
            document.getElementById('reloadC').style.visibility = "visible";
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
                $('#idCliente').val(respuesta['id']);
                document.getElementById('reloadC').style.visibility = "hidden";
                // $("#reloadC").hide();
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
            cerrarSession();
            let numeroDoc = $(this).val();
            let tipoDocumento = $("#tipoDoc").val();
            let datos = {"numeroDoc": numeroDoc, "tipoDocumento": tipoDocumento};
            $.ajax({
                method: "POST",
                url: 'ajax/clientes.ajax.php',
                data: datos,
                // dataType: "json",
                
                beforeSend: function(){
                    //$("#reloadC").show(50).html("<img src='vistas/img/reload.svg'> ");
                    
                },
                success: function (respuesta){
                  
                    if(respuesta != false){
                    if(numeroDoc != '' && numeroDoc.length > 3){
                        $(".resultadoCliente").show();   

                    $(".resultadoCliente").html(respuesta);
          
                }else{
                    $(".resultadoCliente").hide();
                
            }
    
                }else{
                    $(".resultadoCliente").hide();
                }
            }
            })
        })

// AGREGAR CLIENTE A LOS INPUTS 
    $(document).on("click",".btn-add", function(e){
        e.preventDefault();
        cerrarSession();
        let tipoDocumento = $("#tipoDoc").val();
    let idCliente = $(this).attr('idCliente');
    let datos = new FormData();
    datos.append('idCliente', idCliente);
    $.ajax({
        url: "ajax/clientes.ajax.php",
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (respuesta) {  
            if(tipoDocumento == 1){

            $('#idCliente').val(respuesta['id']);
            $('#razon_social').val(respuesta['nombre']);
            $('#docIdentidad').val(respuesta['documento']);
            $('#direccion').val(respuesta['direccion']);
            $('#ubigeo').val(respuesta['ubigeo']);
            $('#celular').val(respuesta['telefono']);
            $('#email').val(respuesta['email']);
            $(".resultadoCliente").hide();
            }else{

            $('#idCliente').val(respuesta['id']);
            $('#razon_social').val(respuesta['razon_social']);
            $('#docIdentidad').val(respuesta['ruc']);
            $('#direccion').val(respuesta['direccion']);
            $('#ubigeo').val(respuesta['ubigeo']);
            $('#celular').val(respuesta['telefono']);
            $('#email').val(respuesta['email']);
            $(".resultadoCliente").hide();
            }
           
        }
    })
})
$('.modoemail').change(function() {
    let modo = $(".modoemail:checked").val();
    
     if(modo == "s"){

        $("#sie").addClass("emailsi");
        $("#sie").html("Sí");
        $("#noe").html("||");
        $("#sie").removeClass("alterno");
        $("#noe").addClass("alterno");
        $('.email-colunma').show(500);

        }else{
            $("#noe").addClass("emailno");
            $("#noe").html("No");
            $("#sie").html("||");
            $("#sie").addClass("alterno");
            $("#noe").removeClass("alterno");
            $('.email-colunma').hide(200);

        }
    });
function loadEmaiChange(){
    let modo = $(".modoemail:checked").val();
    
     if(modo == "s"){

        $("#sie").addClass("emailsi");
        $("#sie").html("Sí");
        $("#noe").html("||");
        $("#sie").removeClass("alterno");
        $("#noe").addClass("alterno");
       

        }else{
            $("#noe").addClass("emailno");
            $("#noe").html("No");
            $("#sie").html("||");
            $("#sie").addClass("alterno");
            $("#noe").removeClass("alterno");
            $('.email-colunma').hide();
        }
    };
    loadEmaiChange();