import { defineConfig, devices, type Project } from '@playwright/test';

/**
 * Configuracion de la suite de regresion E2E.
 *
 * Variables de entorno:
 *   BASE_URL   URL de la aplicacion (por defecto http://localhost:8080).
 *   CI         "true" activa reintentos y limita los workers.
 *   BROWSERS   "all" agrega Firefox y WebKit (se usa en la ejecucion nocturna).
 *   E2E_USER   usuario de la suite (por defecto demo).
 *   E2E_PASS   clave del usuario (por defecto 123456).
 */
const proyectos: Project[] = [
  { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
];

if (process.env.BROWSERS === 'all') {
  proyectos.push(
    { name: 'firefox', use: { ...devices['Desktop Firefox'] } },
    { name: 'webkit', use: { ...devices['Desktop Safari'] } },
  );
}

export default defineConfig({
  testDir: './tests',
  outputDir: './test-results',
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  workers: process.env.CI ? 2 : undefined,
  timeout: 30_000,
  expect: { timeout: 10_000 },
  reporter: [
    ['list'],
    ['html', { outputFolder: 'playwright-report', open: 'never' }],
    ['json', { outputFile: process.env.PLAYWRIGHT_JSON_OUTPUT_NAME || 'resultados/resultados.json' }],
  ],
  use: {
    baseURL: process.env.BASE_URL || 'http://localhost:8080',
    locale: 'es-PE',
    timezoneId: 'America/Lima',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
  projects: proyectos,
});
