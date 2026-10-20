$(".contenedor-cuotas").hide();
var pluss = 1;
$(document).on('change','#tipopago', function(){
    let tipopago = $(this).val();
    if(tipopago == 'Credito'){
        $(".contenedor-cuotas").show();
        $(".cuotas-float").show();
    }else{
        $(".contenedor-cuotas").hide();
        $('.contenedor-cuotas input').val('');
        $('.items-cuotas').remove();
        $('#numcuotas').val(1);
        // document.getElementById("numcuotas").value = '1';
    }
})
$(document).on('click', '.salir-cuotas', function(e){
    e.preventDefault();
   $(".cuotas-float").hide();
   $(".contenedor-cuotas .mostrar-cuotas").remove();
   $(".contenedor-cuotas").append(`
   <button class="btn btn-success btn-xs mostrar-cuotas">Mostrar cuotas</button>  
   `);
})
$(document).on('click', '.mostrar-cuotas', function(e){
    e.preventDefault();
   $(".cuotas-float").show();

})
$(document).on('change', '#numcuotas', function(){
    var numcuotas = $(this).val();  
     $('.items-cuotas').remove();
    for (var i = 2; i <= numcuotas; i++) {
    
    $('.pago-cuotas').append(`
    <div class="items-cuotas pago-cuotas${i}">
        <div class="form-group">                             
        <input type="text" class="form-control" id="fecha_cuota${i}" name="fecha_cuota[]" placeholder="Fecha cuota ${i}">
        
        </div>
         <div class="form-group" style="margin-top: -12px;">
             <input type="number" class="form-control" id="cuotas${i}" name="cuotas[]" placeholder="Monto cuota ${i}">
        </div>                              
        </div>   
    `).promise()
    } 
    $('.contenedor-cuotas #fecha_cuota2').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota3').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota4').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota5').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota6').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota7').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota8').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota9').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
       $('.contenedor-cuotas #fecha_cuota10').datepicker({
            autoclose: true,
            'language' : 'es'
            
       }) 
})
// $(document).on('click', '.add-cuotas', function(){
//     pluss++;
//     $('.pago-cuotas').append(`
//     <div class="items-c pago-cuotas${pluss}">
//         <div class="form-group">                             
//         <input type="text" class="form-control" id="fecha_cuota${pluss}" name="fecha_cuota[]" placeholder="Fecha cuota ${pluss}">
        
//         </div>
//          <div class="form-group" style="margin-top: -12px;">
//              <input type="number" class="form-control" id="cuotas" name="cuotas[]" placeholder="Monto cuota ${pluss}">
//         </div>                              
//         </div>   
//     `).promise()
   
      
    
// }) 
$(document).on('keyup', '#cuotas',function(){
   var totalOperacion = $("#totalOperacion").val();
   var numcuotas = $("#numcuotas").val();
   var cuota = $(this).val();

   var restacuota = totalOperacion - cuota;   
   
   if(numcuotas == 1){
        $('#cuotas').val(parseFloat(totalOperacion).toFixed(2));
    }
    if(numcuotas == 2){
        var totales = parseFloat(Math.round((restacuota / 1) * 100)/100).toFixed(2);
        $('#cuotas2').val(totales);
    }
    // if(numcuotas == 3){
    //     var totales =  parseFloat(Math.round((restacuota / 2) * 100)/100).toFixed(2);
    //     $('#cuotas2').val(totales);
    //     $('#cuotas3').val(totales);
       
    // }
    // if(numcuotas == 4){
    //     var totales = parseFloat(Math.round((restacuota / 3) * 100)/100).toFixed(2);
    //     $('#cuotas2').val(totales);
    //     $('#cuotas3').val(totales);
    //     $('#cuotas4').val(totales);
    // }
    // if(numcuotas == 5){
    //     var totales = parseFloat(Math.round((restacuota / 4) * 100)/100).toFixed(2);
    //     $('#cuotas2').val(totales);
    //     $('#cuotas3').val(totales);
    //     $('#cuotas4').val(totales);
    //     $('#cuotas5').val(totales);
    // }
    // if(numcuotas == 6){
    //     var totales = parseFloat(Math.round((restacuota / 5) * 100)/100).toFixed(2);
    //     $('#cuotas2').val(totales);
    //     $('#cuotas3').val(totales);
    //     $('#cuotas4').val(totales);
    //     $('#cuotas5').val(totales);
    //     $('#cuotas6').val(totales);
    // }
})