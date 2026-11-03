<?php
/* =============================================
   MAD-001 (aditivo): CUENTAS POR COBRAR
   Cuotas pendientes de ventas al credito, abonos y alertas de vencimiento.
============================================= */
use Controladores\ControladorCuentasCobrar;
?>
<div class="content-wrapper panel-medio-principal">
<?php if (!ControladorCuentasCobrar::ctrTieneAcceso()) { ?>
  <section class="container-fluid panel-medio">
    <div class="box alert-dangers text-center">
      <div><h3>Área restringida, solo el administrador o el contador pueden tener acceso</h3></div>
      <div class="img-restringido"></div>
    </div>
  </section>
<?php } else {
  $cuotas = ControladorCuentasCobrar::ctrCuotas();
  $porVencer = ControladorCuentasCobrar::ctrCuotasPorVencer($cuotas);
  $saldos = ControladorCuentasCobrar::ctrSaldosPorCliente($cuotas);
  $abonos = ControladorCuentasCobrar::ctrUltimosAbonos();
  $totalPendiente = array_sum(array_column($cuotas, 'saldo'));
?>
<div style="padding:5px"></div>
<section class="container-fluid">
  <section class="content-header dashboard-header">
    <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
      <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">
        <div class="col-md-4 hidden-sm hidden-xs">
          <button class=""><i class="fas fa-hand-holding-usd"></i> Cuentas por cobrar</button>
        </div>
        <div class="col-md-8 col-sm-12 btns-dash">
          <span class="pull-right" style="font-size:16px; padding:8px;">Total por cobrar: <b id="totalPorCobrar">S/ <?php echo number_format($totalPendiente, 2); ?></b></span>
        </div>
      </div>
    </div>
  </section>
</section>

<section class="container-fluid panel-medio">

  <?php if (!empty($porVencer)) { ?>
  <!-- RF03: cuotas que vencen en los proximos dias o ya vencidas -->
  <div class="alert alert-warning" id="alertaCuotas">
    <h4><i class="fas fa-bell"></i> Cuotas por vencer (próximos <?php echo ControladorCuentasCobrar::DIAS_AVISO; ?> días) o vencidas: <?php echo count($porVencer); ?></h4>
    <table class="table table-condensed" style="background:transparent; margin:0;">
      <thead><tr><th>Comprobante</th><th>Cliente</th><th>Cuota</th><th>Vence</th><th>Saldo</th></tr></thead>
      <tbody>
      <?php foreach ($porVencer as $c) { ?>
        <tr data-venta="<?php echo $c['id_venta']; ?>" data-cuota="<?php echo $c['nro']; ?>">
          <td><?php echo htmlspecialchars($c['comprobante']); ?></td>
          <td><?php echo htmlspecialchars($c['cliente']); ?></td>
          <td><?php echo $c['nro']; ?></td>
          <td><?php echo $c['vence']; ?> <?php echo $c['dias'] < 0 ? '<span class="label label-danger">Vencida</span>' : '<span class="label label-warning">en ' . $c['dias'] . ' días</span>'; ?></td>
          <td>S/ <?php echo number_format($c['saldo'], 2); ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <?php } ?>

  <!-- RF01: cuotas pendientes -->
  <div class="box rounded">
    <div class="box-header"><h3 class="box-title">Cuotas pendientes de cobro</h3></div>
    <div class="box-body table-responsive">
      <table class="table table-bordered table-striped" id="tablaCuotas" width="100%">
        <thead>
          <tr><th>Comprobante</th><th>Cliente</th><th>Cuota</th><th>Vence</th><th>Monto</th><th>Abonado</th><th>Saldo</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody>
        <?php if (empty($cuotas)) { ?>
          <tr><td colspan="9" class="text-center">No hay cuotas pendientes.</td></tr>
        <?php } ?>
        <?php foreach ($cuotas as $c) { ?>
          <tr data-venta="<?php echo $c['id_venta']; ?>" data-cuota="<?php echo $c['nro']; ?>">
            <td><?php echo htmlspecialchars($c['comprobante']); ?></td>
            <td><?php echo htmlspecialchars($c['cliente']); ?><br><small><?php echo htmlspecialchars($c['documento']); ?></small></td>
            <td><?php echo $c['nro']; ?></td>
            <td><?php echo $c['vence']; ?></td>
            <td class="monto"><?php echo number_format($c['monto'], 2); ?></td>
            <td class="abonado"><?php echo number_format($c['abonado'], 2); ?></td>
            <td class="saldo"><?php echo number_format($c['saldo'], 2); ?></td>
            <td><?php echo $c['estado'] == 'Vencida' ? '<span class="label label-danger">Vencida</span>' : '<span class="label label-info">Pendiente</span>'; ?></td>
            <td>
              <button class="btn btn-success btn-xs btnAbonar"
                      data-pago="<?php echo $c['id_pago_credito']; ?>" data-cuota="<?php echo $c['nro']; ?>"
                      data-saldo="<?php echo $c['saldo']; ?>"
                      data-descripcion="<?php echo htmlspecialchars($c['comprobante'] . ' - cuota ' . $c['nro'] . ' - ' . $c['cliente']); ?>">
                <i class="fas fa-money-bill-wave"></i> Registrar abono
              </button>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <!-- RF04: saldo por cliente -->
    <div class="col-md-6">
      <div class="box rounded">
        <div class="box-header"><h3 class="box-title">Saldo pendiente por cliente</h3></div>
        <div class="box-body table-responsive">
          <table class="table table-condensed" id="tablaSaldos">
            <thead><tr><th>Cliente</th><th>Cuotas</th><th>Saldo</th></tr></thead>
            <tbody>
            <?php foreach ($saldos as $s) { ?>
              <tr>
                <td><?php echo htmlspecialchars($s['cliente']); ?> <small>(<?php echo htmlspecialchars($s['documento']); ?>)</small></td>
                <td><?php echo $s['cuotas']; ?></td>
                <td class="saldo-cliente">S/ <?php echo number_format($s['saldo'], 2); ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- RNF03: historial de abonos con fecha, monto y usuario -->
    <div class="col-md-6">
      <div class="box rounded">
        <div class="box-header"><h3 class="box-title">Últimos abonos registrados</h3></div>
        <div class="box-body table-responsive">
          <table class="table table-condensed" id="tablaAbonos">
            <thead><tr><th>Fecha</th><th>Comprobante</th><th>Cuota</th><th>Monto</th><th>Registrado por</th></tr></thead>
            <tbody>
            <?php foreach ($abonos as $a) { ?>
              <tr>
                <td><?php echo date('d/m/Y H:i', strtotime($a['fecha'])); ?></td>
                <td><?php echo htmlspecialchars($a['serie_correlativo']); ?> <small class="text-muted">(venta <?php echo $a['id_venta']; ?>)</small></td>
                <td><?php echo $a['nro_cuota']; ?></td>
                <td>S/ <?php echo number_format($a['monto'], 2); ?></td>
                <td><?php echo htmlspecialchars($a['usuario']); ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php } ?>
</div>

<?php if (ControladorCuentasCobrar::ctrTieneAcceso()) { ?>
<!-- Fuera de .content-wrapper (como los demas modulos) para que el fondo del modal no lo tape -->
<!-- MODAL PARA REGISTRAR ABONO (RF02) -->
<div id="modalAbono" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#3c8dbc; color:white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Registrar abono</h4>
      </div>
      <div class="modal-body">
        <p id="descripcionCuota"></p>
        <p>Saldo pendiente: <b>S/ <span id="saldoCuota"></span></b></p>
        <input type="hidden" id="idPagoCredito">
        <input type="hidden" id="nroCuota">
        <div class="form-group">
          <label for="montoAbono">Monto del abono (S/)</label>
          <input type="number" class="form-control" id="montoAbono" min="0.01" step="0.01">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarAbono">Guardar abono</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

