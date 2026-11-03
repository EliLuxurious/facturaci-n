#!/usr/bin/env node
/**
 * Convierte los reportes JSON de Playwright en una tabla Markdown.
 * Uso: node scripts/resumen.mjs resultados/completa.json resultados/tia.json
 */
import fs from 'node:fs';
import path from 'node:path';

const archivos = process.argv.slice(2);
if (archivos.length === 0) {
  console.error('Uso: node scripts/resumen.mjs <reporte.json> [...]');
  process.exit(1);
}

const filas = [];
for (const archivo of archivos) {
  if (!fs.existsSync(archivo)) {
    filas.push(`| ${path.basename(archivo)} | - | - | - | - | - | no generado |`);
    continue;
  }
  const reporte = JSON.parse(fs.readFileSync(archivo, 'utf8'));
  const s = reporte.stats ?? {};
  const segundos = ((s.duration ?? 0) / 1000).toFixed(2);
  const total = (s.expected ?? 0) + (s.unexpected ?? 0) + (s.flaky ?? 0) + (s.skipped ?? 0);
  filas.push(
    `| ${path.basename(archivo)} | ${total} | ${s.expected ?? 0} | ${s.unexpected ?? 0} | ${s.flaky ?? 0} | ${s.skipped ?? 0} | ${segundos} |`
  );
}

const salida = [
  '### Resumen de la suite de regresion',
  '',
  '| Reporte | Pruebas | Esperadas | Fallidas | Inestables | Omitidas | Duracion (s) |',
  '|---|---:|---:|---:|---:|---:|---:|',
  ...filas,
  '',
];
console.log(salida.join('\n'));
