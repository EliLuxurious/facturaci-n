// PRO: registro de productos.
const { test, expect } = require('@playwright/test');
const { buscar } = require('./helpers/buscar');

test('PRO-01 registra un producto nuevo y aparece en el almacén', { tag: ['@productos'] }, async ({ page }) => {
  const descripcion = `PRODUCTO PRUEBA ${Date.now().toString().slice(-6)}`;

  await page.goto('/productos');
  await page.click('button[data-target="#modalAgregarProducto"]');
  const modal = page.locator('#modalAgregarProducto');
  await expect(modal).toBeVisible();

  // Al elegir la categoria, el sistema genera el codigo automaticamente
  await modal.locator('#nuevaCategoria').selectOption({ index: 1 });
  await expect(modal.locator('#nuevoCodigo')).not.toHaveValue('');

  await modal.locator('#nuevaDescripcion').fill(descripcion);
  await modal.locator('#nuevoStock').fill('10');
  await modal.locator('#nuevoPrecioUnitario').pressSequentially('118'); // el calculo se activa al teclear
  // El valor sin IGV se calcula solo: 118 / 1.18 = 100
  await expect(modal.locator('#nuevoValorUnitario')).toHaveValue(/^100(\.0+)?$/);

  const respuesta = page.waitForResponse((r) => r.url().includes('ajax/productos.ajax.php') && r.request().method() === 'POST');
  await modal.getByRole('button', { name: 'Guardar producto' }).click();
  expect((await (await respuesta).text()).trim()).toBe('ok');

  // Buscar el producto en la tabla del almacen
  await page.goto('/productos');
  await buscar(page.locator('#searchProducto'), descripcion);
  await expect(page.locator('.body-productos')).toContainText(descripcion, { timeout: 10_000 });
});
