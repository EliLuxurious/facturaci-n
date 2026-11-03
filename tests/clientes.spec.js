// CLI: registro de clientes.
// Se llenan solo los campos obligatorios del formulario (los que tienen asterisco
// o "required"); la fecha de nacimiento se deja vacia porque es opcional.
const { test, expect } = require('@playwright/test');
const { buscar } = require('./helpers/buscar');

test('CLI-01 registra un cliente nuevo con DNI y aparece en la lista', { tag: ['@clientes', '@MA-002', '@adaptativo'] }, async ({ page }) => {
  // Datos unicos en cada ejecucion para no chocar con clientes ya creados
  const sufijo = Date.now().toString().slice(-6);
  const nombre = `CLIENTE PRUEBA ${sufijo}`;
  const dni = `70${sufijo}`; // 8 digitos

  await page.goto('/clientes');
  await page.click('button[data-target="#modalAgregarCliente"]');
  const modal = page.locator('#modalAgregarCliente');
  await expect(modal).toBeVisible();

  await modal.locator('#nuevoCliente').fill(nombre);
  await modal.locator('#nuevoDni').fill(dni);
  await modal.locator('#nuevaDireccion').fill('Av. Universitaria 123, Tingo María');
  // Ojo: el formulario muestra el email como opcional, pero el servidor lo exige
  await modal.locator('#nuevoEmail').fill(`cliente${sufijo}@correo.com`);
  await modal.getByRole('button', { name: 'Guardar cliente' }).click();

  // Mensaje de confirmacion
  await expect(page.locator('.swal2-popup')).toContainText('El cliente ha sido guardado', { timeout: 10_000 });

  // Buscar el cliente en la tabla
  await page.goto('/clientes');
  await buscar(page.locator('#search'), dni);
  await expect(page.locator('.body-clientes')).toContainText(nombre, { timeout: 10_000 });
});
