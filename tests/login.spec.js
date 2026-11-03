// AUT: pruebas del inicio de sesion.
// Estas pruebas empiezan SIN sesion iniciada.
const { test, expect } = require('@playwright/test');
const { iniciarSesion, esperarPanel } = require('./helpers/login');
const datos = require('./helpers/datos');

test.use({ storageState: { cookies: [], origins: [] } });

test.describe('Login', () => {
  test('AUT-01 ingresa con usuario y contraseña correctos', { tag: ['@critico', '@login'] }, async ({ page }) => {
    await iniciarSesion(page, datos.usuario, datos.password);
    await esperarPanel(page);
  });

  test('AUT-02 rechaza una contraseña incorrecta', { tag: ['@critico', '@login', '@MC-004', '@correctivo'] }, async ({ page }) => {
    await iniciarSesion(page, datos.usuario, 'claveIncorrecta999');

    // Debe mostrar el mensaje de error...
    await expect(page.locator('#resultLogin')).toContainText('Error al ingresar', { timeout: 10_000 });
    // ...y NO debe entrar al sistema
    await page.waitForTimeout(3_000);
    await expect(page).not.toHaveURL(/\/inicio$/);
    await expect(page.locator('#ingUsuario')).toBeVisible();
  });

  test('AUT-03 rechaza un usuario que no existe', { tag: ['@login', '@MC-004', '@correctivo'] }, async ({ page }) => {
    await iniciarSesion(page, 'usuarioInexistente', datos.password);
    await page.waitForTimeout(3_000);
    await expect(page).not.toHaveURL(/\/inicio$/);
    await expect(page.locator('#ingUsuario')).toBeVisible();
  });

  test('AUT-04 al cerrar sesión ya no se puede abrir una página interna', { tag: ['@login'] }, async ({ page }) => {
    await iniciarSesion(page, datos.usuario, datos.password);
    await esperarPanel(page);

    await page.goto('/salir');
    await page.goto('/ventas');

    // Sin sesion, el sistema debe mostrar el login en lugar de las ventas
    await expect(page.locator('#ingUsuario')).toBeVisible();
    await expect(page.locator('.main-sidebar')).toHaveCount(0);
  });
});
