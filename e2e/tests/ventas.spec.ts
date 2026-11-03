import { test, expect } from '@playwright/test';
import { iniciarSesion } from './helpers/auth';

test.describe('Emision de comprobantes', () => {
  test.beforeEach(async ({ page }) => {
    await iniciarSesion(page);
  });

  test('@critico la pantalla de boleta permite elegir cliente y moneda', async ({ page }) => {
    await page.goto('/crear-boleta');
    await expect(page.locator('.content-wrapper')).toContainText(/NUEVA BOLETA/i);
    await expect(page.locator('#serie')).toBeVisible();
    await expect(page.locator('#moneda')).toBeVisible();
    await expect(page.locator('#tipoDoc')).toBeVisible();
    await expect(page.locator('#docIdentidad')).toBeVisible();
  });

  test('la pantalla de factura carga sus datos maestros', async ({ page }) => {
    await page.goto('/crear-factura');
    await expect(page.locator('.content-wrapper')).toContainText(/NUEVA FACTURA/i);
    await expect(page.locator('#serie')).toBeVisible();
    await expect(page.locator('#tipoDoc')).toBeVisible();
    await expect(page.locator('#docIdentidad')).toBeVisible();
  });
});
