# Suite de regresion E2E - Sistema de Facturacion

Suite de pruebas de extremo a extremo construida con Playwright para el sistema
PHP 8.1 + MariaDB del repositorio. Cubre autenticacion, panel de control,
navegacion por modulos, clientes, productos y pantallas de emision.

## Ejecucion rapida

Con la aplicacion levantada en `http://localhost:8080`:

```bash
npm ci
npx playwright install --with-deps chromium
npm test
```

Con Docker (no requiere Node en el host):

```bash
docker compose --profile test run --rm e2e
docker compose --profile test run --rm e2e sh -c "npm ci --no-audit --no-fund && npx playwright test --grep @critico"
```

## Comandos

| Comando | Que ejecuta |
|---|---|
| `npm test` | Suite completa en Chromium. |
| `npm run test:critico` | Solo pruebas etiquetadas `@critico` (riesgo). |
| `npm run test:changed` | TIA respecto de `origin/main` (CI). |
| `npm run test:changed:local` | TIA respecto de `HEAD` (trabajo local). |
| `npm run report` | Abre el reporte HTML. |
| `npm run resumen -- archivo.json` | Tabla Markdown de un reporte JSON. |
| `npm run medir -- a.json b.json -- c.json` | Medianas y reduccion entre grupos. |
| `npm run verificar -- a.json` | Falla si hay pruebas inesperadas. |

## Estructura

- `tests/helpers/auth.ts`: login reutilizable.
- `tests/auth.spec.ts`: acceso, cierre de sesion y defecto DEF-01.
- `tests/dashboard.spec.ts`: indicadores y accesos directos.
- `tests/navegacion.spec.ts`: humo de los modulos.
- `tests/clientes.spec.ts`: alta y busqueda de clientes.
- `tests/productos.spec.ts`: alta y busqueda de productos.
- `tests/ventas.spec.ts`: pantallas de boleta y factura.
- `tests/http.spec.ts`: disponibilidad y proteccion de rutas.
- `scripts/`: resumen, medicion y verificacion de reportes.
- `cambios/regresion-simulada.patch`: cambio que rompe el acceso directo a factura.

## Variables de entorno

| Variable | Valor por defecto | Uso |
|---|---|---|
| `BASE_URL` | `http://localhost:8080` | URL de la aplicacion. |
| `E2E_USER` | `demo` | Usuario de la suite. |
| `E2E_PASS` | `123456` | Clave del usuario. |
| `BROWSERS` | `chromium` | `all` agrega Firefox y WebKit. |
| `CI` | vacio | `true` activa reintentos y 2 workers. |
