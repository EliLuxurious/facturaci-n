// MPR-001 (preventivo): "Credenciales de acceso expuestas de forma insegura".
// Un visitante sin sesion no debe poder descargar archivos con credenciales,
// respaldos de la base de datos ni el certificado digital.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

test.use({ storageState: { cookies: [], origins: [] } });

const archivosProtegidos = [
  { id: 'SEG-01', ruta: '/.env', que: 'variables con las credenciales de la base de datos' },
  { id: 'SEG-02', ruta: '/BD/bdsistema.sql', que: 'respaldo completo de la base de datos' },
  { id: 'SEG-03', ruta: '/api/certificado/certificado_prueba.pfx', que: 'certificado digital para firmar comprobantes' },
  { id: 'SEG-04', ruta: '/.git/config', que: 'historial del repositorio' },
  // Agregadas tras el mutation testing: cada una depende de UNA sola regla del .htaccess
  { id: 'SEG-06', ruta: '/pdf/examples/data/cert/tcpdf.p12', que: 'certificado con clave privada (solo lo protege el bloqueo por extension)' },
  { id: 'SEG-07', ruta: '/docker/db/02_usuario_app.sh', que: 'script que crea el usuario de la base de datos (solo lo protege el bloqueo de carpetas)' },
];

test.describe('MPR-001 archivos sensibles no accesibles desde el navegador', () => {
  for (const archivo of archivosProtegidos) {
    test(`${archivo.id} no se puede descargar ${archivo.ruta}`, { tag: ['@seguridad', '@MPR-001', '@preventivo'] }, async ({ request }) => {
      const r = await request.get(archivo.ruta);
      expect([403, 404], `${archivo.ruta} (${archivo.que}) quedó expuesto`).toContain(r.status());
    });
  }

  test('SEG-05 el archivo de conexión no muestra credenciales', { tag: ['@seguridad', '@MPR-001', '@preventivo'] }, async ({ request }) => {
    const r = await request.get('/Conect/Conexion.php');
    const cuerpo = await r.text();
    expect(cuerpo).not.toMatch(/PASSWORD|DB_USER|root/i);
  });

  test('SEG-08 el código no trae credenciales escritas', { tag: ['@seguridad', '@MPR-001', '@preventivo'] }, async () => {
    // Revision estatica: una prueba E2E no puede ver una clave escrita en el codigo si el sistema funciona igual
    const codigo = fs.readFileSync(path.join(__dirname, '..', 'Conect', 'Conexion.php'), 'utf8');
    expect(codigo, 'DB_USER o DB_PASSWORD con un valor por defecto escrito en el codigo').not.toMatch(/config\('DB_(USER|PASSWORD)',\s*'[^']+'\)/);
    expect(codigo, 'constante con el usuario o la clave').not.toMatch(/const\s+(DB_)?(USER|PASS(WORD)?)\s*=\s*'[^']*'/i);
    expect(codigo, 'conexion PDO con usuario o clave literal').not.toMatch(/new\s+\\?PDO\([^;]*,\s*'[^']*'\s*,\s*'[^']*'\s*\)/);
  });
});
