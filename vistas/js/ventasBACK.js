// LISTAR PRODUCTOS PARA AGREGAR AL CARRO CON BUSCADOR
function loadProductosV(page){
    let searchProductoV= $("#searchProductoV").val();
    let selectnum= $("#selectnum").val();
    let parametros={"action":"ajax","page":page,"searchProductoV":searchProductoV,"selectnum":selectnum, "dpv":"dpv"};

$.ajax({
    url: 'vistas/tables/dataTables.php',
    // method: 'GET',
    data: parametros,  
    // cache: false,
    // contentType: false,
    // processData: false,  
    beforeSend: function(){
         $("#reload").fadeIn(50).html("<img src='vistas/img/reload.svg'> ");
    },   
    success:function(data){        
      
            $("#reload").hide();
            $('.body-productos-ventas').html(data);       
    }
})
};
loadProductosV(1);

// AGREGAR PRODUCTOS AL CARRO
$(".tablaVentas tbody").on("click", "button.agregarProducto", function(){
    let idProducto = $(this).attr("idProducto");
    let descuentoGlobal = $("#descuentoGlobal").val();
    let descuentoGlobalP = $("#descuentoGlobalP").val();
    let tipo_desc = $('input[name=tipo_desc]:checked').val();
    let moneda = $("#moneda").val();
    let cantidad = $("#cantidad"+idProducto).val();
    let tipo_cambio = $("#tipo_cambio").val();
    ////(idProducto);
    let datos = {"idProducto":idProducto, "descuentoGlobal":descuentoGlobal ,"cantidad":cantidad, "moneda":moneda, "tipo_desc":tipo_desc, "descuentoGlobalP":descuentoGlobalP,"tipo_cambio":tipo_cambio};

    $.ajax({
        method: "POST",
        url: "ajax/ventas.ajax.php",
        data: datos,
        success: function(respuesta){
  
           $('.nuevoProducto table #itemsP').html(respuesta);

     
// comillas invertidas  (``);
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
                                    <tr class="op-descuento">
                                    <td>Descuento</td><td>0</td>
                                    </tr>
                                    <tr class="op-igv">
                                    <td>IGV(18%)</td><td>0</td>
                                    </tr>

                                    <tr class="op-total">
                                    <td>Total</td><td>0</td>
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
    let tipo_cambio = $("#tipo_cambio").val();
    let datos = {"idEliminarCarro":idEliminarCarro, "moneda":moneda, "descuentoGlobal":descuentoGlobal, "descuentoGlobalP":descuentoGlobalP, "tipo_desc":tipo_desc, "tipo_cambio":tipo_cambio};
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
    let datos = {"loadCarrito":loadCarrito, "moneda":moneda, "tipo_cambio":tipo_cambio};
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
    $('#docIdentidad').keyup(function() {
        var ruc = $(this).val();
        
          //this.value = (this.value + '').replace(/[^0-9]/g, '');
            if(!$.isNumeric(ruc)) {                 
                //dni = dni.substr(0,(dni.length -1));
               ruc = ruc.replace(/[^0-9]/g, '');
                $('#docIdentidad').val(ruc);
            }
              
      });
    
      /*================================================================
                GUARDAR VENTA
    ===================================================================*/
    $('.btnGuardarVenta').on('click', function(){
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
                url: "ajax/ventas.ajax.php",
                data: dataForm,
            })
                .done(function(respuesta){                
                                         
            Swal.fire({
        title: 'El comprobante ha sido registrado corréctamente',
        text: "¡Gracias!",
        icon: 'success',
        html:
          '<div id="successCO"></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fa fa-print"></i> Imprimir A4'
      }).then((result) => {
        if (result.isConfirmed) {
            let idCo = $("#idCo").val();
         //(idCo);
          window.open("./vistas/print/print.php?id="+idCo, '_blank');
        
          // Swal.fire(
          //   'Deleted!',
          //   'Your file has been deleted.',
          //   'success'
          // )
        }
                
            })
              loadCarrito();
             $("#successCO").html(respuesta); 
            })
      
        }
        });

})
 
//DESCUENTO GLOBAL
$(document).on('keyup change', "#descuentoGlobal,#descuentoGlobalP", function(){
    let descontar = "descontar";
    let descuentoGlobal = $(this).val();
    let descuentoGlobalP = $("#descuentoGlobalP").val();
    let moneda = $("#moneda").val();
    let tipo_desc = $('input[name=tipo_desc]:checked').val(); 
    let tipo_cambio = $("#tipo_cambio").val();
    let datos = {"descuentoGlobal":descuentoGlobal, "descontar":descontar, "moneda":moneda, "tipo_desc":tipo_desc, "descuentoGlobalP":descuentoGlobalP, "tipo_cambio":tipo_cambio};
    $.ajax({
        method: "POST",
        url: "ajax/ventas.ajax.php",
        data: datos,
        //dataType: "json",
        success: function(respuesta){
           //(respuesta)

           $('.nuevoProducto table #itemsP').html(respuesta);
               
        }
    })
});

// CARGAR EL DESCUENTO 
 function LoadDescuento(){
    let descontar = "descontar";
    let descuentoGlobal = $(this).val();
    let moneda = $("#moneda").val();
    let tipo_desc = $('input[name=tipo_desc]:checked').val();
    let datos = {"descuentoGlobal":descuentoGlobal, "descontar":descontar, "moneda":moneda, "tipo_desc":tipo_desc};
    $.ajax({
        method: "POST",
        url: "ajax/ventas.ajax.php",
        data: datos,
        //dataType: "json",
        success: function(respuesta){
           //(respuesta)

           $('.nuevoProducto table #itemsP').html(respuesta);
               
        }
    })
};
// LISTAR VENTAS BOLETAS FACTURAS
function loadVentas(page){
    let searchVentas= $("#searchVentas").val();
    let selectnum= $("#selectnum").val();
    let parametros={"action":"ajax","page":page,"searchVentas":searchVentas,"selectnum":selectnum, "dv":"dv"};

$.ajax({
    url: 'vistas/tables/dataTables.php',
    // method: 'GET',
    data: parametros,  
    // cache: false,
    // contentType: false,
    // processData: false,  
    beforeSend: function(){
         $("#reload").show(50).html("<img src='vistas/img/reload.svg'> ");
    },   
    success:function(data){           
      
            $("#reload").hide();
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
        url: "controladores/tipo-cambio.php",
        method: "POST",
        data: dato,
        dataType: "json",
        success: function(datos){
            //(datos)
            $("#tipo_cambio").val(datos['venta']);

        }
    })
}
tipoCambio();

$("#moneda").change(function(){
  loadCarrito();
})