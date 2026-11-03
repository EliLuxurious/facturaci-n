const { expect } = require('@playwright/test');

/** Llena el formulario de login y hace clic en "Ingresar al sistema". */
async function iniciarSesion(page, usuario, password) {
  await page.goto('/');
  await page.fill('#ingUsuario', usuario);
  await page.fill('#ingPassword', password);
  await page.click('#logUser');
}

/** Verifica que el usuario esta dentro del sistema (se ve el menu lateral). */
async function esperarPanel(page) {
  await expect(page).toHaveURL(/\/inicio$/, { timeout: 15_000 });
  await expect(page.locator('.main-sidebar')).toBeVisible();
}

module.exports = { iniciarSesion, esperarPanel };
