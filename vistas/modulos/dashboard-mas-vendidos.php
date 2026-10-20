<?php

use Controladores\ControladorProductos;

$item = null;
$valor = null;
$orden = 'ventas';
$productos = ControladorProductos::ctrMostrarProductosMasVendidos($item, $valor, $orden);
$sumaventas = 0;
foreach ($productos as $k => $v) {

  $sumaventas += $v['ventas'];
}

$colores = array('#0080f8','#f94144', '#90be6d', '#f9c74f', '#156b95',  '#43aa8b', '#ff5832', '#cd925a', 'orange', 'gold');
?>
<div class="box box-default contenedor-pie-chart">
  <div class="box-header with-border">
    <h3 class="box-title">LOS MÁS VENDIDOS</h3>

    <div class="box-tools pull-right">
      <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-md-7">
        <div class="chart-responsive">
          <canvas id="pieChart" height="210"></canvas>
        </div>
        <!-- ./chart-responsive -->
      </div>
      <!-- /.col -->
      <div class="col-md-5">
        <ul class="chart-legend clearfix">
          <?php

          for ($i = 0; $i < 5; $i++) {

            echo '<li><i class="fas fa-circle" style="color:' . $colores[$i] . '"></i> ' . $productos[$i]['descripcion'] . '</li>';
          }

          ?>

        </ul>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.box-body -->
  <div class="box-footer no-padding box pro-mas-v">
    <ul class="nav nav-pills nav-stacked">
      <?php

      for ($i = 0; $i < 5; $i++) {

        echo '<li>
                 <a href="#"><i class="fas fa-circle" style="color:' . $colores[$i] . '"></i> ' . $productos[$i]['descripcion'] . '
                  <span class="pull-right" style="color:' . $colores[$i] . '"><i class="fa fa-angle-down"></i> ' . $totales = ($productos[$i]['ventas']  > 0) ? ceil($productos[$i]['ventas'] * 100 / $sumaventas) . '%' : 0 . '%</span>
                  </a>
                  </li>';
      }

      ?>

    </ul>
  </div>
  <!-- /.footer -->
</div>
<!-- /.box -->


<script>
  $(document).ready(function() {
    // - PIE CHART -
    // -------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [

      <?php
      if ($sumaventas > 0) {
        for ($i = 0; $i < 5; $i++) {

          echo "{
      value    : " . $productos[$i]["ventas"] . ",
      color    : '" . $colores[$i] . "',
      highlight: '" . $colores[$i] . "',
      label    : 'Ventas " . ceil($productos[$i]['ventas'] * 100 / $sumaventas) . "%'
    },";
        }
      } else {
      ?> {
          value: 1,
          color: '#f86663ff',
          highlight: '#fe9393',
          label: 'Aún no hay productos o servicios vendidos'
        }
      <?php
      }

      ?>

    ];
    var pieOptions = {
      // Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      // String - The colour of each segment stroke
      segmentStrokeColor: '#fff',
      // Number - The width of each segment stroke
      segmentStrokeWidth: 1,
      // Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      // Number - Amount of animation steps
      animationSteps: 100,
      // String - Animation easing effect
      animationEasing: 'easeOutBounce',
      // Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      // Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      // Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: false,
      // String - A legend template
      legendTemplate: '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
      // String - A tooltip template
      tooltipTemplate: '<%=value %> <%=label%>'
    };
    // Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
    // -----------------
    // - END PIE CHART -
    // -----------------
  })
</script>