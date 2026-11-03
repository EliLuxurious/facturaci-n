// MAD-001 (aditivo): "Sin modulo para controlar pagos a credito y cuotas pendientes".
// Pruebas nuevas para el modulo "Cuentas por cobrar" (requisitos RF01-RF04, RNF02 y RNF03)
// y para su punto de integracion con lo existente: la venta al credito.
const { test, expect } = require('@playwright/test');
const { elegirCliente, agregarProducto, guardarComprobante, configurarCredito, fechaSistema, diasDesdeHoy } = require('./helpers/venta');
const { asegurarVendedor, sesionVendedor } = require('./helpers/usuarios');
const { cerrarCargando } = require('./helpers/pagina');
const datos = require('./helpers/datos');

/** Emite una factura al credito y devuelve el id de la venta. */
async function facturaAlCredito(page, cuotas) {
  await page.goto('/crear-factura');
  await elegirCliente(page, '6', datos.clienteRuc.ruc);
  await agregarProducto(page, datos.producto.id); // S/ 63.00
  await configurarCredito(page, cuotas);
  const { texto } = await guardarComprobante(page);
  expect(texto).toContain('EL COMPROBANTE HA SIDO FIRMADO');
  return texto.match(/id='idCo' name='idCo' value='(\d+)'/)[1];
}

const fila = (page, idVenta, nroCuota) => page.locator(`#tablaCuotas tr[data-venta="${idVenta}"][data-cuota="${nroCuota}"]`);

test.describe('MAD-001 cuentas por cobrar', () => {
  test('CXC-01 las cuotas de una venta al crédito aparecen como pendientes', { tag: ['@cuentas', '@MAD-001', '@aditivo', '@ventas'] }, async ({ page }) => {
    const vence1 = diasDesdeHoy(20);
    const vence2 = diasDesdeHoy(50);
    const id = await facturaAlCredito(page, [{ fecha: vence1, monto: 30 }, { fecha: vence2, monto: 33 }]);

    await page.goto('/cuentas-por-cobrar');
    await expect(fila(page, id, 1)).toContainText(datos.clienteRuc.razonSocial); // RF01: cliente
    await expect(fila(page, id, 1)).toContainText(fechaSistema(vence1));         // RF01: vencimiento
    await expect(fila(page, id, 1)).toContainText('30.00');                       // RF01: monto
    await expect(fila(page, id, 2)).toContainText(fechaSistema(vence2));
    await expect(fila(page, id, 2)).toContainText('33.00');
  });

  test('CXC-02 registrar un abono reduce el saldo y queda registrado quién lo hizo', { tag: ['@cuentas', '@MAD-001', '@aditivo'] }, async ({ page }) => {
    const id = await facturaAlCredito(page, [{ fecha: diasDesdeHoy(30), monto: 63 }]);

    await page.goto('/cuentas-por-cobrar');
    await cerrarCargando(page);
    await fila(page, id, 1).locator('.btnAbonar').click();
    await page.locator('#montoAbono').fill('20');
    await page.locator('#btnGuardarAbono').click();
    await expect(page.locator('.swal2-popup')).toContainText('Abono registrado');

    await page.goto('/cuentas-por-cobrar');
    await expect(fila(page, id, 1).locator('.saldo')).toHaveText('43.00');         // RF02
    const abono = page.locator('#tablaAbonos tr', { hasText: `venta ${id}` }).first();
    await expect(abono).toContainText('20.00');                                     // RNF03: monto
    await expect(abono).toContainText('dacuna');                                    // RNF03: usuario
    await expect(abono).toContainText(fechaSistema(new Date()));                   // RNF03: fecha
  });

  test('CXC-03 no permite abonar más que el saldo pendiente', { tag: ['@cuentas', '@MAD-001', '@aditivo'] }, async ({ page }) => {
    const id = await facturaAlCredito(page, [{ fecha: diasDesdeHoy(30), monto: 63 }]);

    await page.goto('/cuentas-por-cobrar');
    await cerrarCargando(page);
    await fila(page, id, 1).locator('.btnAbonar').click();
    await page.locator('#montoAbono').fill('100');
    await page.locator('#btnGuardarAbono').click();
    await expect(page.locator('.swal2-popup')).toContainText('no puede ser mayor al saldo');

    await page.goto('/cuentas-por-cobrar');
    await expect(fila(page, id, 1).locator('.saldo')).toHaveText('63.00');
  });

  test('CXC-04 avisa las cuotas que vencen en los próximos días', { tag: ['@cuentas', '@MAD-001', '@aditivo'] }, async ({ page }) => {
    const pronto = diasDesdeHoy(3);
    const id = await facturaAlCredito(page, [{ fecha: pronto, monto: 30 }, { fecha: diasDesdeHoy(60), monto: 33 }]);

    await page.goto('/cuentas-por-cobrar');
    const alerta = page.locator('#alertaCuotas');
    await expect(alerta.locator(`tr[data-venta="${id}"][data-cuota="1"]`)).toContainText(fechaSistema(pronto)); // RF03
    await expect(alerta.locator(`tr[data-venta="${id}"][data-cuota="2"]`)).toHaveCount(0); // la de 60 dias no
  });

  test('CXC-05 muestra el saldo total pendiente por cliente', { tag: ['@cuentas', '@MAD-001', '@aditivo'] }, async ({ page }) => {
    await page.goto('/cuentas-por-cobrar');
    const saldoAntes = await leerSaldoCliente(page);

    await facturaAlCredito(page, [{ fecha: diasDesdeHoy(30), monto: 63 }]);
    await page.goto('/cuentas-por-cobrar');
    expect(await leerSaldoCliente(page)).toBeCloseTo(saldoAntes + 63, 2); // RF04
  });

  test('CXC-06 el vendedor no puede entrar al módulo', { tag: ['@cuentas', '@MAD-001', '@aditivo', '@permisos'] }, async ({ page, browser }) => {
    await asegurarVendedor(page);
    const { page: paginaVendedor, contexto } = await sesionVendedor(browser);
    await paginaVendedor.goto('/cuentas-por-cobrar');
    await expect(paginaVendedor.locator('.content-wrapper')).toContainText('Área restringida'); // RNF02
    await expect(paginaVendedor.locator('#tablaCuotas')).toHaveCount(0);
    await contexto.close();
  });
});

async function leerSaldoCliente(page) {
  const celda = page.locator('#tablaSaldos tr', { hasText: datos.clienteRuc.razonSocial }).locator('.saldo-cliente');
  if (!(await celda.count())) return 0;
  return Number((await celda.innerText()).replace(/[^0-9.]/g, ''));
}
