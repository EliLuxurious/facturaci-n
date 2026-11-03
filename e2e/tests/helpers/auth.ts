import { expect, type Page } from '@playwright/test';

export const CREDENCIALES = {
  usuario: process.env.E2E_USER ?? 'demo',
  clave: process.env.E2E_PASS ?? '123456',
};

/**
 * Inicia sesion en el sistema. El login se hace por AJAX y redirige a /inicio.
 */
export async function iniciarSesion(page: Page, usuario = CREDENCIALES.usuario, clave = CREDENCIALES.clave) {
  await page.goto('/');
  await expect(page.locator('#ingUsuario')).toBeVisible();
  await page.locator('#ingUsuario').fill(usuario);
  await page.locator('#ingPassword').fill(clave);
  await page.locator('#logUser').click();
  await page.waitForURL(/\/inicio$/, { timeout: 20_000 });
  await expect(page.locator('.main-header')).toBeVisible();
}

/** Genera un identificador unico para no chocar con datos de ejecuciones anteriores. */
export function idUnico(prefijo: string): string {
  return `${prefijo} ${Date.now()} ${Math.floor(Math.random() * 1000)}`;
}
