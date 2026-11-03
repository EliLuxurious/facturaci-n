// VEN: el comprobante emitido debe aparecer en "Administración de ventas".
const { test, expect } = require('@playwright/test');
const { elegirCliente, agregarProducto, guardarComprobante } = require('./helpers/venta');
const datos = require('./helpers/datos');
const { buscar } = require('./helpers/buscar');

/** Devuelve el numero de la ultima factura listada, ej. "FACTURA-F002-4" -> 4 */
async function ultimoNumeroFactura(page) {
  await page.goto('/ventas');
  await page.waitForLoadState('networkidle');
  const fila = page.locator('.tablaVentas tbody tr', { hasText: 'FACTURA-F002-' }).first();
  await expect(fila).toBeVisible({ timeout: 10_000 });
  const texto = await fila.innerText();
  return Number(texto.match(/FACTURA-F002-(\d+)/)[1]);
}

test('VEN-01 la factura emitida aparece en el listado de ventas', { tag: ['@ventas'] }, async ({ page }) => {
  const antes = await ultimoNumeroFactura(page);

  await page.goto('/crear-factura');
  await elegirCliente(page, '6', datos.clienteRuc.ruc);
  await agregarProducto(page, datos.producto.id);
  const { texto } = await guardarComprobante(page);
  expect(texto).toContain('EL COMPROBANTE HA SIDO FIRMADO');
  await expect(page.locator('.swal2-popup')).toBeVisible(); // espera a que el sistema termine de procesar

  // La nueva factura debe tener el correlativo siguiente y los datos correctos
  await page.goto('/ventas');
  const fila = page.locator('.tablaVentas tbody tr', { hasText: `FACTURA-F002-${antes + 1}` });
  await expect(fila).toBeVisible({ timeout: 10_000 });
  await expect(fila).toContainText(datos.clienteRuc.razonSocial);
  await expect(fila).toContainText('S/ 63.00');
});

// ---------------------------------------------------------------
// MC-001 (correctivo): "El listado de ventas no se muestra correctamente
// al ingresar al modulo sin filtros".
// Estas pruebas reproducen el defecto y quedan en el suite para siempre.
// ---------------------------------------------------------------
test.describe('MC-001 listado de ventas sin filtros', () => {
  test('VEN-02 el listado responde aunque no se envíe ningún filtro', { tag: ['@ventas', '@MC-001', '@correctivo'] }, async ({ page }) => {
    // Es la misma consulta que hace la pantalla, pero sin buscador ni fechas
    const r = await page.request.get('/vistas/tables/dataTables.php', {
      params: { action: 'ajax', page: 1, selectnum: 5, dv: 'dv' },
    });
    expect(r.status(), 'el servidor no debe fallar (error 500)').toBe(200);
    expect(await r.text()).toContain('FACTURA-F002-');
  });

  test('VEN-03 el buscador del listado sigue filtrando', { tag: ['@ventas', '@MC-001', '@correctivo'] }, async ({ page }) => {
    await page.goto('/ventas');
    await expect(page.locator('.tablaVentas tbody tr').first()).toBeVisible({ timeout: 10_000 });

    await buscar(page.locator('#searchVentas'), 'B002');
    const filas = page.locator('.tablaVentas tbody tr', { hasText: /-\d+/ });
    await expect(filas.first()).toContainText('B002', { timeout: 10_000 });
    for (const texto of await filas.allInnerTexts()) {
      expect(texto, 'solo deben quedar boletas B002').not.toContain('F002');
    }
  });
});

test('VEN-04 el XML del comprobante se descarga desde el listado', { tag: ['@ventas'] }, async ({ page }) => {
  // Protege la descarga de XML: un cambio de seguridad (MPR-001) no debe bloquearla
  await page.goto('/ventas');
  const enlace = page.locator('.tablaVentas tbody tr a.xml').first();
  await expect(enlace).toBeAttached({ timeout: 10_000 });
  const r = await page.request.get(new URL(await enlace.getAttribute('href'), page.url()).toString());
  expect(r.status()).toBe(200);
  expect(await r.text()).toContain('<cbc:UBLVersionID>2.1</cbc:UBLVersionID>');
});
