// MP-001 (perfectivo): "Sin alerta cuando el stock de un producto esta por agotarse".
// Pruebas nuevas (regresion progresiva): verifican los requisitos RF01-RF04 y RNF02 del ticket.
const { test, expect } = require('@playwright/test');
const { crearProducto } = require('./helpers/productos');
const { asegurarVendedor, sesionVendedor } = require('./helpers/usuarios');
const { elegirCliente, guardarComprobante } = require('./helpers/venta');
const datos = require('./helpers/datos');
const { buscar } = require('./helpers/buscar');

const sufijo = () => Date.now().toString().slice(-6);
const alerta = (page) => page.locator('#alertaStockBajo');

test.describe('MP-001 alerta de stock mínimo', () => {
  test('STK-01 un producto en stock crítico aparece en la alerta del panel', { tag: ['@stock', '@MP-001', '@perfectivo'] }, async ({ page }) => {
    const descripcion = await crearProducto(page, { descripcion: `STOCK BAJO ${sufijo()}`, stock: 2, stockMinimo: 5 });

    await page.goto('/inicio');
    await expect(alerta(page)).toBeVisible();
    const fila = alerta(page).locator('tr', { hasText: descripcion });
    await expect(fila).toBeVisible();
    await expect(fila).toContainText('2'); // stock actual (RF03)
    await expect(fila).toContainText('5'); // minimo configurado (RF02)
  });

  test('STK-02 un producto con stock suficiente no aparece en la alerta', { tag: ['@stock', '@MP-001', '@perfectivo'] }, async ({ page }) => {
    const descripcion = await crearProducto(page, { descripcion: `STOCK OK ${sufijo()}`, stock: 50, stockMinimo: 5 });
    await page.goto('/inicio');
    await expect(alerta(page)).not.toContainText(descripcion);
  });

  test('STK-03 sin mínimo configurado se usa el valor por defecto (5)', { tag: ['@stock', '@MP-001', '@perfectivo'] }, async ({ page }) => {
    const descripcion = await crearProducto(page, { descripcion: `SIN MINIMO ${sufijo()}`, stock: 3 });
    await page.goto('/inicio');
    await expect(alerta(page).locator('tr', { hasText: descripcion })).toContainText('5');
  });

  test('STK-04 la alerta se actualiza después de una venta', { tag: ['@stock', '@MP-001', '@perfectivo', '@ventas'] }, async ({ page }) => {
    // Stock 6 con minimo 5: todavia no es critico
    const descripcion = await crearProducto(page, { descripcion: `VENTA STOCK ${sufijo()}`, stock: 6, stockMinimo: 5 });
    await page.goto('/inicio');
    await expect(alerta(page)).not.toContainText(descripcion);

    // Vender 2 unidades -> queda en 4 (RF04)
    await page.goto('/crear-factura');
    await elegirCliente(page, '6', datos.clienteRuc.ruc);
    await page.click('.btn-agregar-carrito');
    await buscar(page.locator('#searchProductoV'), descripcion);
    const boton = page.locator(`button.agregarProducto[descripcionP="${descripcion}"]`).first();
    await expect(boton).toBeVisible({ timeout: 10_000 });
    const id = await boton.getAttribute('idProducto');
    await page.locator(`#cantidad${id}`).first().fill('2');
    await boton.click();
    await expect(page.locator('#itemsP tr').first()).toBeVisible();
    await page.keyboard.press('Escape');
    const { texto } = await guardarComprobante(page);
    expect(texto).toContain('EL COMPROBANTE HA SIDO FIRMADO');

    await page.goto('/inicio');
    await expect(alerta(page).locator('tr', { hasText: descripcion })).toContainText('4');
  });

  test('STK-05 el vendedor no ve la alerta de stock', { tag: ['@stock', '@MP-001', '@perfectivo', '@permisos'] }, async ({ page, browser }) => {
    await crearProducto(page, { descripcion: `STOCK VENDEDOR ${sufijo()}`, stock: 1, stockMinimo: 5 });
    await asegurarVendedor(page);

    const { page: paginaVendedor, contexto } = await sesionVendedor(browser);
    await expect(paginaVendedor.locator('.main-sidebar')).toBeVisible();
    await expect(paginaVendedor.locator('#alertaStockBajo')).toHaveCount(0); // RNF02
    await contexto.close();
  });
});
