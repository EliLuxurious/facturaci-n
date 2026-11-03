// FAC: emision de facturas (tipo 01). Es el flujo de mayor riesgo del sistema:
// un error aqui afecta montos, impuestos (IGV) y documentos que van a SUNAT.
const { test, expect } = require('@playwright/test');
const { leerTotal, elegirCliente, agregarProducto, guardarComprobante } = require('./helpers/venta');
const datos = require('./helpers/datos');

test.describe('Factura electrónica', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/crear-factura');
  });

  test('FAC-01 emite una factura y calcula bien el IGV', { tag: ['@critico', '@ventas'] }, async ({ page }) => {
    await elegirCliente(page, '6', datos.clienteRuc.ruc);
    await expect(page.locator('#razon_social')).toHaveValue(datos.clienteRuc.razonSocial);

    await agregarProducto(page, datos.producto.id);

    // Verificar los montos del RESUMEN. El precio ya incluye IGV (18 %):
    // valor de venta = 63.00 / 1.18 = 53.39  ->  IGV = 63.00 - 53.39 = 9.61
    const total = await leerTotal(page, 'op-total');
    const gravadas = await leerTotal(page, 'op-gravadas');
    const igv = await leerTotal(page, 'op-igv');

    expect(total).toBe(datos.producto.precio);
    expect(gravadas).toBeCloseTo(total / 1.18, 2);
    expect(igv).toBeCloseTo(total - gravadas, 2);

    const { status, texto } = await guardarComprobante(page);
    expect(status, 'el servidor no debe fallar al guardar').toBe(200);
    expect(texto).toContain('EL COMPROBANTE HA SIDO FIRMADO');
  });

  test('FAC-02 no permite emitir una factura sin cliente', { tag: ['@critico', '@ventas'] }, async ({ page }) => {
    await agregarProducto(page, datos.producto.id);

    const { texto } = await guardarComprobante(page);
    expect(texto).not.toContain('EL COMPROBANTE HA SIDO FIRMADO');
    // El sistema debe avisar al usuario que falta el RUC
    await expect(page.locator('.swal2-popup')).toContainText('Debes ingresar el número de R.U.C.');
  });
});
