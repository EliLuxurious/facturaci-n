#!/usr/bin/env node
/**
 * Calcula la mediana de duracion y de pruebas ejecutadas a partir de varios
 * reportes JSON de Playwright.
 *
 * Uso:
 *   node scripts/medir.mjs resultados/mediciones/completa-*.json
 *   node scripts/medir.mjs resultados/mediciones/completa-*.json resultados/mediciones/critico-*.json
 *
 * Si se pasan varios grupos separados por "--", calcula la reduccion del
 * segundo grupo respecto del primero:
 *   node scripts/medir.mjs completa-*.json -- critico-*.json
 */
import fs from 'node:fs';
import path from 'node:path';

const args = process.argv.slice(2);
if (args.length === 0) {
  console.error('Uso: node scripts/medir.mjs <reporte.json>... [-- <reporte.json>...]');
  process.exit(1);
}

const grupos = [[]];
for (const arg of args) {
  if (arg === '--') grupos.push([]);
  else grupos[grupos.length - 1].push(arg);
}

function mediana(valores) {
  const orden = [...valores].sort((a, b) => a - b);
  const mitad = Math.floor(orden.length / 2);
  return orden.length % 2 === 0
    ? (orden[mitad - 1] + orden[mitad]) / 2
    : orden[mitad];
}

function leerGrupo(archivos) {
  const filas = [];
  for (const archivo of archivos) {
    if (!fs.existsSync(archivo)) continue;
    const { stats = {} } = JSON.parse(fs.readFileSync(archivo, 'utf8'));
    const total = (stats.expected ?? 0) + (stats.unexpected ?? 0) + (stats.flaky ?? 0) + (stats.skipped ?? 0);
    filas.push({
      archivo: path.basename(archivo),
      pruebas: total,
      segundos: (stats.duration ?? 0) / 1000,
      fallidas: stats.unexpected ?? 0,
    });
  }
  if (filas.length === 0) return null;
  const tiempos = filas.map((f) => f.segundos);
  return {
    n: filas.length,
    pruebasMediana: mediana(filas.map((f) => f.pruebas)),
    tiempoMediana: mediana(tiempos),
    tiempoMin: Math.min(...tiempos),
    tiempoMax: Math.max(...tiempos),
    fallidas: filas.reduce((s, f) => s + f.fallidas, 0),
  };
}

const resumenes = grupos.map(leerGrupo);
const lineas = [
  '| Grupo | Ejecuciones | Pruebas (mediana) | Tiempo mediana (s) | Min (s) | Max (s) | Fallidas |',
  '|---|---:|---:|---:|---:|---:|---:|',
];

resumenes.forEach((r, i) => {
  if (!r) {
    lineas.push(`| Grupo ${i + 1} | 0 | - | - | - | - | - |`);
    return;
  }
  lineas.push(
    `| Grupo ${i + 1} | ${r.n} | ${r.pruebasMediana} | ${r.tiempoMediana.toFixed(2)} | ${r.tiempoMin.toFixed(2)} | ${r.tiempoMax.toFixed(2)} | ${r.fallidas} |`
  );
});

if (resumenes[0] && resumenes[1]) {
  const reduccionTiempo = (1 - resumenes[1].tiempoMediana / resumenes[0].tiempoMediana) * 100;
  const reduccionPruebas = (1 - resumenes[1].pruebasMediana / resumenes[0].pruebasMediana) * 100;
  lineas.push(
    '',
    `Reduccion del grupo 2 respecto del grupo 1: ${reduccionPruebas.toFixed(1)} % en pruebas y ${reduccionTiempo.toFixed(1)} % en tiempo.`
  );
}

console.log(lineas.join('\n'));
