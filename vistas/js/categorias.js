$(document).ready(function(){

// EDITAR CATEGORIA
$(document).on('click','.btnEditarCategoria', function (){
    
    let idCategoria = $(this).attr('idCategoria');
    let datos = new FormData();
    datos.append('idCategoria', idCategoria);
    $.ajax({
        url: "ajax/categorias.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(respuesta){

            $("#editarCategoria").val(respuesta['categoria']);
            $("#idCategoria").val(respuesta['id']);
        }
    })
});
// ELIMINAR CATEGORIA
$(document).on('click','.btnEliminarCategoria', function (){
    let idEliminar = $(this).attr('idCategoria');
    let datos = new FormData();
    datos.append('idEliminar', idEliminar);

    Swal.fire({
        title: '¿Estás seguro de eliminar esta categoría?',
        text: "¡Si no lo está puede  cancelar la acción!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!'
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/categorias.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function(respuesta){
                    //(respuesta);
                   if(respuesta == 'success') {
                    Swal.fire({
                        title: '¡La categoría ha sido eliminada!',
                        text: '...',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        // window.location = 'categorias';
                        $('.id-cat'+idEliminar).fadeOut(1000, function(){
                            window.location = 'categorias';
                        });
                        
                   }
                })
            
            }else{
                Swal.fire({
                    title: '¡La categoría no se puede eliminar porque tiene productos!',
                    text: '...',
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                }).then((result) => {
                    if (result.isConfirmed) {
                    window.location = 'categorias';
                    }
                })
            }
            }
           
                
})
        }
})
})
// VALIDAR NO REPETIR USUARIO
$(document).on('change', "#nuevaCategoria", function(){
    $('.alert').remove();

    let categoria = $(this).val();
    let datos = new FormData();
        datos.append('validarCategoria', categoria);
    $.ajax({
        url: "ajax/categorias.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(respuesta){
           // //("respuesta", respuesta);
           if(respuesta){
            $("#nuevaCategoria").val('');
            $("#nuevaCategoria").parent().before('<div class="alert alert-warning" style="display:none;">Esta categoría ya existe!</div>');
            $('.alert').show(500,function(){
                $(this).delay(3000).hide(500);
            });
            
           }
        }
    })
})

})
