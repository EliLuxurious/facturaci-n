import { test, expect } from '@playwright/test';
import { CREDENCIALES, iniciarSesion } from './helpers/auth';

test.describe('Autenticacion', () => {
  test('@critico inicia sesion con credenciales validas', async ({ page }) => {
    await iniciarSesion(page);
    await expect(page).toHaveURL(/\/inicio$/);
    await expect(page.locator('.main-header')).toContainText(/AWSPERUTIC|SISTEMA DE FACTURACION/i);
    await expect(page.locator('.content-wrapper')).toBeVisible();
  });

  test('@critico cierra sesion y vuelve al formulario de acceso', async ({ page }) => {
    await iniciarSesion(page);
    // El panel puede mostrar el overlay de carga #reload-all. Se espera un poco
    // y, si sigue visible, se cierra igual que lo haria el usuario.
    const overlay = page.locator('#reload-all');
    await overlay
      .waitFor({ state: 'hidden', timeout: 5_000 })
      .catch(async () => {
        await overlay.click({ force: true }).catch(() => {});
      });
    await page.locator('.user-menu > a').click({ force: true });
    await page.locator('.user-menu a[href="salir"]').click({ force: true });
    await page.waitForURL(/\/salir$/);
    await expect(page.locator('#ingUsuario')).toBeVisible();
  });

  test('no inicia sesion con campos vacios', async ({ page }) => {
    await page.goto('/');
    await page.locator('#logUser').click();
    await page.waitForTimeout(1000);
    await expect(page.locator('#ingUsuario')).toBeVisible();
    await expect(page).not.toHaveURL(/\/inicio$/);
  });

  test('DEF-01: no deberia aceptar una contrasena incorrecta', async ({ page }) => {
    test.fail(true, 'DEF-01: ctrIngresoUsuario no compara el hash de la contrasena. Ver anexo de mantenimiento correctivo.');
    await page.goto('/');
    await page.locator('#ingUsuario').fill(CREDENCIALES.usuario);
    await page.locator('#ingPassword').fill('claveincorrecta');
    await page.locator('#logUser').click();
    await page.waitForTimeout(2000);
    await expect(page.locator('#ingUsuario')).toBeVisible();
    await expect(page).not.toHaveURL(/\/inicio$/);
  });
});
