const { expect } = require('@playwright/test');

/**
 * Cierra el aviso de "cargando" (#reload-all) si se quedo abierto.
 * Varias consultas globales del sistema lo muestran al cargar cualquier pagina y, si
 * responden con error 500, nunca lo ocultan; el overlay tapa los botones hasta que el
 * usuario hace clic sobre el (asi esta programado en plantilla.php).
 */
async function cerrarCargando(page) {
  await page.waitForLoadState('networkidle');
  const cargando = page.locator('#reload-all');
  if (await cargando.isVisible()) await cargando.click();
  await expect(cargando).toBeHidden();
}

module.exports = { cerrarCargando };
