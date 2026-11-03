import { test, expect } from '@playwright/test';
import { idUnico, iniciarSesion } from './helpers/auth';

test.describe('Modulo de productos', () => {
  test.beforeEach(async ({ page }) => {
    await iniciarSesion(page);
    await page.goto('/productos');
    await expect(page.locator('.tablaProductos')).toBeVisible();
    await expect(page.locator('.body-productos tr').first()).toBeVisible({ timeout: 15_000 });
  });

  test('@critico registra un producto nuevo', async ({ page }) => {
    const descripcion = idUnico('PRODUCTO E2E');

    await page.getByRole('button', { name: /Nuevo producto o servicio/i }).click();
    await expect(page.locator('#modalAgregarProducto')).toBeVisible();

    const categoria = page.locator('#nuevaCategoria');
    const opciones = await categoria.locator('option').count();
    expect(opciones).toBeGreaterThan(1);
    await categoria.selectOption({ index: 1 });
    await expect(page.locator('#nuevoCodigo')).not.toHaveValue('');

    await page.locator('#nuevaSerie').fill('E2E');
    await page.locator('#nuevaDescripcion').fill(descripcion);
    await page.locator('#nuevoStock').fill('10');
    await page.locator('#nuevoPrecioUnitario').pressSequentially('118');
    await expect(page.locator('#nuevoValorUnitario')).not.toHaveValue('', { timeout: 10_000 });
    await expect(page.locator('#nuevoPrecioCompra')).not.toHaveValue('', { timeout: 10_000 });

    await page.locator('#formProductos button[type="submit"]').click();
    await expect(page.getByText(/AGREGADO CORR.CTAMENTE/i)).toBeVisible({ timeout: 15_000 });

    await page.locator('#searchProducto').fill(descripcion);
    await page.locator('#searchProducto').dispatchEvent('keyup');
    await expect(page.locator('.body-productos')).toContainText(descripcion, { timeout: 15_000 });
  });

  test('busca un producto existente', async ({ page }) => {
    await page.locator('#searchProducto').fill('Laptop Lenovo i8');
    await page.locator('#searchProducto').dispatchEvent('keyup');
    await expect(page.locator('.body-productos')).toContainText(/Laptop Lenovo i8/i, { timeout: 15_000 });
  });
});
