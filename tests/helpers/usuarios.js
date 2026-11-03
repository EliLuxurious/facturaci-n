const { expect } = require('@playwright/test');
const { cerrarCargando } = require('./pagina');

// Usuario con perfil Vendedor que usan las pruebas de permisos
const vendedor = { usuario: 'vendedor1', password: 'vende123', nombre: 'Vendedor de Prueba', dni: '45678912' };

/** Crea el usuario vendedor desde el modulo Usuarios (solo si todavia no existe). */
async function asegurarVendedor(page) {
  await page.goto('/usuarios');
  if (!(await page.locator('table', { hasText: vendedor.usuario }).count())) {
    await crearVendedor(page);
    await page.goto('/usuarios');
  }
  await activarVendedor(page);
}

/** Los usuarios nuevos quedan desactivados: se activan con el interruptor "Estado". */
async function activarVendedor(page) {
  await cerrarCargando(page);
  const interruptor = page.locator('tr', { hasText: vendedor.usuario }).locator('.toggle').first();
  await expect(interruptor).toBeVisible();
  if (await interruptor.evaluate((el) => el.classList.contains('off'))) {
    const guardado = page.waitForResponse((r) => r.url().includes('ajax/usuarios.ajax.php'));
    await interruptor.click();
    await guardado;
  }
}

async function crearVendedor(page) {

  await cerrarCargando(page);
  await page.click('button[data-target="#modalAgregarUsuario"]');
  const modal = page.locator('#modalAgregarUsuario');
  // Al cambiar el DNI el sistema consulta RENIEC y reemplaza DNI y nombre con la respuesta.
  // Sin conexion a RENIEC (como en las pruebas) los deja vacios, asi que esperamos esa
  // respuesta y luego escribimos los datos sin volver a disparar la consulta.
  const consultaReniec = page.waitForResponse((r) => r.url().includes('ajax/usuarios.ajax.php'));
  await modal.locator('#nuevoDni').fill(vendedor.dni);
  await modal.locator('#nuevoDni').press('Tab'); // al salir del campo se dispara la consulta
  await consultaReniec;
  await modal.locator('#nuevoDni').evaluate((el, dni) => { el.value = dni; }, vendedor.dni);
  await modal.locator('#nuevoNombre').evaluate((el, nombre) => { el.value = nombre; }, vendedor.nombre);
  await modal.locator('#nuevoEmail').fill('vendedor1@correo.com');
  await modal.locator('#nuevoUsuario').fill(vendedor.usuario);
  await modal.locator('#nuevoPassword').fill(vendedor.password);
  await modal.locator('#nuevoPerfil').selectOption('Vendedor');
  await modal.locator('button[type="submit"]').click();
  await expect(page.locator('.swal2-popup')).toContainText(/guardado/i, { timeout: 10_000 });
}

/** Abre una sesion nueva (otro navegador) con el usuario vendedor. */
async function sesionVendedor(browser) {
  const contexto = await browser.newContext({ storageState: { cookies: [], origins: [] } });
  const page = await contexto.newPage();
  await page.goto('/');
  await page.fill('#ingUsuario', vendedor.usuario);
  await page.fill('#ingPassword', vendedor.password);
  await page.click('#logUser');
  await expect(page).toHaveURL(/\/inicio$/, { timeout: 15_000 });
  return { page, contexto };
}

module.exports = { vendedor, asegurarVendedor, sesionVendedor };
