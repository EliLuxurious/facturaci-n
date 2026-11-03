#!/usr/bin/env node
/**
 * Falla si algun reporte JSON contiene pruebas inesperadas (regresiones).
 * Uso: node scripts/verificar.mjs resultados/tia.json resultados/critico.json
 */
import fs from 'node:fs';

const archivos = process.argv.slice(2);
let fallo = false;

for (const archivo of archivos) {
  if (!fs.existsSync(archivo)) {
    console.error(`No se genero el reporte: ${archivo}`);
    fallo = true;
    continue;
  }

  const { stats = {} } = JSON.parse(fs.readFileSync(archivo, 'utf8'));
  const inesperadas = stats.unexpected ?? 0;
  const inestables = stats.flaky ?? 0;
  console.log(`${archivo}: esperadas=${stats.expected ?? 0}, inesperadas=${inesperadas}, inestables=${inestables}`);

  if (inesperadas > 0) fallo = true;
}

process.exit(fallo ? 1 : 0);
