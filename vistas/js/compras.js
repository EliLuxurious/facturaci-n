$(".p-productos").hide();
$("#formCompra").on("change", "#tipoComprobante", function(){
    let tipoComprobante = $(this).val();
    //(tipoComprobante);
    let data = {"tipoComprobante": tipoComprobante};
    $.ajax({
        method: "POST",
        url: "ajax/compras.ajax.php",
        data: data,
        beforeSend: function(){
    
        },
        success: function(respuesta) {
           $(".contenedor-notascd").html(respuesta);
        }
    })
});

$("#formItems").on("click", "#btnAddItem", function(e) {
e.preventDefault();
let dataForm = $("#formItems").serialize();

$.ajax({
    method: "POST",
    url: "ajax/compras.ajax.php",
    data: dataForm,
    beforeSend: function(){

    },
    success: function(respuesta) {
        //(respuesta);
        $('.nuevoProductoC table #itemsP').html(respuesta);
    }
})
})
    // ELIMINAR ITEM DEL CARRO
    $(".formCompra").on("click", "button.btnEliminarItemCarroC", function(){
        let idEliminarCarroC = $(this).attr("itemEliminar");
        //(idEliminarCarroC);
        let datos = {"idEliminarCarroC":idEliminarCarroC};
        $.ajax({
            method: "POST",
            url: "ajax/compras.ajax.php",
            data: datos,
            success: function(respuesta){
               
                $('.id-eliminar'+idEliminarCarroC).fadeOut(500, function(){    
                    $('.nuevoProductoC table #itemsP').html(respuesta);        
                             
                });
             //(respuesta);
                
            }
        })
    })
   // ELIMINAR TODOS LOS ITEMS DEL CARRO
   $(".formCompra").on("click", "button.btnEliminarCarro", function(){
    let eliminarCarro = "eliminarCarro";
    let datos = {"eliminarCarro":eliminarCarro};
    $.ajax({
        method: "POST",
        url: "ajax/compras.ajax.php",
        data: datos,
        success: function(respuesta){
            $('.nuevoProductoC table #itemsP').html('');
            $('.totales').html(`
            <tr class="">
            <td>SubTotal</td><td><input type="text" class="" name="subtotalc" id="subtotalc" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Op.Gravadas</td><td><input type="text" class="" name="op_gravadas" id="op_gravadas" value="0.00" /></td></tr>
             </tr>
            <tr class="">
            <td>Op.Exoneradas</td><td><input type="text" class="" name="op_exoneradas" id="op_exoneradas" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Op.Inafectas</td><td><input type="text" class="" name="op_inafectas" id="op_inafectas" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Op.gratuitas</td><td><input type="text" class="" name="op_gratuitas" id="op_gratuitas" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Descuento (-)</td><td><input type="text" class="" name="descuento" id="descuento"value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>ICBPER</td><td><input type="text" class="" name="icbper" id="icbper" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>IGV(18%)</td><td><input type="text" class="" name="igvc" id="igvc" value="0.00" /></td></tr>
            </tr>
            <tr class="">
            <td>Total</td><td><input type="text" class="" name="totalc" id="totalc" value="0.00" /></td></tr>
            </tr>
            
            
            `);
        }
    })
    })
    $(".formCompra").on("keyup", "#descuentoGlobalC", function(e) {
        let descuentoGlobalC = $(this).val();
        let data = {'descuentoGlobalC': descuentoGlobalC, 'descontarG': "descontarG"};
        $.ajax({
            method: "POST",
            url: "ajax/compras.ajax.php",
            data: data,
            beforeSend: function(){
        
            },
            success: function(respuesta) {
                //(data);
                $('.nuevoProductoC table #itemsP').html(respuesta);
            }
    })
    })
$(".formCompra").on("click", ".btnGuardarCompra", function(e) {
e.preventDefault();
let dataForm = $("#formCompra").serialize();
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
    url: "ajax/compras.ajax.php",
    data: dataForm,
    beforeSend: function(){

    },
    success: function(respuesta) {
        Swal.fire({
            title: '',
            text: '¡Gracias!',
            icon: 'success',
            html:
            '<div id="successCompra"></div>',
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cerrar',
        })
        $("#successCompra").html(respuesta);
    }
})
    }
})
})
$("#formItems").on('change', '#codigo', function(){
    let codigo = $(this).val();
    // //(codigo);

});

$(".tabla-reportes").on('click', ".btn-anular-compra", function(){
    let idCompra = $(this).attr('idCompra');
    let datos = {"idCompra" :idCompra};
    Swal.fire({
        title: '¿Estás seguro de anular este comprobante?',
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
        url: "ajax/compras.ajax.php",
        data: datos,
        beforeSend: function(){
    
        },
        success: function(respuesta) {
            if(respuesta == 'ok'){
            Swal.fire({
                title: '',
                text: '¡Comproante anulado corrréctamete!',
                icon: 'success',
                showCancelButton: true,
                showConfirmButton: false,
                allowOutsideClick: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cerrar',
            })
            loadReportesCompras(1);
        }else{
            Swal.fire({
                title: '',
                text: '¡No se ha podido anular el comprobante!',
                icon: 'error',
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
    }) 
        
})
// IMPRIMIR EN MODAL
$(".tabla-reportes").on("click",".btn-print-compra", function(e) {
    let idCompra = $(this).attr("idCompra");
    let datos = {"idCompra":idCompra};
    $.ajax({
        method: "POST",
        url: "vistas/print/printCompra.php",
        data: datos,        

        beforeSend: function(){

    },
    success: function(respuesta){
        $('.printerhere').html(respuesta);
    }
    })
})

$("#formItems").on("keyup", "#descripcion", function(e) {
    let buscarP = $(this).val();
    let datos =  {'buscarP':buscarP, 'buscarProducto': 'buscarProducto'};
    $.ajax({
        method: "POST",
        url: "ajax/compras.ajax.php",
        data: datos,
        beforeSend: function(){
    
        },
        success: function(respuesta) {
            $("#formItems .p-productos").fadeIn(300).html(respuesta);
        }

})
})
$(document).on("click", "#formItems .btn-add-item", function(e) {
    e.preventDefault();
    let idProducto = $(this).attr("idProducto");
    let datos = {'idProducto': idProducto};
    $.ajax({
        method: "POST",
        url: "ajax/compras.ajax.php",
        data: datos,
        dataType: "json",
        beforeSend: function(){
    
        },
        success: function(respuesta) {
          $("#formItems #idProductoc").val(respuesta['id']);
          $("#formItems #descripcion").val(respuesta['descripcion']);
          $("#formItems #codigo").val(respuesta['codigo']);
          $("#formItems #precio_unitario").val(respuesta['precio_unitario']);
          $("#formItems #valor_unitario").val(respuesta['valor_unitario']);
          changePriceCompra(respuesta['codigo']);
          $(".p-productos").hide();
        }

})
})


