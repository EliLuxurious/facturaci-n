# Pruebas de regresión con Playwright

## Requisitos
- Node.js 18 o superior (`node -v`)
- El sistema funcionando en `http://localhost:8080` (con `docker compose up -d app db`)
- Usuario de prueba: `demo` / `123456` (viene en `BD/bdsistema.sql`)

## Preparar el sistema (solo la primera vez o después de actualizar)
1. Copiar `.env.example` como `.env`. El sistema ya **no** trae la clave de la base de datos
   escrita en el código (MPR-001): la lee de `.env` o de las variables del contenedor.
2. Si la base de datos ya existía antes de estos cambios, borrar el volumen para que se
   vuelvan a ejecutar los scripts de `docker/db/` y `BD/migraciones/`:
   ```bash
   docker compose down -v
   docker compose up -d --build app db
   ```
   O, si no quieren perder datos, aplicar las migraciones a mano desde phpMyAdmin
   (`http://localhost:8081`) en este orden:
   - `BD/migraciones/001_stock_minimo.sql` (MP-001)
   - `BD/migraciones/002_abonos_cuotas.sql` (MAD-001)
   - y crear el usuario de la aplicación como hace `docker/db/02_usuario_app.sh`.

## Instalar las pruebas (solo la primera vez)
```bash
npm install
npx playwright install chromium
```

## Ejecutar
| Comando | Qué hace | Estrategia |
|---|---|---|
| `npm test` | Ejecuta todas las pruebas | Regresión completa |
| `npm run test:critico` | Solo las pruebas marcadas `@critico` | Basada en riesgos |
| `npx playwright test --grep "@ventas\|@cuentas"` | Solo las pruebas de los módulos tocados | Selectiva por etiquetas |
| `npm run test:cambios` | Solo las pruebas cuyos archivos cambiaron respecto de `origin/main` | TIA (`--only-changed`) |
| `npm run test:ui` | Abre la ventana visual para ver cada paso | — |
| `npm run test:visible` | Ejecuta las pruebas con el navegador a la vista | — |
| `npm run test:debug` | Ejecuta paso a paso (botón "Step over") | — |
| `npm run reporte` | Abre el reporte HTML de la última ejecución | — |

Si el sistema está en otra dirección (por ejemplo XAMPP en `http://localhost/facturacion`):
```bash
# Windows PowerShell
$env:BASE_URL="http://localhost/facturacion"; npm test
```

## Medir las estrategias (para la Tabla 9)
```bash
npm run medir -- <cambio> <completa|riesgo|selectiva|tia> [etiquetas|commit]
# ejemplos
npm run medir -- MC-001-antes completa
npm run medir -- MC-001-antes selectiva "@ventas"
npm run medir -- MC-001-antes tia HEAD~1
```
Cada ejecución agrega una fila a `resultados/mediciones.csv` (separado por `;`, se abre con Excel):
pruebas ejecutadas, pasaron, fallaron, duración y qué pruebas fallaron.

## Etiquetas
| Etiqueta | Significado |
|---|---|
| `@critico` | Flujos de mayor riesgo (login y emisión de comprobantes) |
| `@correctivo` `@adaptativo` `@perfectivo` `@preventivo` `@aditivo` | Tipo de mantenimiento al que pertenece la prueba |
| `@MC-001` `@MC-004` `@MA-002` `@MP-001` `@MPR-001` `@MAD-001` | Ticket del informe que la prueba verifica |
| `@ventas` `@clientes` `@login` `@stock` `@cuentas` `@seguridad` `@permisos` | Módulo del sistema |

## Archivos
| Archivo | Casos |
|---|---|
| `auth.setup.js` | Inicia sesión una vez y guarda la sesión |
| `login.spec.js` | AUT-01 a AUT-04 (MC-004) |
| `navegacion.spec.js` | NAV-01 (22 páginas del menú), NAV-02 |
| `clientes.spec.js` | CLI-01 (MA-002) |
| `productos.spec.js` | PRO-01 |
| `factura.spec.js` | FAC-01, FAC-02 |
| `boleta.spec.js` | BOL-01 |
| `ventas.spec.js` | VEN-01 a VEN-04 (MC-001) |
| `seguridad.spec.js` | SEG-01 a SEG-05 (MPR-001) |
| `stock.spec.js` | STK-01 a STK-05 (MP-001) |
| `cuentas-cobrar.spec.js` | CXC-01 a CXC-06 (MAD-001) |
| `helpers/` | Funciones reutilizables (login, venta, búsquedas, usuarios, productos) y datos de prueba |

Los comprobantes se guardan con la opción **"Solo Firmar e Imprimir"**: nunca se envían a SUNAT.
