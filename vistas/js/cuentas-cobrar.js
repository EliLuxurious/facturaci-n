// MAD-001 (aditivo): modulo de cuentas por cobrar
$(document).on('click', '.btnAbonar', function () {
  $('#idPagoCredito').val($(this).data('pago'));
  $('#nroCuota').val($(this).data('cuota'));
  $('#saldoCuota').text(Number($(this).data('saldo')).toFixed(2));
  $('#descripcionCuota').text($(this).data('descripcion'));
  $('#montoAbono').val('');
  $('#modalAbono').modal('show');
});

$(document).on('click', '#btnGuardarAbono', function () {
  $.ajax({
    url: 'ajax/cuentas-cobrar.ajax.php',
    method: 'POST',
    dataType: 'json',
    data: {
      registrarAbono: 'ok',
      idPagoCredito: $('#idPagoCredito').val(),
      nroCuota: $('#nroCuota').val(),
      montoAbono: $('#montoAbono').val(),
    },
    success: function (respuesta) {
      $('#modalAbono').modal('hide');
      Swal.fire({
        icon: respuesta.ok ? 'success' : 'error',
        title: respuesta.ok ? 'Abono registrado' : 'No se registró el abono',
        text: respuesta.mensaje,
      }).then(function () {
        if (respuesta.ok) window.location = 'cuentas-por-cobrar';
      });
    },
  });
});
