// $(document).ready(function() {
    

  // LISTAR PRODUCTOS PARA AGREGAR AL CARRO CON BUSCADOR
  function loadProductosV(page){ 
      let searchProductoV= $("#searchProductoV").val();
      let selectnum= $("#selectnum").val();
      let categorias= $("#categorias").val();
      let parametros={"action":"ajax","page":page,"searchProductoV":searchProductoV,"selectnum":selectnum, "categorias":categorias, "dpv":"dpv"};
      
      $.ajax({
          url: 'vistas/tables/dataTables.php',
          // method: 'GET',
          data: parametros,  
          // cache: false,
          // contentType: false,
          // processData: false,  
          beforeSend: function(){
            //   $("#modalProductosVenta").append(loadcar);
            },   
            success:function(data){        
               
                $(".reloadc").hide();
                $('.body-productos-ventas').html(data);   
                $(".tablaVentas .super-contenedor-precios").hide();    
            }
        })
    };
    loadProductosV(1);
$(document).on('click', '.btn-agregar-carrito', function(e){
    loadProductosV(1);
    cerrarSession();
})
    // AGREGAR PRODUCTOS AL CARRO
    $(document).on("click", "button.agregarProducto", function(){
        let descripcionProducto = $(this).attr("descripcionP");       
        let idProducto = $(this).attr("idProducto");
        let descuentoGlobal = $("#descuentoGlobal").val();
        let descuentoGlobalP = $("#descuentoGlobalP").val();
        let tipo_desc = $('input[name=tipo_desc]:checked').val();
        let moneda = $("#moneda").val();
        let cantidad = $("#cantidad"+idProducto).val();
        let descuento_item = $(".descuento_item"+idProducto).val();
        let tipo_afectacion = $(".tipo_afectacion"+idProducto).val();
        let precio_unitario = $(".precio_unitario"+idProducto).val();
        let valor_unitario = $(".valor_unitario"+idProducto).val();
        let igv = $(".igv"+idProducto).val();
        let icbper = $(".icbper"+idProducto).val();
        let tipo_cambio = $("#tipo_cambio").val();
       
        ////(idProducto);
        let datos = {"idProducto":idProducto, "descuentoGlobal":descuentoGlobal ,"cantidad":cantidad, "moneda":moneda, "tipo_desc":tipo_desc, "descuentoGlobalP":descuentoGlobalP,"tipo_cambio":tipo_cambio,"descuento_item": descuento_item, "tipo_afectacion": tipo_afectacion, 'precio_unitario': precio_unitario, 'valor_unitario': valor_unitario, 'igv': igv, 'icbper': icbper};
        
        $.ajax({
            method: "POST",
            url: "ajax/ventas.ajax.php",
            data: datos,
            success: function(respuesta){
               
                $('.nuevoProducto table #itemsP').html(respuesta);
                $(".super-contenedor-precios").hide();
             
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    // width: 600,
                    // padding: '3em',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })
                  
                  Toast.fire({
                    icon: 'success',
                    title:  `<h5>Se ha agregado al carrito</h5>`,
                    html:  `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-shopping-cart"></i> ${descripcionProducto}</div`,
                    
                  })
                // comillas invertidas  (``);
                $(".contenedor-items").fadeIn(200);
                $(".tablaVentas thead").fadeIn(200);
            }
        })
    })
    
    
    // ELIMINAR TODOS LOS ITEMS DEL CARRO
    $(".formVenta").on("click", "button.btnEliminarCarro", function(){
        let eliminarCarro = "eliminarCarro";
        let datos = {"eliminarCarro":eliminarCarro};
        $.ajax({
            method: "POST",
            url: "ajax/ventas.ajax.php",
            data: datos,
            success: function(respuesta){
                $('.nuevoProducto table #itemsP').html('');
                $('.totales').html('');
                $('.totales').html(`       
                <tr class="op-subt">
                <td>SubTotal</td><td>0.00</td>
                </tr>
                <tr class="op-gravadas">
                <td>Op.Gravadas</td><td>0.00</td>
                </tr>
                <tr class="op-exoneradas">
                <td>Op.Exoneradas</td><td>0.00</td>
                </tr>
                <tr class="op-inafectas">
                <td>Op.Inafectas</td><td>0.00</td>
                </tr>   
                <tr class="op-gratuitas">
                <td>Op.gratuitas</td><td>0.00</td>
                </tr>         
                <tr class="op-descuento">
                <td>Descuento</td><td>0.00</td>
                </tr>
                <tr class="icbper">
                <td>ICBPER</td><td>0.00</td>
                </tr>
                <tr class="op-igv">
                <td>IGV(18%)</td><td>0.00</td>
                </tr>
                
                <tr class="op-total">
                <td>Total</td><td>0.00</td>
                </tr>
                
                
                `);
                $('.op-subt').hide();
                $('.op-gravadas').hide();
                $('.op-exoneradas').hide();
                $('.op-inafectas').hide();
            }
        })
    })
    
    // ELIMINAR ITEM DEL CARRO
    $(".formVenta").on("click", "button.btnEliminarItemCarro", function(){
        let idEliminarCarro = $(this).attr("itemEliminar");
        let descuentoGlobal = $("#descuentoGlobal").val();
        let descuentoGlobalP = $("#descuentoGlobalP").val();
        let tipo_desc = $('input[name=tipo_desc]:checked').val();
        let moneda = $("#moneda").val();
        //let cantidad = $("#cantidad"+idProducto).val();
        let tipo_cambio = $("#tipo_cambio").val();
        let datos = {"idEliminarCarro":idEliminarCarro, "moneda":moneda, "descuentoGlobal":descuentoGlobal,"descuentoGlobalP":descuentoGlobalP, "tipo_desc":tipo_desc, "tipo_cambio":tipo_cambio};
        $.ajax({
            method: "POST",
            url: "ajax/ventas.ajax.php",
            data: datos,
            success: function(respuesta){
               
                $('.id-eliminar'+idEliminarCarro).fadeOut(500, function(){
                    
                    $('.nuevoProducto table #itemsP').html(respuesta);
                    
                    LoadDescuento();
                    
                });
                
            }
        })
    })
    
    //CARGAR CARRO
    function loadCarrito(){
        let loadCarrito = "loadCarrito";
        let moneda = $("#moneda").val();
        let tipo_cambio = $("#tipo_cambio").val();
        let descuentoGlobal = $("#descuentoGlobal").val();
        let descuentoGlobalP = $("#descuentoGlobalP").val();
        let datos = {"loadCarrito":loadCarrito, "moneda":moneda, "tipo_cambio":tipo_cambio, 'descuentoGlobal':descuentoGlobal};
        $.ajax({
            method: "POST",
            url: "ajax/ventas.ajax.php",
            data: datos,
            success: function(respuesta){
                
                $('.nuevoProducto table #itemsP').html(respuesta);
            }
        })
    }
    // CARGAR CARRO
    loadCarrito();
    
    // SOLO INGRESAR NUMEROS CAMPO RUC-DNI
    // $('#docIdentidad').keyup(function() {
    //     var ruc = $(this).val();
        
    //     //this.value = (this.value + '').replace(/[^0-9]/g, '');
    //     if(!$.isNumeric(ruc)) {                 
    //         //dni = dni.substr(0,(dni.length -1));
    //         ruc = ruc.replace(/[^0-9]/g, '');
    //         $('#docIdentidad').val(ruc);
    //     }
        
    // });
//EDITAR ITEM DEL CARRITORY
// $(document).on('click', '.btnEditarItemCarro', function(e) {

//     let codigo = $(this).attr('itemEditar');
//     let cantidad = $(this).attr('itemCantidad');
//     let id = $(this).attr('itemId');    
//     $(".contenedor-items").hide();
//     $("#searchProductoV").focus();  
//         $("#searchProductoV").val('');
//     $("#searchProductoV").val(codigo); 
//     loadProductosV(1);
//        $(".tablaVentas #cantidad"+id).val(cantidad);   
//     loadProductosV(1);
  
// })
    /*================================================================
    GUARDAR VENTA
    ===================================================================*/
    $('.btnGuardarVenta').on('click', function(){
        //let guardarVenta = "guardarVenta";
        let numcuotas = $('#numcuotas').val();
        let tipopago = $('#tipopago').val();
        if(tipopago == "Credito"){
            let fechac = $('#fecha_cuota').val();
            let cuotac = $('#cuotas').val();
            if(fechac == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '¡Debes ingresar todas las fechas para las cuotas!'
                        //footer: '<a href>Why do I have this issue?</a>'
                      })
                    exit();
                }
                 if(cuotac == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '¡Debes ingresar todos los montos!'
                        //footer: '<a href>Why do I have this issue?</a>'
                      })
                    exit();
                 }
            for (let i = 2; i <=numcuotas; i++){
                let fecha = $('#fecha_cuota'+i).val();  
                let cuota = $('#cuotas'+i).val();  
                if(fecha == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '¡Debes ingresar todas las fechas para las cuotas!'
                        //footer: '<a href>Why do I have this issue?</a>'
                      })
                    exit();
                }
                if(cuota == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '¡Debes ingresar todos los montos!'
                        //footer: '<a href>Why do I have this issue?</a>'
                      })
                    exit();
                }
            }

        }
        
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
                    url: "./ajax/ventas.ajax.php",
                    type: "POST",
                    data: dataForm,
                    beforeSend: function() {
                        $(".reload-all").fadeIn(50).html("<img src='vistas/img/reload.svg' width='80px'> ");
                    },
                    success: function(respuesta){             
                        // loadCarrito();
                        Swal.fire({
                            title: '',
                            text: '¡Gracias!',
                            icon: 'success',
                            html:
                            '<div id="successCO"></div><div id="successemail"></div>',
                            showCancelButton: true,
                            showConfirmButton: false,
                            allowOutsideClick: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cerrar',
                        })
                        $(".reload-all").fadeOut(50);
                        loadCarrito();
                        $("#successCO").html(respuesta); 
                        $('#rucActivo').hide();

                        enviarReporteVenta();
                        loadComrobantesNoEnviados();
                    }
                    
                })     
                
            }
        });
        
    })
    $(document).on("click","#printA4", function(e){
        
        document.getElementById("a4").checked = true;
    });
    $(document).on("click","#printT", function(e){
        
        document.getElementById("tk").checked = true;
    });

    
        //   REENVIAR REPORTES AL CORREO===========================
 function enviarReporteVenta(){
    let enviarporemail = $(".modoemail:checked").val();
    let idCo = $("#idCo").val(); 
    let sendemail = $("#email").val();
   var ruta = $("#ruta_comprobante").val();
   let datos = {"idCo":idCo, "sendemail":sendemail};
 
 if(enviarporemail == "s"){
     if(ruta == 'crear-cotizacion'){
        var urle = "vistas/print/sendCotizacion.php";
     }else{
        var urle = "vistas/print/send.php";
     }
   $.ajax({
       method: "POST",
       url: urle,
       data: datos,
       beforeSend: function(){
        $("#successemail").fadeIn(20).html("<img src='vistas/img/reload.svg' width='60px'> ");
       },
       success: function(enviarReport){
        console.log(enviarReport);
           if(enviarReport == 'ok'){
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                // width: 600,
                // padding: '3em',
                showConfirmButton: false,
                timer: 6000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })
              
              Toast.fire({
                icon: 'success',
                title:  `<h5>COMPROBANTE ENVIADO A: ${sendemail}</h5>`,
                
              })
            //    $("#successemail").html("ENVIADO CORRECTAMENTE AL CORREO: " + sendemail);
               $(".modo-contenedor-email #si").prop('checked',false);
               $(".modo-contenedor-email #no").prop('checked','checked');
               $('#email').val('');
               loadEmaiChange();
         
     }else{
        
     }
       }
   })
     }
 }
    //DESCUENTO GLOBAL
    // $(document).on('keyup change', "#descuentoGlobal,#descuentoGlobalP", function(){
    //     let descontar = "descontar";
    //     let descuentoGlobal = $("#descuentoGlobal").val();
    //     let descuentoGlobalP = $("#descuentoGlobalP").val();
    //     let moneda = $("#moneda").val();
    //     let tipo_desc = $('input[name=tipo_desc]:checked').val(); 
    //     let tipo_cambio = $("#tipo_cambio").val();
    //     let datos = {"descuentoGlobal":descuentoGlobal, "descontar":descontar, "moneda":moneda, "tipo_desc":tipo_desc, "descuentoGlobalP":descuentoGlobalP, "tipo_cambio":tipo_cambio};
    //     $.ajax({
    //         method: "POST",
    //         url: "ajax/ventas.ajax.php",
    //         data: datos,
    //         //dataType: "json",
    //         success: function(respuesta){
               
    //             $('.nuevoProducto table #itemsP').html(respuesta);
                
    //         }
    //     })
    // });
    $(document).on('keyup', "#descuentoGlobal", function(){
        LoadDescuento();
         let valor = $(this).val();
       if(valor == 0){
        $("#descuentoGlobalP").val(0);
       }
    })
    $(document).on('change', "#descuentoGlobal", function(){
        LoadDescuento();
         let valor = $(this).val();
       if(valor == 0){
        $("#descuentoGlobalP").val(0);
       }
    })
    $(document).on('keyup', "#descuentoGlobalP", function(){
        LoadDescuento();
         let valor = $(this).val();
       if(valor == 0){
        $("#descuentoGlobal").val(0);
       }
    })
    $(document).on('change', "#descuentoGlobalP", function(){
        LoadDescuento();
         let valor = $(this).val();
       if(valor == 0){
        $("#descuentoGlobal").val(0);
       }
    })
    // CARGAR EL DESCUENTO 
    function LoadDescuento(){
        let descontar = "descontar";
        let descuentoGlobal = $("#descuentoGlobal").val();
        let descuentoGlobalP = $("#descuentoGlobalP").val();
        let moneda = $("#moneda").val();
        let tipo_cambio = $("#tipo_cambio").val();
        let tipo_desc = $('input[name=tipo_desc]:checked').val();
        let datos = {"descuentoGlobal":descuentoGlobal, "descuentoGlobalP":descuentoGlobalP,"descontar":descontar, "moneda":moneda, "tipo_desc":tipo_desc, "tipo_cambio":tipo_cambio};
        $.ajax({
            method: "POST",
            url: "ajax/ventas.ajax.php",
            data: datos,
            //dataType: "json",
            success: function(respuesta){
                
                $('.nuevoProducto table #itemsP').html(respuesta);
                
            }
        })
    };
    // LISTAR VENTAS BOLETAS FACTURAS
    function loadVentas(page){
        let searchVentas= $("#searchVentas").val();
        let selectnum= $("#selectnum").val();
        let fechaInicial = $('#fechaInicial').val();
        let fechaFinal = $('#fechaFinal').val();
        let parametros={"action":"ajax","page":page,"searchVentas":searchVentas,"selectnum":selectnum, "dv":"dv", "fechaInicial":fechaInicial, "fechaFinal":fechaFinal};
        
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
                
                $(".reload-all").hide();
                $('.body-ventas').html(data); 
                
                
            }
        })
    };
    loadVentas(1);
    
    $("#sol").on('click',function(){
        $("#por").addClass('off');
        $("#por").removeClass('on');
        $("#sol").removeClass('off');
        $("#sol").addClass('on');
        $("#descuentoGlobal").show();
        $("#descuentoGlobalP").hide(); 
        $(".ico-desc").html("");
        $(".ico-desc").addClass("fa-money");
        
    })
    $("#por").on('click',function(){
        $("#sol").removeClass('on');
        $("#sol").addClass('off');    
        $("#por").removeClass('off');
        $("#por").addClass('on-por');
        $("#descuentoGlobal").hide();
        $("#descuentoGlobalP").show();
        $(".ico-desc").html("%");
        $(".ico-desc").removeClass("fa-money");
        
    })
    
    function tipoCambio() {
        let fecha = $("#fecha").val();
        let dato = {"tipo_cambio": 'tipo_cambio', "fecha": fecha};
        $.ajax({
            url: "Controladores/tipo-cambio.php",
            method: "POST",
            data: dato,
            dataType: "json",
            success: function(datos){
              
                $("#tipo_cambio").val(datos['venta']);
                $("#tipocambio").html('TC - Venta: '+datos['venta']+' Compra: '+datos['compra']);
                
            }
        })
    }
    tipoCambio();
    
    $("#moneda").change(function(){
        loadCarrito();
    })
    
    $(".tablaVentas").on("click",".printA4", function(e){
        let idComp = $(this).attr("idComp");
     
        $(".a4"+idComp).prop("checked",true);
        $(".tk"+idComp).prop("checked",false);
        
    });
    $(".tablaVentas").on("click",".printT", function(e){
        let idComp = $(this).attr("idComp");   
        $(".tk"+idComp).prop("checked", true);
        $(".a4"+idComp).prop("checked", false);
        
    });
    
    
    //Date range as a button
    // BUSCAR POR FECHAS
    // ======================================    
    $('#daterange-btn').daterangepicker(
        {
            "locale": {
                "format": "YYYY-MM-DD",
                "separator": " - ",
                "applyLabel": "Guardar",
                "cancelLabel": "Cancelar",
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Personalizar",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Setiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ]
            },
      
          ranges   : {
            'Hoy'       : [moment(), moment()],
            'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Díass' : [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
            'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
            'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          
          startDate: moment(),
          endDate  : moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
          let fechaInicial = start.format('YYYY-MM-DD');
          let fechaFinal = end.format('YYYY-MM-DD');

         $('#fechaInicial').val(fechaInicial);
         $('#fechaFinal').val(fechaFinal);
        loadVentas(1);
        loadGuiasR(1);
        loadCotizaciones(1);
        }
      );
      $('.daterangepicker .ranges li').on('click', function(){
        let fechaHoy = $('.daterangepicker .active').val();
      
         $('#fechaInicial').val(fechaHoy);
         $('#fechaFinal').val(fechaHoy);
         
         loadVentas(1);
         loadGuiasR(1);
      })
    // FIN BUSCAR POR FECHAS
    // ======================================   
    // })
    // $(".btn-agregar-carrito").on('click', function(){
    //     $.ajax({
    //         url : "vistas/modulos/table-productos.php",
    //         success : function(respuesta){
    //             $("#productosCarrito").html(respuesta); 
    //                 loadProductosV(1);  
    //     }
    //     }) 
       
    //     // $("#productosCarrito").load('vistas/modulos/table-productos.php');
       
    // })
    function loadComrobantesNoEnviados() {
        let noEnviados = 'noEnviados';
        let datos = {'noEnviados' : noEnviados};
        $.ajax({
            method: "POST",
            url: "ajax/ventas.ajax.php",
            data: datos,
            //dataType: "json",
            success: function(respuesta){
              
               if(respuesta > 1){
                
                $('.no-enviados').html(respuesta);
                $('.no-enviados-text').html("Tienes <b>"+respuesta+"</b> comprobantes no enviados");
                $('.no-enviados-items').html("No se olvide");

               } else if(respuesta == 1){
                $('.no-enviados').html(respuesta);
                $('.no-enviados-text').html("Tienes <b>"+respuesta+"</b> comprobante no enviado");
                $('.no-enviados-items').html("No se olvide");
                
               }else{
                $('.no-enviados').html(respuesta);
                $('.no-enviados-text').html("No tienes comprobantes pendientes");
                $('.no-enviados-items').html("Todo está muy bien");
               }
            }
        })
        
    }
    loadComrobantesNoEnviados();


    $(document).on('click','.btn-icb-si',function() {
        let id = $(this).attr('idProducto');
        let modo = $(this).attr('val'); 
        let cantidad = $("#cantidad"+id).val();     
      
      
        if(modo == "s"){
    
            $("#s"+id).addClass("icbsi");
            $("#s"+id).html("Sí");
            $("#n"+id).html("||");
            $("#s"+id).removeClass("alterno");
            $("#n"+id).addClass("alterno");
            
            $(".icbper"+id).val(0.30 * cantidad);
            $(".modo-icbper"+id).val('s');
            }
           
        });


    $(document).on('click','.btn-icb-no',function() {
        let id = $(this).attr('idProducto');
        let modo = $(this).attr('val');      
        
      
        if(modo == "n"){

            $("#n"+id).addClass("icbno");
                $("#n"+id).html("No");
                $("#s"+id).html("||");
                $("#s"+id).addClass("alterno");
                $("#n"+id).removeClass("alterno");
    
                $(".icbper"+id).val('');
                 $(".modo-icbper"+id).val('n');
            }
           
        });

        $(document).on('click','.anular-nota', function(){
            let idventa = $(this).attr('idComp');
            let datos = {'idventa': idventa};
            Swal.fire({
                title: '¿Estás seguro en anular el comprobante?',
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
                method: 'POST',
                url: 'ajax/ventas.ajax.php',
                data: datos,
                beforeSend: function(){

                },
                success: function(datos){
                    if(datos == 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Ok...',
                            text: '¡El comprobante ha sido anulado!'
                            //footer: '<a href>Why do I have this issue?</a>'
                          })
                        loadReportes(1);
                    }
                }
            })
        }
        })
        })
