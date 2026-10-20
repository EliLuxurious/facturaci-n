document.getElementById("reload-all").style.display = 'none';
$(".reload-all").hide();
    //Date picker
   $('#fechaResumen').datepicker({
    autoclose: true,
    'language' : 'es'
  })
   $('#fechaInicial').datepicker({
    autoclose: true,
    'language' : 'es'
  })
   $('#fechaFinal').datepicker({
    autoclose: true,
    'language' : 'es'
  })
  
   $('#fechaDoc').datepicker({
    autoclose: true,
    'language' : 'es'
  })
  
   $('#fechaEmision').datepicker({
    autoclose: true,
    'language' : 'es'
  })
   $('#fechaInicialTraslado').datepicker({
    autoclose: true,
    'language' : 'es'
  })

      $('#fechaModificar').datepicker({
    autoclose: true,
    'language' : 'es'
    })
  
    $('#fecha_cuota').datepicker({
      autoclose: true,
      'language' : 'es'
      })
   
  
//MENU RÁPIDO

$(".btns-dash").load("vistas/modulos/menu-rapido.php");

//   $(".btns-dash").on('click','.btn-menur', function() {
      
//       $("#contenedor-menur").toggle(500);
// });





// INPUT MASK
 //Datemask dd/mm/yyyy
 $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
 //Datemask2 mm/dd/yyyy
 $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
 //Datemask2 yyyy/mm/dd
 $('#nuevaFechaNacimiento').inputmask('yyyy/mm/dd', { 'placeholder': 'yyyy/mm/dd' })
 //Money Euro
 $('[data-mask]').inputmask()

//iCheck for checkbox and radio inputs
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
  checkboxClass: 'icheckbox_minimal-blue',
  radioClass   : 'iradio_minimal-blue'
})

  $('.tablas').DataTable({
    "language": {

      "sProcessing":     "Procesando...",
      "sLengthMenu":     "Mostrar _MENU_",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "Buscar:",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
      "sFirst":    "Primero",
      "sLast":     "Último",
      "sNext":     "&rsaquo;",
			"sPrevious": "&lsaquo;"
      },
      "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
  
    }
  
  });
  
 //DONUT CHART DASHBOARD
//  var donut = new Morris.Donut({
//   element: 'sales-chart',
//   resize: true,
//   colors: ["#3c8dbc", "#f56954", "#00a65a"],
//   data: [
//     {label: "Download Sales", value: 12},
//     {label: "In-Store Sales", value: 30},
//     {label: "Mail-Order Sales", value: 20}
//   ],
//   hideHover: 'auto'
// });

  // $.widget.bridge('uibutton', $.ui.button);


$('.sidebar-menu').tree();

// To style only selects with the my-select class
// $('select').selectpicker();
    //DATA TABLE
    
      //     $('.tablas').DataTable({
      //   'paging'      : true,
      //   'lengthChange': false,
      //   'searching'   : false,
      //   'ordering'    : true,
      //   'info'        : true,
      //   'autoWidth'   : false
      // });

    
   
  
