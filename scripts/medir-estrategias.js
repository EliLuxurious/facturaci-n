// Ejecuta una estrategia de regresion y registra sus metricas en resultados/mediciones.csv
//
// Uso:
//   node scripts/medir-estrategias.js <cambio> completa
//   node scripts/medir-estrategias.js <cambio> riesgo                 (--grep @critico)
//   node scripts/medir-estrategias.js <cambio> selectiva "@ventas"    (--grep por etiquetas del modulo)
//   node scripts/medir-estrategias.js <cambio> tia <commit-base>      (--only-changed)
//
// <cambio> es solo una etiqueta para la tabla, por ejemplo MC-001-antes o MC-001-despues.
const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const [cambio, estrategia, extra] = process.argv.slice(2);
if (!cambio || !estrategia) {
  console.error('Uso: node scripts/medir-estrategias.js <cambio> <completa|riesgo|selectiva|tia> [etiquetas|commit]');
  process.exit(1);
}

const args = ['playwright', 'test', '--reporter=json', '--pass-with-no-tests'];
if (estrategia === 'riesgo') args.push('--grep', '@critico');
if (estrategia === 'selectiva') args.push('--grep', extra);
if (estrategia === 'tia') args.push(`--only-changed=${extra}`);

const inicio = Date.now();
const r = spawnSync('npx', args, { encoding: 'utf8', shell: process.platform === 'win32', maxBuffer: 1 << 28 });
const duracion = (Date.now() - inicio) / 1000;

let reporte;
try {
  reporte = JSON.parse(r.stdout);
} catch (e) {
  console.error(r.stdout.slice(0, 2000), r.stderr.slice(0, 2000));
  process.exit(1);
}

// Recorre todas las pruebas del reporte (sin contar el paso de login "setup")
const pruebas = [];
(function recorrer(suites, archivo) {
  for (const s of suites || []) {
    for (const spec of s.specs || []) {
      for (const t of spec.tests || []) {
        if (t.projectName === 'setup') continue;
        const ultimo = t.results[t.results.length - 1] || {};
        pruebas.push({ titulo: spec.title, archivo: s.file || archivo, estado: ultimo.status || 'skipped', reintentos: t.results.length - 1 });
      }
    }
    recorrer(s.suites, s.file || archivo);
  }
})(reporte.suites);

const ejecutadas = pruebas.filter((p) => p.estado !== 'skipped');
const fallidas = ejecutadas.filter((p) => p.estado !== 'passed');
const fila = {
  fecha: new Date().toISOString(),
  cambio,
  estrategia,
  parametro: extra || '',
  ejecutadas: ejecutadas.length,
  pasaron: ejecutadas.length - fallidas.length,
  fallaron: fallidas.length,
  duracion_s: duracion.toFixed(1),
  pruebas_fallidas: fallidas.map((p) => p.titulo.split(' ')[0]).join(' '),
};

const dir = path.join(__dirname, '..', 'resultados');
fs.mkdirSync(dir, { recursive: true });
const csv = path.join(dir, 'mediciones.csv');
const columnas = Object.keys(fila);
if (!fs.existsSync(csv)) fs.writeFileSync(csv, columnas.join(';') + '\n');
fs.appendFileSync(csv, columnas.map((c) => fila[c]).join(';') + '\n');

console.log(JSON.stringify(fila));
