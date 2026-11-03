import { expect, type Page } from '@playwright/test';

export const CREDENCIALES = {
  usuario: process.env.E2E_USER ?? 'demo',
  clave: process.env.E2E_PASS ?? '123456',
};

/**
 * Oculta el overlay global de carga (.reload-all). Varios modulos lo muestran
 * con fadeIn y, si una peticion de fondo falla, queda visible e intercepta los
 * clics. No forma parte de lo que se valida en las pruebas.
 */
export async function neutralizarOverlayCarga(page: Page) {
  await page.addInitScript(() => {
    const inyectar = () => {
      const estilo = document.createElement('style');
      estilo.textContent = '.reload-all{display:none!important;pointer-events:none!important}';
      document.head.appendChild(estilo);
    };

    if (document.head) {
      inyectar();
    } else {
      document.addEventListener('DOMContentLoaded', inyectar, { once: true });
    }
  });
}

/**
 * Inicia sesion en el sistema. El login se hace por AJAX y redirige a /inicio.
 */
export async function iniciarSesion(page: Page, usuario = CREDENCIALES.usuario, clave = CREDENCIALES.clave) {
  await neutralizarOverlayCarga(page);
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
