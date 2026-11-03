import { test, expect } from '@playwright/test';
import { iniciarSesion } from './helpers/auth';

test.describe('Panel de control', () => {
  test.beforeEach(async ({ page }) => {
    await iniciarSesion(page);
  });

  test('@critico muestra los indicadores del dia', async ({ page }) => {
    await expect(page.locator('.content-wrapper')).toContainText(/DASHBOARD/i);
    await expect(page.locator('.content-wrapper')).toContainText(/FACTURAS/i);
    await expect(page.locator('.content-wrapper')).toContainText(/BOLETAS/i);
    await expect(page.locator('.content-wrapper')).toContainText(/TOTAL NETO/i);
  });

  test('@critico el acceso directo Emitir factura abre la pantalla de factura', async ({ page }) => {
    await page.locator('.dashboard-header .btns-dash a.btn-a[href="crear-factura"]').click();
    await expect(page).toHaveURL(/\/crear-factura$/);
    await expect(page.locator('.content-wrapper')).toContainText(/NUEVA FACTURA/i);
    await expect(page.locator('select')).not.toHaveCount(0);
  });

  test('el acceso directo Emitir boleta abre la pantalla de boleta', async ({ page }) => {
    await page.locator('.dashboard-header .btns-dash a[href="crear-boleta"]').click();
    await expect(page).toHaveURL(/\/crear-boleta$/);
    await expect(page.locator('.content-wrapper')).toContainText(/NUEVA BOLETA/i);
  });
});
