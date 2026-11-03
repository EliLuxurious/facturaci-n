// Prueba no funcional de MP-001 (complemento del perfectivo, Tabla 6):
// cuanto tarda en cargar el panel principal (/inicio) con la alerta de stock.
// Uso: node scripts/medir-carga-inicio.js <etiqueta> [repeticiones]
// Requiere haber corrido antes las pruebas (usa la sesion guardada en playwright/.auth).
const { chromium } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const etiqueta = process.argv[2] || 'sin-etiqueta';
const repeticiones = Number(process.argv[3] || 10);
const baseURL = process.env.BASE_URL || 'http://localhost:8080';

(async () => {
  const navegador = await chromium.launch();
  const contexto = await navegador.newContext({ storageState: 'playwright/.auth/usuario.json' });
  const page = await contexto.newPage();
  await page.goto(`${baseURL}/inicio`); // calentamiento
  const tiempos = [];
  for (let i = 0; i < repeticiones; i++) {
    const inicio = Date.now();
    const respuesta = await page.goto(`${baseURL}/inicio`, { waitUntil: 'domcontentloaded' });
    const servidor = Date.now() - inicio;
    const nav = await page.evaluate(() => performance.getEntriesByType('navigation')[0].toJSON());
    tiempos.push({ ttfb: Math.round(nav.responseStart - nav.requestStart), dom: Math.round(nav.domContentLoadedEventEnd), total: servidor, status: respuesta.status() });
  }
  await navegador.close();
  const mediana = (xs) => { const s = [...xs].sort((a, b) => a - b); const m = Math.floor(s.length / 2); return s.length % 2 ? s[m] : (s[m - 1] + s[m]) / 2; };
  const fila = { etiqueta, repeticiones, ttfb_ms: mediana(tiempos.map((t) => t.ttfb)), dom_ms: mediana(tiempos.map((t) => t.dom)) };
  const csv = path.join('resultados', 'carga-inicio.csv');
  if (!fs.existsSync(csv)) fs.writeFileSync(csv, 'fecha;etiqueta;repeticiones;ttfb_mediana_ms;domcontentloaded_mediana_ms\n');
  fs.appendFileSync(csv, `${new Date().toISOString()};${etiqueta};${repeticiones};${fila.ttfb_ms};${fila.dom_ms}\n`);
  console.log(JSON.stringify(fila));
})();
