// BOL: emision de boletas (tipo 03) a clientes con DNI.
const { test, expect } = require('@playwright/test');
const { leerTotal, elegirCliente, agregarProducto, guardarComprobante } = require('./helpers/venta');
const datos = require('./helpers/datos');

test('BOL-01 emite una boleta a un cliente con DNI', { tag: ['@critico', '@ventas'] }, async ({ page }) => {
  await page.goto('/crear-boleta');
  await expect(page.locator('#serie option:checked')).toHaveText(/^B/);

  await elegirCliente(page, '1', datos.clienteDni.dni);
  await expect(page.locator('#razon_social')).toHaveValue(datos.clienteDni.nombre);

  await agregarProducto(page, datos.producto.id);
  expect(await leerTotal(page, 'op-total')).toBe(datos.producto.precio);

  const { status, texto } = await guardarComprobante(page);
  expect(status).toBe(200);
  expect(texto).toContain('EL COMPROBANTE HA SIDO FIRMADO');
});
