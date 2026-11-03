// @ts-check
// Configuracion de Playwright para el sistema de facturacion.
// Documentacion: https://playwright.dev/docs/test-configuration
const { defineConfig, devices } = require('@playwright/test');

module.exports = defineConfig({
  // Carpeta donde estan las pruebas
  testDir: './tests',

  // Las pruebas comparten la misma base de datos (correlativos de facturas),
  // por eso se ejecutan una por una para que no se pisen entre si.
  fullyParallel: false,
  workers: 1,

  // En GitHub Actions se reintenta 1 vez para detectar pruebas inestables (flaky)
  retries: process.env.CI ? 1 : 0,

  // Prohibe dejar un test.only olvidado en el pipeline
  forbidOnly: !!process.env.CI,

  // Reportes: lista en consola + reporte HTML (playwright-report/)
  reporter: [['list'], ['html', { open: 'never' }]],

  use: {
    // Direccion del sistema. Se puede cambiar con la variable BASE_URL
    baseURL: process.env.BASE_URL || 'http://localhost:8080',
    // Evidencias solo cuando algo falla
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    trace: 'retain-on-failure',
  },

  projects: [
    // 1) Inicia sesion una vez y guarda la sesion en playwright/.auth/usuario.json
    { name: 'setup', testMatch: /auth\.setup\.js/ },

    // 2) Ejecuta las pruebas ya con la sesion iniciada
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        storageState: 'playwright/.auth/usuario.json',
      },
      dependencies: ['setup'],
    },
  ],
});
