// NAV: prueba de humo (smoke). Abre cada pagina del menu y verifica que cargue
// sin errores de PHP. Detecta, por ejemplo, un include roto o un error de sintaxis.
const { test, expect } = require('@playwright/test');

const rutas = [
  'inicio', 'usuarios', 'categorias', 'productos', 'clientes', 'ventas',
  'crear-factura', 'crear-boleta', 'crear-nota', 'nota-credito', 'nota-debito',
  'crear-cotizacion', 'listar-cotizaciones', 'crear-guia', 'ver-guias',
  'nueva-compra', 'reporte-ventas', 'reporte-compras', 'resumen-diario',
  'consulta-comprobante', 'unidad-medida', 'empresa',
];

const erroresPhp = /Fatal error|Parse error|Warning<\/b>:|Notice<\/b>:|Uncaught/;

test.describe('NAV-01 cada página del menú carga sin errores', () => {
  for (const ruta of rutas) {
    test(`abre /${ruta}`, { tag: ['@navegacion'] }, async ({ page }) => {
      const respuesta = await page.goto(`/${ruta}`);

      expect(respuesta.status(), 'el servidor responde 200').toBe(200);
      expect(await page.content(), 'la página no muestra errores de PHP').not.toMatch(erroresPhp);
      await expect(page.locator('.main-sidebar'), 'la sesión sigue activa').toBeVisible();
      await expect(page.locator('.error-page'), 'no es la página 404').toHaveCount(0);
    });
  }
});

test('NAV-02 una ruta que no existe muestra la página 404', { tag: ['@navegacion'] }, async ({ page }) => {
  await page.goto('/pagina-que-no-existe');
  await expect(page.locator('.error-page .headline')).toHaveText('404');
});
