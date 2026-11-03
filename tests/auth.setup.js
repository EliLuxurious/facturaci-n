// Se ejecuta antes que todas las pruebas: inicia sesion una sola vez
// y guarda las cookies para reutilizarlas (asi cada prueba es mas rapida).
const { test: setup } = require('@playwright/test');
const { iniciarSesion, esperarPanel } = require('./helpers/login');
const datos = require('./helpers/datos');

setup('iniciar sesion con el usuario de prueba', async ({ page }) => {
  await iniciarSesion(page, datos.usuario, datos.password);
  await esperarPanel(page);
  await page.context().storageState({ path: 'playwright/.auth/usuario.json' });
});
