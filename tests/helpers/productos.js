const { expect } = require('@playwright/test');

/**
 * Registra un producto desde el modulo Productos y devuelve su descripcion.
 * stockMinimo es opcional: si no se envia, el sistema usa su valor por defecto.
 */
async function crearProducto(page, { descripcion, stock, precio = '118', stockMinimo }) {
  await page.goto('/productos');
  await page.click('button[data-target="#modalAgregarProducto"]');
  const modal = page.locator('#modalAgregarProducto');
  await expect(modal).toBeVisible();

  await modal.locator('#nuevaCategoria').selectOption({ index: 1 });
  await expect(modal.locator('#nuevoCodigo')).not.toHaveValue('');
  await modal.locator('#nuevaDescripcion').fill(descripcion);
  await modal.locator('#nuevoStock').fill(String(stock));
  if (stockMinimo !== undefined) await modal.locator('#nuevoStockMinimo').fill(String(stockMinimo));
  await modal.locator('#nuevoPrecioUnitario').pressSequentially(precio);
  await expect(modal.locator('#nuevoValorUnitario')).not.toHaveValue('');

  const respuesta = page.waitForResponse((r) => r.url().includes('ajax/productos.ajax.php') && r.request().method() === 'POST');
  await modal.getByRole('button', { name: 'Guardar producto' }).click();
  expect((await (await respuesta).text()).trim()).toBe('ok');
  return descripcion;
}

module.exports = { crearProducto };
