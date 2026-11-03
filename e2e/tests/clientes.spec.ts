import { test, expect } from '@playwright/test';
import { idUnico, iniciarSesion } from './helpers/auth';

test.describe('Modulo de clientes', () => {
  test.beforeEach(async ({ page }) => {
    await iniciarSesion(page);
    await page.goto('/clientes');
    await expect(page.locator('.tabla-clientes')).toBeVisible();
    await expect(page.locator('.body-clientes tr').first()).toBeVisible({ timeout: 15_000 });
  });

  test('@critico registra un cliente nuevo', async ({ page }) => {
    const nombre = idUnico('CLIENTE E2E');
    const dni = String(Date.now()).slice(-8);

    await page.getByRole('button', { name: /Nuevo cliente/i }).click();
    await expect(page.locator('#modalAgregarCliente')).toBeVisible();
    await page.locator('#nuevoCliente').fill(nombre);
    await page.locator('#nuevoDni').fill(dni);
    await page.locator('#nuevoRuc').fill('');
    await page.locator('#nuevoRS').fill('');
    await page.locator('#nuevoEmail').fill(`e2e-${dni}@example.com`);
    await page.locator('#nuevoTelefono').fill('987654321');
    await page.locator('#nuevaDireccion').fill('Av. Universidad 123');
    await page.locator('#nuevaFechaNacimiento').click();
    await page.locator('#nuevaFechaNacimiento').pressSequentially('1990/01/01');
    await page.locator('#modalAgregarCliente button[type="submit"]').click();

    await expect(page.getByText(/guardado corr.ctamente/i)).toBeVisible({ timeout: 15_000 });
    await page.locator('#search').fill(nombre);
    await page.locator('#search').dispatchEvent('keyup');
    await expect(page.locator('.body-clientes')).toContainText(nombre, { timeout: 15_000 });
  });

  test('busca un cliente por documento', async ({ page }) => {
    await page.locator('#search').fill('20601487871');
    await page.locator('#search').dispatchEvent('keyup');
    await expect(page.locator('.body-clientes')).toContainText('20601487871', { timeout: 15_000 });
  });
});
