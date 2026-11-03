import { test, expect } from '@playwright/test';

test.describe('Disponibilidad HTTP', () => {
  test('@critico la pagina de acceso responde 200', async ({ request }) => {
    const respuesta = await request.get('/');
    expect(respuesta.status()).toBe(200);
    expect(await respuesta.text()).toContain('form-login');
  });

  test('protege las rutas privadas sin sesion', async ({ request }) => {
    const respuesta = await request.get('/clientes');
    expect(respuesta.status()).toBe(200);
    expect(await respuesta.text()).toContain('form-login');
  });

  test('sirve los recursos estaticos del tema', async ({ request }) => {
    const respuesta = await request.get('/vistas/pack/dist/css/AdminLTE.min.css');
    expect(respuesta.status()).toBe(200);
    expect((await respuesta.body()).length).toBeGreaterThan(1000);
  });
});
