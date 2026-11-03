import { test, expect } from '@playwright/test';
import { iniciarSesion } from './helpers/auth';

const RUTAS = [
  { ruta: 'usuarios', titulo: /USUARIOS/i },
  { ruta: 'categorias', titulo: /CATEGOR/i },
  { ruta: 'unidad-medida', titulo: /UNIDAD DE MEDIDA/i },
  { ruta: 'clientes', titulo: /Administraci.n de clientes/i },
  { ruta: 'productos', titulo: /Administraci.n de productos/i },
  { ruta: 'ventas', titulo: /Administraci.n de ventas/i },
  { ruta: 'nueva-compra', titulo: /NUEVA COMPRA/i },
  { ruta: 'crear-factura', titulo: /NUEVA FACTURA/i },
  { ruta: 'crear-boleta', titulo: /NUEVA BOLETA/i },
  { ruta: 'nota-credito', titulo: /NUEVA NOTA DE CR.DITO/i },
  { ruta: 'nota-debito', titulo: /NUEVA NOTA DE D.BITO/i },
  { ruta: 'resumen-diario', titulo: /RES.MEN DIARIO/i },
  { ruta: 'reporte-ventas', titulo: /REPORTE DE VENTAS/i },
  { ruta: 'reporte-compras', titulo: /REPORTE DE COMPRAS/i },
  { ruta: 'empresa', titulo: /CONFIGURACI.N DE EMPRESA/i },
];

test.describe('Navegacion de modulos', () => {
  test.beforeEach(async ({ page }) => {
    await iniciarSesion(page);
  });

  for (const { ruta, titulo } of RUTAS) {
    test(`carga el modulo ${ruta}`, async ({ page }) => {
      const respuesta = await page.goto(`/${ruta}`);
      expect(respuesta?.status()).toBe(200);
      await expect(page.locator('.content-wrapper')).toBeVisible();
      await expect(page.locator('.content-wrapper')).toContainText(titulo, { timeout: 15_000 });
      await expect(page.locator('body')).not.toContainText(/404|Page not found/i);
    });
  }
});
