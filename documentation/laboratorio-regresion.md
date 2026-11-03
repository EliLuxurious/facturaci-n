# Laboratorio guiado: suite de regresión automatizada y pipeline CI/CD

**Sistema:** Facturación Electrónica SUNAT (PHP 8.1 + MariaDB + AdminLTE)  
**Repositorio base:** <https://github.com/EliLuxurious/facturaci-n>  
**Asignatura:** Mantenimiento de Software  
**Tema:** Estrategias de pruebas de regresión en el mantenimiento evolutivo  
**Herramientas:** Playwright 1.64, Docker Compose, GitHub Actions  
**Duración estimada:** 120–150 minutos  
**Modalidad:** individual o en parejas  
**Entregable:** repositorio con la suite y el pipeline + informe de evidencias

---

## Índice

1. [Resultados de aprendizaje](#1-resultados-de-aprendizaje)
2. [Caso de estudio y alcance](#2-caso-de-estudio-y-alcance)
3. [Requisitos previos](#3-requisitos-previos)
4. [Parte 1: descargar y ejecutar el sistema](#4-parte-1-descargar-y-ejecutar-el-sistema)
5. [Parte 2: ejecutar la suite de regresión](#5-parte-2-ejecutar-la-suite-de-regresión)
6. [Parte 3: aplicar las estrategias de regresión](#6-parte-3-aplicar-las-estrategias-de-regresión)
7. [Parte 4: pipeline de CI/CD en GitHub Actions](#7-parte-4-pipeline-de-cicd-en-github-actions)
8. [Parte 5: métricas y evidencias](#8-parte-5-métricas-y-evidencias)
9. [Parte 6: entregables y rúbrica](#9-parte-6-entregables-y-rúbrica)
10. [Anexos](#10-anexos)

---

## 1. Resultados de aprendizaje

Al finalizar el laboratorio, el estudiante será capaz de:

1. Levantar un sistema legado PHP/MariaDB de forma reproducible con Docker Compose.
2. Diseñar y ejecutar una suite de regresión E2E con Playwright, organizada por módulos y etiquetas de riesgo.
3. Aplicar al menos dos estrategias de regresión: **completa**, **selectiva/TIA** y **basada en riesgos**.
4. Provocar una regresión controlada y comprobar qué estrategia la detecta y cuál la deja pasar.
5. Integrar la suite en un pipeline de CI/CD por etapas con GitHub Actions.
6. Medir el tiempo de feedback, la reducción de pruebas y la inclusividad de cada estrategia.

---

## 2. Caso de estudio y alcance

### 2.1 El sistema

El sistema es una aplicación de facturación electrónica para Perú (UBL 2.1) con los módulos de:

- Panel de control e indicadores.
- Emisión de facturas, boletas, notas de venta, notas de crédito y notas de débito.
- Guías de remisión, cotizaciones, compras y proveedores.
- Clientes, productos, categorías y unidades de medida.
- Reportes, resumen diario y configuración de la empresa emisora.

La arquitectura es MVC en PHP 8.1, con MariaDB 10.6, Apache y vistas AdminLTE. La aplicación se ejecuta en tres contenedores: `app`, `db` y `phpmyadmin`. La suite de pruebas se ejecuta en un cuarto contenedor opcional, `e2e`, para no exigir Node.js en el equipo del estudiante.

### 2.2 ¿Por qué pruebas E2E?

Las pruebas de extremo a extremo recorren la aplicación como lo haría un usuario: abren el navegador, escriben en los formularios, esperan las peticiones AJAX y verifican el resultado. Son las más costosas de ejecutar, pero también las que mejor cubren los flujos completos de facturación, donde un cambio en una plantilla o en un controlador puede romper una pantalla que las pruebas unitarias no ven.

### 2.3 El cambio que se va a simular

El laboratorio incluye el archivo:

```text
e2e/cambios/regresion-simulada.patch
```

Ese parche modifica un solo enlace del menú rápido del panel de control, en
`vistas/modulos/menu-rapido.php`:

```diff
-<a href="crear-factura" class="btn-a" ...>Emitir factura</a>
+<a href="crear-factura-rota" class="btn-a" ...>Emitir factura</a>
```

El menú rápido se inserta con jQuery en el contenedor `.btns-dash` del panel
(`vistas/js/plantilla.js`), por lo que ese enlace es el que el usuario ve y
pulsa. Se trata de un cambio **perfectivo** (una modificación de la interfaz
posterior a la entrega) que introduce una **regresión**: el acceso directo a la
emisión de facturas deja de funcionar. El resto del sistema sigue operativo, de
modo que solo una prueba que recorra ese flujo detectará la falla.

### 2.4 Estrategias que se aplicarán

| Estrategia | Comando | Qué ejecuta |
|---|---|---|
| Completa (*retest-all*) | `npx playwright test` | Toda la suite, en cada cambio. |
| Selectiva / TIA | `--only-changed=origin/main` | Solo los archivos de prueba impactados por el cambio. |
| Basada en riesgos | `--grep @critico` | Solo los flujos críticos etiquetados. |

La comparación permitirá comprobar una conclusión del informe: **TIA reduce el tiempo, pero en E2E es ciego a los cambios del backend PHP**; por eso debe complementarse con la suite crítica.

### 2.5 Composición de la suite

| Archivo | Pruebas | `@critico` | Propósito |
|---|---:|---:|---|
| `tests/auth.spec.ts` | 4 | 2 | Login, logout, campos vacíos y defecto DEF-01. |
| `tests/dashboard.spec.ts` | 3 | 2 | Indicadores y accesos directos del panel. |
| `tests/navegacion.spec.ts` | 15 | 0 | Humo de todos los módulos. |
| `tests/clientes.spec.ts` | 2 | 1 | Alta y búsqueda de clientes. |
| `tests/productos.spec.ts` | 2 | 1 | Alta y búsqueda de productos. |
| `tests/ventas.spec.ts` | 2 | 1 | Pantallas de boleta y factura. |
| `tests/http.spec.ts` | 3 | 1 | Disponibilidad y protección de rutas. |
| **Total** | **31** | **8** | |

> La prueba `DEF-01` está marcada con `test.fail()`: documenta un defecto real y se reporta como *fallo esperado*. No debe confundirse con una prueba roja del pipeline. El anexo B explica cómo corregirlo.

---

## 3. Requisitos previos

### 3.1 Software

| Herramienta | Versión mínima | Verificación |
|---|---|---|
| Git | 2.30 | `git --version` |
| Docker Engine / Docker Desktop | 24 | `docker --version` |
| Docker Compose | v2 | `docker compose version` |
| Node.js (recomendado para métricas) | 22 | `node -v` |
| npm (incluido con Node.js) | 10 | `npm -v` |
| Cuenta de GitHub | — | <https://github.com> |
| GitHub CLI (opcional) | 2.40 | `gh --version` |

> **Ruta con Docker:** la suite completa y la crítica se pueden ejecutar con el contenedor `e2e`, que ya trae Playwright y los navegadores preinstalados. Para las mediciones (`scripts/medir.mjs`), los escenarios TIA y la regresión simulada se recomienda Node.js en el host; si no lo tienes, ejecuta esos comandos dentro del mismo contenedor (el repositorio está montado en `/repo`). La ruta con Node.js también es la más cómoda para abrir el reporte HTML.
>
> **Node.js para métricas:** las mediciones de la Parte 3 y los escenarios TIA se ejecutan con Node.js en el host. Si usas el contenedor `e2e`, recuerda que este trabaja con 2 workers y reintentos de CI (`CI=true`), por lo que los tiempos no serán comparables con la tabla de referencia. Para la exposición se recomienda la ruta con Node.js.

### 3.2 Puertos y recursos

- `8080`: aplicación.
- `8081`: phpMyAdmin.
- `3306`: MariaDB (opcional, para inspección).
- 4 GB de RAM libres y 5 GB de disco.

Si alguno de esos puertos está ocupado, cambie los valores en `.env` antes de levantar el sistema:

```bash
APP_PORT=8090
PMA_PORT=8091
DB_PORT=3307
```

En ese caso, use `http://localhost:8090` en todos los comandos y en `BASE_URL`.

### 3.3 Verificación inicial

```bash
git --version
docker --version
docker compose version
node -v   # opcional
```

Si `docker compose version` falla, actualice Docker Desktop o instale el plugin `docker-compose-plugin`.

---

## 4. Parte 1: descargar y ejecutar el sistema

### 4.1 Clonar el repositorio

```bash
git clone https://github.com/EliLuxurious/facturaci-n.git
cd facturaci-n

# Si el laboratorio se distribuye en una rama, cámbiala:
# git checkout lab/regresion
```

> **Nota para el docente:** el paquete del laboratorio incluye las carpetas `e2e/`, `scripts/` y `.github/workflows/`. Si trabajas desde una copia del repositorio que no las tiene, cambia a la rama del laboratorio (`git branch -a`) o cópialas antes de continuar. Verifica con `ls e2e .github/workflows`.
>
> Si `ls e2e` falla, no continúes con la Parte 2: primero necesitas la suite.

### 4.2 Crear el archivo de entorno

```bash
cp .env.example .env
```

Contenido esperado de `.env`:

```dotenv
APP_PORT=8080
PMA_PORT=8081
DB_PORT=3306
DB_HOST=db
DB_NAME=dbfacturacion
DB_USER=root
DB_PASSWORD=root
```

### 4.3 Construir y levantar los contenedores

```bash
docker compose up -d --build
```

La primera construcción tarda entre 2 y 5 minutos porque instala las extensiones PHP (`pdo_mysql`, `soap`, `zip`, `gd`, `bcmath`). Las siguientes son mucho más rápidas.

Verifique el estado:

```bash
docker compose ps
```

Salida esperada (los nombres pueden variar):

```text
NAME                IMAGE                              STATUS
facturacion_app     facturaci-n-app                    Up
facturacion_db      mariadb:10.6                       Up (healthy)
facturacion_pma     phpmyadmin/phpmyadmin:latest       Up
```

### 4.4 Verificar la aplicación

```bash
curl -s -o /dev/null -w "HTTP %{http_code}\n" http://localhost:8080/
```

Debe responder `HTTP 200`.

Verifique que la base de datos se haya inicializado:

```bash
docker compose exec db mariadb -uroot -proot \
  -e "USE dbfacturacion; SELECT usuario, perfil FROM usuarios;"
```

Salida esperada:

```text
usuario  perfil
demo     Administrador
```

### 4.5 Ingresar al sistema

1. Abra <http://localhost:8080>.
2. Usuario: `demo`.
3. Contraseña: `123456`.
4. Debe aparecer el panel de control con los indicadores del día.

phpMyAdmin está disponible en <http://localhost:8081> con usuario `root` y contraseña `root`.

### 4.6 Si algo falla

| Síntoma | Causa probable | Solución |
|---|---|---|
| `port is already allocated` | Otro servicio usa 8080/8081/3306. | Cambie los puertos en `.env` y ejecute `docker compose up -d`. |
| La página responde 500 | La base de datos aún no terminó de inicializarse. | Espere 20 s y recargue; revise `docker compose logs db`. |
| `Connection refused` en la base | El contenedor `db` no está *healthy*. | `docker compose ps` y `docker compose logs db`. |
| Login no redirige | Usuario inexistente o sesión previa. | Use `demo` / `123456`; abra una ventana privada. |
| Quiere reiniciar todo | Datos de pruebas acumulados. | `docker compose down -v && docker compose up -d --build`. |

> `docker compose down -v` **borra** el volumen de la base de datos. Úselo solo para reiniciar el laboratorio.

---

## 5. Parte 2: ejecutar la suite de regresión

### 5.1 Estructura de la suite

```text
e2e/
├── package.json
├── package-lock.json
├── playwright.config.ts
├── tests/
│   ├── helpers/auth.ts
│   ├── auth.spec.ts
│   ├── dashboard.spec.ts
│   ├── navegacion.spec.ts
│   ├── clientes.spec.ts
│   ├── productos.spec.ts
│   ├── ventas.spec.ts
│   └── http.spec.ts
├── scripts/
│   ├── resumen.mjs
│   ├── medir.mjs
│   └── verificar.mjs
├── cambios/
│   └── regresion-simulada.patch
└── README.md
```

Descripción breve:

- `playwright.config.ts`: URL base, navegadores, reintentos, reportes HTML/JSON y capturas en caso de fallo.
- `tests/helpers/auth.ts`: función `iniciarSesion()` reutilizable, generador de datos únicos y neutralización del overlay de carga.
- `tests/*.spec.ts`: casos organizados por módulo. Las pruebas con `@critico` forman la suite de riesgo.
- `scripts/resumen.mjs`: convierte el JSON de Playwright en una tabla Markdown para el informe.
- `scripts/medir.mjs`: calcula la mediana de tiempo y la reducción entre dos grupos de ejecuciones.
- `scripts/verificar.mjs`: falla el job si hay pruebas inesperadas (regresiones).
- `cambios/regresion-simulada.patch`: cambio controlado que rompe el acceso directo a factura.

### 5.2 Ejecutar con Docker (recomendado)

Desde la raíz del repositorio:

```bash
docker compose --profile test run --rm e2e
```

El servicio `e2e`:

1. Se conecta a la red interna de Docker.
2. Ejecuta `npm ci`.
3. Lanza Playwright contra `http://app.local`.
4. Deja los reportes en `e2e/playwright-report/` y `e2e/test-results/`.

Salida esperada:

```text
31 passed (1.0m)
```

> La ejecución tarda alrededor de un minuto porque el contenedor usa 2 workers y reintentos de CI. En local con Node y varios workers baja a 25–35 s según el equipo.

Para ejecutar solo la suite crítica:

```bash
docker compose --profile test run --rm e2e \
  sh -c "npm ci --no-audit --no-fund && npx playwright test --grep @critico"
```

### 5.3 Ejecutar con Node.js (opcional)

```bash
cd e2e
npm ci
npx playwright install --with-deps chromium
npm test
```

Si el sistema no tiene las dependencias del sistema para Chromium, use solo `npx playwright install chromium` o vuelva a la ruta Docker.

Abrir el reporte HTML:

```bash
npm run report
```

### 5.4 Interpretar el resultado

- **31 passed**: la suite terminó sin fallos inesperados. Playwright cuenta las pruebas con `test.fail()` como aprobadas cuando fallan como se esperaba.
- `DEF-01` aparece con `✘` en la lista porque usa `test.fail()`, pero **no cuenta como fallo inesperado**: el resumen global sigue en verde. Documenta un defecto real de autenticación (anexo B) y en el reporte HTML se ve la anotación que explica por qué se espera ese fallo.
- Si hay fallos inesperados, Playwright guarda captura, video y traza en `e2e/test-results/`. Abra la traza con:

```bash
npx playwright show-trace test-results/<carpeta>/trace.zip
```

### 5.5 Generar el primer reporte JSON

Desde la raíz del repositorio. Si vienes de `e2e`, ejecuta `cd ..` antes de empezar:

```bash
cd e2e
mkdir -p resultados
PLAYWRIGHT_JSON_OUTPUT_NAME=resultados/completa.json \
  npx playwright test --project=chromium --reporter=line,json
node scripts/resumen.mjs resultados/completa.json
```

Guarde el resultado: será la línea base de la estrategia completa.

---

## 6. Parte 3: aplicar las estrategias de regresión

> **Convención de rutas:** los comandos indican la carpeta desde la que se ejecutan. Si ya estás en `e2e`, omite `cd e2e`; si ya estás en la raíz, omite `cd ..`.

> **Antes de empezar:** la estrategia TIA necesita un repositorio Git con la suite ya confirmada. Cree una rama de trabajo y confirme los archivos.

```bash
cd ..   # volver a la raíz del repositorio (omitir si ya estás en la raíz)
git checkout -b lab/regresion
git add e2e scripts .github docker-compose.yml .gitignore .dockerignore
git commit -m "lab: suite de regresion y pipeline CI/CD"
```

Si el repositorio ya trae esos archivos confirmados, o si ya estás en la rama `lab/regresion`, **omite este bloque** y continúa con el punto 6.1. Verifícalo con:

```bash
git status
git log --oneline -1
```

### 6.1 Estrategia completa (retest-all)

**Definición:** ejecuta todas las pruebas después de cada cambio. Es la estrategia más segura y la más costosa.

Ejecute cinco veces para obtener una mediana representativa:

```bash
cd e2e
mkdir -p resultados/mediciones
for i in 1 2 3 4 5; do
  PLAYWRIGHT_JSON_OUTPUT_NAME="resultados/mediciones/completa-$i.json" \
    npx playwright test --project=chromium --reporter=line,json
done
node scripts/medir.mjs resultados/mediciones/completa-*.json
```

Salida esperada (equipo del autor, 8 workers; el tiempo varía según el equipo):

```text
| Grupo | Ejecuciones | Pruebas (mediana) | Tiempo mediana (s) | Min (s) | Max (s) | Fallidas |
|---|---:|---:|---:|---:|---:|---:|
| Grupo 1 | 5 | 31 | 25.4 | 24.8 | 27.5 | 0 |
```

> **Nota:** si aparece una prueba fallida, revise el Anexo C antes de registrar la medición. La suite debe quedar en **0 fallos** para tomar la línea base.

Anote en la tabla del informe:

- Pruebas ejecutadas: **31**
- Tiempo de ejecución (mediana): **______ s**
- Fallos detectados: **______**
- Tiempo de feedback del job: **______ s**

### 6.2 Estrategia selectiva / TIA

Playwright implementa una forma estática y de granularidad gruesa de TIA con `--only-changed`. Compara los archivos de prueba contra una referencia Git y selecciona:

- los archivos de prueba modificados;
- los archivos de prueba que importan archivos modificados.

#### Escenario A: se modifica una prueba

```bash
cd ..   # raíz del repositorio (omitir si ya estás en la raíz)
echo "// ajuste de prueba para el laboratorio" >> e2e/tests/dashboard.spec.ts
cd e2e
npx playwright test --project=chromium --only-changed=HEAD --list
```

Salida esperada:

```text
Total: 3 tests in 1 file
```

Ejecute y mida:

```bash
PLAYWRIGHT_JSON_OUTPUT_NAME=resultados/tia-spec.json \
  npx playwright test --project=chromium --only-changed=HEAD \
  --pass-with-no-tests --reporter=line,json
```

Vuelva a dejar la prueba como estaba:

```bash
cd ..   # raíz del repositorio (omitir si ya estás en la raíz)
git checkout -- e2e/tests/dashboard.spec.ts
```

#### Escenario B: se modifica solo el código de la aplicación

```bash
git apply e2e/cambios/regresion-simulada.patch
cd e2e
npx playwright test --project=chromium --only-changed=HEAD --list
```

Salida esperada:

```text
Total: 0 tests in 0 files
```

Ejecute con `--pass-with-no-tests` para que el pipeline no falle por no tener pruebas:

```bash
PLAYWRIGHT_JSON_OUTPUT_NAME=resultados/tia-app.json \
  npx playwright test --project=chromium --only-changed=HEAD \
  --pass-with-no-tests --reporter=line,json
```

El resultado es **0 pruebas ejecutadas y 0 fallos**. Es un *falso verde*: la regresión del enlace está presente, pero TIA no la ve porque el archivo PHP no pertenece al grafo de importaciones de las pruebas.

> **Lección clave:** en E2E, `--only-changed` no es una selección segura. Es una optimización para cambios en las pruebas, no para cambios en el backend. El informe lo explica en la sección 3.4 y en la discusión.

#### Escenario C: referencia de rama base (como en CI)

En GitHub Actions se usa la rama base del pull request:

```bash
npx playwright test --project=chromium \
  --only-changed=origin/main --pass-with-no-tests
```

Requiere `fetch-depth: 0` en `actions/checkout`; de lo contrario Git no encuentra `origin/main`.

**Estado de partida:** el parche del Escenario B sigue aplicado.

**Resultado esperado:** al comparar contra `origin/main`, Playwright selecciona las pruebas que difieren de la rama base. En esta rama eso equivale a toda la suite (31 pruebas), porque `e2e/` es nuevo respecto de `origin/main`. Con el parche aplicado, la regresión **sí se detecta**:

```text
Running 31 tests using 8 workers

  ✓  ...
  ✘  [chromium] › tests/dashboard.spec.ts:16:7 › Panel de control › @critico el acceso directo Emitir factura abre la pantalla de factura

  1 failed
    [chromium] › tests/dashboard.spec.ts:16:7 › Panel de control › @critico el acceso directo Emitir factura abre la pantalla de factura
  30 passed
```

La prueba falla porque el enlace ya no tiene `href="crear-factura"` sino `href="crear-factura-rota"`, y el localizador espera el atributo original. Este es el contraste que busca el laboratorio: con `HEAD` (Escenario B) TIA no ve el cambio y da un falso verde; con la rama base (Escenario C) sí lo ve, porque los cambios confirmados de la rama entran en la comparación.

> **Si tu rama parte de una base que ya contiene `e2e/`**, el número de pruebas seleccionadas puede ser menor que 31. Lo importante es que `origin/main` exista y que la comparación incluya los cambios confirmados de la rama; el resultado puede variar, pero la referencia base debe encontrar la regresión si el cambio forma parte de la rama.

| Estado del parche | Pruebas ejecutadas | Resultado esperado |
|---|---:|---|
| Aplicado (después del Escenario B) | 31 | 1 fallo en `dashboard.spec.ts` y 30 aprobadas |
| Revertido | 31 | 31 aprobadas |

> **Nota:** `DEF-01` aparece con `✘` porque usa `test.fail(...)`; se contabiliza como aprobada y no como fallo inesperado.

Vuelva a la raíz del repositorio y revierta el parche antes de continuar:

```bash
cd ..   # raíz del repositorio (omitir si ya estás en la raíz)
git apply -R e2e/cambios/regresion-simulada.patch
git status
```

**Resultado esperado:** `git status` ya no muestra `vistas/modulos/menu-rapido.php` entre los archivos modificados. Si vuelve a ejecutar el comando del Escenario C, la suite queda en **31 aprobadas**; ese es el punto de partida en verde para las secciones 6.3 y 6.4.

### 6.3 Estrategia basada en riesgos

**Definición:** ejecuta solo las pruebas de los flujos con mayor exposición. En la suite se identifican con la etiqueta `@critico`.

Ejecute desde la raíz del repositorio. Si ya está dentro de `e2e`, omita el `cd e2e`:

```bash
cd e2e
for i in 1 2 3 4 5; do
  PLAYWRIGHT_JSON_OUTPUT_NAME="resultados/mediciones/critico-$i.json" \
    npx playwright test --project=chromium --grep @critico --reporter=line,json
done
node scripts/medir.mjs resultados/mediciones/critico-*.json
```

Salida esperada (el tiempo varia segun el equipo; lo importante es que no haya fallos):

```text
| Grupo | Ejecuciones | Pruebas (mediana) | Tiempo mediana (s) | Min (s) | Max (s) | Fallidas |
|---|---:|---:|---:|---:|---:|---:|
| Grupo 1 | 5 | 8 | 8.0 | 7.8 | 8.7 | 0 |
```

> **Nota:** una diferencia de uno o dos segundos no invalida la medicion. Si aparece una prueba fallida, revise el Anexo C antes de registrar el resultado.

Compare las dos estrategias:

```bash
node scripts/medir.mjs \
  resultados/mediciones/completa-*.json -- \
  resultados/mediciones/critico-*.json
```

Salida esperada (aproximada; los porcentajes de tiempo dependen del equipo):

```text
Reduccion del grupo 2 respecto del grupo 1: 74.2 % en pruebas y 68-71 % en tiempo.
```

La reduccion en pruebas debe ser exactamente 74.2 % (8 de 31). La reduccion en tiempo puede variar, pero debe rondar el 70 %.

#### ¿Qué pruebas son críticas y por qué?

| Prueba | Riesgo que cubre |
|---|---|
| Login y logout | Sin autenticación no hay acceso al sistema. |
| Indicadores del panel | Es la primera pantalla que ve el usuario. |
| Acceso directo a factura | Flujo de emisión de comprobantes. |
| Alta de cliente | Requisito para emitir facturas y boletas. |
| Alta de producto | Requisito para armar una venta. |
| Pantalla de boleta | Emisión de comprobantes al consumidor. |
| Página de acceso HTTP | Disponibilidad mínima del servicio. |

Las pruebas de navegación y búsqueda quedan fuera de `@critico` porque detectan fallos de menor impacto y se ejecutan en la regresión completa.

### 6.4 Provocar la regresión y comparar la detección

Con la suite en verde, aplique el cambio simulado:

```bash
cd ..   # raíz del repositorio (omitir si ya estás en la raíz)
git apply e2e/cambios/regresion-simulada.patch
```

**a) Regresión completa:**

```bash
cd e2e
npx playwright test --project=chromium --reporter=line
```

Resultado esperado: **1 fallo** y **30 aprobadas**. El fallo es en `dashboard.spec.ts`, en la prueba `@critico el acceso directo Emitir factura abre la pantalla de factura`. La suite completa detecta la regresión.

> **Importante:** este fallo es intencional: es la evidencia de que la regresión fue detectada. No modifique la prueba ni el enlace. El paso 6.4d revierte el parche y devuelve la suite a verde.

**b) Suite crítica:**

```bash
npx playwright test --project=chromium --grep @critico --reporter=line
```

Resultado esperado: **1 fallo** y **7 aprobadas**. La suite crítica también la detecta, porque el acceso a factura está etiquetado como crítico.

**c) TIA sobre el cambio de aplicación:**

```bash
npx playwright test --project=chromium --only-changed=HEAD \
  --pass-with-no-tests --list
```

Resultado esperado: **0 pruebas**. TIA no detecta la regresión.

> **Nota:** este paso supone que los cambios de las pruebas ya están confirmados. Si tienes modificaciones sin confirmar en `e2e/tests`, `--only-changed=HEAD` las seleccionará y el resultado no será 0. Confirma esos cambios (o usa `git stash`) antes de medir TIA.

Registre la comparación:

| Estrategia | Pruebas ejecutadas | Fallos detectados | ¿Detecta la regresión? |
|---|---:|---:|---|
| Completa | 31 | 1 | Sí |
| Crítica (`@critico`) | 8 | 1 | Sí |
| TIA (solo app) | 0 | 0 | **No** |

**d) Revertir el cambio:**

```bash
cd ..   # raíz del repositorio (omitir si ya estás en la raíz)
git apply -R e2e/cambios/regresion-simulada.patch
cd e2e
npx playwright test --project=chromium --grep @critico --reporter=line
```

Debe volver a **8 passed**.

> **Para el informe:** este experimento demuestra por qué el pipeline combina TIA con la suite crítica. TIA ahorra tiempo en cambios de pruebas, pero no reemplaza la cobertura de riesgo ni la regresión completa.

### 6.5 Priorización (opcional)

La suite no reordena pruebas por riesgo; el orden lo decide Playwright. Si desea observar priorización, ejecute la suite crítica primero y luego la completa:

```bash
npx playwright test --project=chromium --grep @critico
npx playwright test --project=chromium
```

En un pipeline real, la suite crítica se ejecuta en la etapa 1 y la completa en la etapa 2, que es exactamente el diseño del workflow.

---

## 7. Parte 4: pipeline de CI/CD en GitHub Actions

### 7.1 Diseño por etapas

El workflow está en:

```text
.github/workflows/regresion.yml
```

| Etapa | Disparador | Estrategia | Job | Presupuesto |
|---|---|---|---|---|
| 1. Pull request | `pull_request` a `main` | TIA + `@critico` | `regresion-pr` | < 10 min |
| 2. Integración | `push` a `main` | Completa en 4 fragmentos | `regresion-integracion` | < 30 min |
| 3. Nocturna | `schedule` o manual | Completa en Chromium, Firefox y WebKit | `regresion-nocturna` | < 45 min |

El workflow también se puede lanzar manualmente desde **Actions → regresion → Run workflow**.

### 7.2 Qué hace cada job

**Job `regresion-pr`:**

1. Clona con `fetch-depth: 0` para que `--only-changed` encuentre la rama base.
2. Levanta la aplicación con Docker Compose.
3. Espera a que el login responda (`scripts/wait-for-app.sh`).
4. Instala la suite y Chromium.
5. Ejecuta TIA contra `origin/<rama base>`.
6. Ejecuta la suite crítica `@critico`.
7. Publica el resumen en el *job summary*.
8. Sube los reportes como artefactos.
9. Falla si algunas de las dos etapas tuvo pruebas inesperadas.

**Job `regresion-integracion`:**

- Se ejecuta en cada push a `main`.
- Usa una matriz de 4 fragmentos (`--shard=1/4` … `4/4`).
- Cada fragmento sube su propio reporte.
- `fail-fast: false` permite ver todos los fragmentos aunque uno falle.

**Job `regresion-nocturna`:**

- Se ejecuta con `schedule` (06:00 UTC = 01:00 en Perú) o manualmente.
- Usa `BROWSERS=all`, que agrega Firefox y WebKit.
- Es la red de seguridad que detecta problemas de compatibilidad.

### 7.3 Publicar el repositorio y abrir el pull request

```bash
cd ..   # raíz del repositorio (omitir si ya estás en la raíz)
git status

# Si hay cambios pendientes:
git add e2e scripts .github docker-compose.yml .gitignore .dockerignore
git commit -m "lab: suite de regresion y pipeline CI/CD"

# Publicar la rama (si aún no tiene upstream):
git push -u origin lab/regresion
```

> Si `git commit` responde `nothing to commit`, la suite ya estaba confirmada: continúa directamente con `git push`. Si modificaste la documentación, agrégala también con `git add documentation`.
>
> Si el pull request muestra conflictos, actualiza la rama con `git fetch origin && git merge origin/main` y vuelve a hacer push.

En GitHub:

1. Abra el repositorio y pulse **Compare & pull request**.
2. Base: `main`; comparación: `lab/regresion`.
3. Cree el pull request.
4. Vaya a la pestaña **Checks** y observe el job `Regresion PR (TIA + @critico)`.

### 7.4 Qué observar en la ejecución

- **TIA**: en este PR la rama agrega toda la carpeta `e2e/` respecto de `main`, por lo que TIA ejecutará las 31 pruebas. Para observar el caso de 0 pruebas, usa un PR posterior que solo toque archivos PHP (o el Escenario B local). Es el comportamiento esperado y demuestra la limitación.
- **Crítica**: debe ejecutar 8 pruebas. Si el cambio rompe un flujo crítico, el job falla.
- **Resumen**: al final del job aparece una tabla con pruebas esperadas, fallidas e inestables.
- **Artefactos**: descargue `reporte-pr` para ver el HTML, las capturas y las trazas.

Si el job falla, revise:

```bash
gh run list --workflow=regresion.yml
gh run view <run-id> --log
```

### 7.5 Integración en `main`

Después del merge (o de un push directo a `main`):

```bash
git checkout main
git pull
git merge lab/regresion
git push origin main
```

> Si fusionaste el pull request desde GitHub, `main` ya contiene los cambios: ejecuta solo `git checkout main && git pull` y omite el `git merge`.

Se dispara `regresion-integracion` con 4 fragmentos. Verifique que los cuatro terminen en verde y que la suma de pruebas sea 31.

### 7.6 Ejecución nocturna

Desde GitHub: **Actions → regresion → Run workflow → Branch: main → Run workflow**.

O por CLI:

```bash
gh workflow run regresion.yml --ref main
gh run list --workflow=regresion.yml
gh run watch
```

La ejecución nocturna usa tres navegadores. El total de pruebas es 31 × 3 = 93 (aproximadamente), por lo que tarda más que la etapa 1.

### 7.7 Medir el tiempo de feedback

El tiempo de feedback es el intervalo entre el push y la conclusión del job de la etapa 1. GitHub Actions muestra la duración en la interfaz y en la API.

Con `gh`:

```bash
gh run list --workflow=regresion.yml --limit 5 \
  --json displayTitle,event,status,conclusion,createdAt,updatedAt
```

Registre:

| Intento | Fecha/hora | Duración del job (s) | Pruebas | Fallos | Observación |
|---|---|---:|---:|---:|---|
| 1 | | | | | |
| 2 | | | | | |
| 3 | | | | | |

Para comparar de forma justa, repita al menos cinco veces y reporte la mediana. Los runners compartidos de GitHub varían entre ejecuciones.

---

## 8. Parte 5: métricas y evidencias

### 8.1 Fórmulas

Con los datos del laboratorio:

```text
Reducción de pruebas (%) = (1 - pruebas_estrategia / pruebas_completa) × 100
Reducción de tiempo (%)  = (1 - tiempo_estrategia / tiempo_completa) × 100
Inclusividad (%)         = pruebas_reveladoras_ejecutadas / pruebas_reveladoras_totales × 100
Precisión (%)            = pruebas_no_reveladoras_omitidas / pruebas_no_reveladoras_totales × 100
Tiempo de feedback (s)   = fin_del_job - push
```

En el laboratorio, la inclusividad se aproxima con la regresión simulada: la prueba que falla en la suite completa es la prueba reveladora. Una estrategia que no la ejecuta tiene inclusividad 0 para ese cambio.

### 8.2 Tabla de resultados

Complete con sus mediciones. Los valores de tiempo son **referencia del autor** (equipo con 8 workers y la aplicación en Docker local); no son un resultado universal.

| Estrategia | Pruebas | Reducción de pruebas (%) | Tiempo mediana (s) | Tiempo de feedback (s) | Fallos detectados | Inclusividad (%) |
|---|---:|---:|---:|---:|---:|---:|
| Completa | 31 | 0 | 25.4 | ______ | 1/1 | 100 |
| Crítica (`@critico`) | 8 | 74.2 | 8.0 | ______ | 1/1 | 100 |
| TIA (solo app) | 0 | 100 | 0.09 | ______ | 0/1 | **0** |
| TIA (prueba modificada) | 3 | 90.3 | 5.7 | ______ | — | — |

> **Advertencia metodológica:** TIA tiene la mayor reducción y la peor inclusividad en el escenario de cambio de aplicación. Reportar solo el ahorro sería engañoso; por eso el informe exige métricas pareadas de efectividad y eficiencia.

### 8.3 Contraste con la cifra del 60–80 %

El informe cuestiona que TIA reduzca el tiempo entre 60 % y 80 % de forma general. En este laboratorio:

- La suite crítica reduce **74.2 % de las pruebas** y alrededor de **68 % del tiempo** (68-71 % según el equipo), pero es una estrategia basada en riesgos, no TIA.
- TIA reduce **100 %** en el cambio de aplicación, pero **no detecta la regresión** (inclusividad 0).
- TIA sobre una prueba modificada reduce **90.3 % de las pruebas** y alrededor de **77 % del tiempo**, pero solo porque el cambio está en la propia prueba.

Conclusión: la cifra es plausible en ciertos contextos, pero no debe presentarse como una propiedad general de TIA. Depende de dónde esté el cambio y de qué se mida.

### 8.4 Evidencias que debe capturar

1. `docker compose ps` con los contenedores en ejecución.
2. Salida de `31 passed` de la suite completa.
3. Reporte HTML de Playwright con la prueba `DEF-01` marcada como fallo esperado.
4. Captura del fallo de `dashboard.spec.ts` con el parche aplicado.
5. Resumen del job `regresion-pr` en GitHub Actions.
6. Artefacto `reporte-pr` descargado.
7. Ejecución de los 4 fragmentos de integración.
8. Ejecución nocturna con los tres navegadores.
9. Tabla de métricas completa.

Cree la carpeta si no existe:

```bash
mkdir -p docs/evidencias
```

Guarde las capturas en:

```text
docs/evidencias/
├── 01-contenedores.png
├── 02-suite-completa.png
├── 03-reporte-html.png
├── 04-regresion-detectada.png
├── 05-actions-pr.png
├── 06-artefacto.png
├── 07-shards.png
└── 08-nocturna.png
```

### 8.5 Cómo citar las evidencias en el informe

Use la tabla del capítulo VII del informe:

| Sección | Evidencia |
|---|---|
| 7.1 Sistema seleccionado | Repositorio, stack y `docker compose ps`. |
| 7.2 Cambio aplicado | `e2e/cambios/regresion-simulada.patch` y clasificación ISO 14764. |
| 7.3 Suite | Tabla de 31 pruebas y 8 críticas. |
| 7.4 Estrategias | Comandos y resultados de completa, TIA y riesgo. |
| 7.5 Pipeline | `.github/workflows/regresion.yml` y capturas de Actions. |
| 7.6 Resultados | Tabla 9 del informe completa (equivale a la tabla de métricas del apartado 8.2). |
| 7.7 Evidencias | Capturas y enlaces a ejecuciones. |

---

## 9. Parte 6: entregables y rúbrica

### 9.1 Entregables

1. **Repositorio** con:
   - `e2e/` completo y ejecutable.
   - `.github/workflows/regresion.yml`.
   - `docker-compose.yml` con el servicio `e2e`.
   - `scripts/wait-for-app.sh`.
2. **Informe** con la tabla de métricas y las capturas.
3. **Enlace** al pull request y a una ejecución de GitHub Actions.
4. **Reflexión** de 10–15 líneas: ¿qué estrategia usaría en un cambio correctivo, adaptativo y perfectivo? Justifique con los datos.

### 9.2 Rúbrica (20 puntos)

| Criterio | Puntos | Indicador |
|---|---:|---|
| Entorno reproducible | 3 | `docker compose up -d --build` levanta app, db y phpMyAdmin; login funciona. |
| Suite E2E | 4 | ≥ 20 pruebas, organizadas por módulo, con datos únicos y sin dependencias entre sí. |
| Cobertura crítica | 3 | ≥ 6 pruebas `@critico` justificadas por riesgo. |
| Estrategias | 4 | Ejecuta completa, TIA y riesgo; documenta resultados y limitaciones. |
| Regresión simulada | 3 | El parche se aplica, se detecta el fallo y se revierte correctamente. |
| Pipeline CI/CD | 3 | Workflow por etapas, artefactos, resumen y jobs en verde. |
| Métricas y evidencias | 2 | Tabla completa, medianas, capturas y enlaces. |
| Calidad del informe | 2 | Redacción clara, tablas, conclusiones basadas en datos. |

### 9.3 Checklist final

- [ ] `docker compose ps` muestra los contenedores arriba.
- [ ] `docker compose --profile test run --rm e2e` termina en verde.
- [ ] `npm run test:critico` ejecuta 8 pruebas.
- [ ] Los cambios de las pruebas están confirmados antes de medir TIA.
- [ ] El parche de regresión produce 1 fallo en la suite completa.
- [ ] El parche de regresión produce 1 fallo en la suite crítica.
- [ ] TIA sobre el cambio de app ejecuta 0 pruebas (limitación documentada).
- [ ] El parche se revierte y la suite vuelve a **31 passed**.
- [ ] La rama `lab/regresion` está publicada y el PR apunta a `main`.
- [ ] El pull request ejecuta `regresion-pr` en menos de 10 minutos y queda en verde.
- [ ] La integración en `main` ejecuta 4 fragmentos.
- [ ] La ejecución nocturna usa Chromium, Firefox y WebKit.
- [ ] La tabla de métricas está completa y respaldada por las capturas.

---

## 10. Anexos

### Anexo A. Mapa de archivos del laboratorio

| Ruta | Propósito |
|---|---|
| `e2e/playwright.config.ts` | Configuración de Playwright. |
| `e2e/package.json` | Dependencias y scripts. |
| `e2e/tests/helpers/auth.ts` | Login reutilizable y neutralización del overlay de carga. |
| `e2e/tests/*.spec.ts` | Casos de prueba. |
| `e2e/scripts/resumen.mjs` | Tabla Markdown desde JSON. |
| `e2e/scripts/medir.mjs` | Medianas y reducción. |
| `e2e/scripts/verificar.mjs` | Falla si hay pruebas inesperadas. |
| `e2e/cambios/regresion-simulada.patch` | Cambio controlado. |
| `.github/workflows/regresion.yml` | Pipeline de 3 etapas. |
| `scripts/wait-for-app.sh` | Espera de disponibilidad en CI. |
| `docker-compose.yml` | Servicios `app`, `db`, `phpmyadmin` y `e2e`. |

### Anexo B. Hallazgos y mantenimiento correctivo opcional

#### DEF-01: el login acepta cualquier contraseña alfanumérica de un usuario existente

**Evidencia:** en `Controladores/ControladorUsuarios.php`, la comparación del hash está comentada y el bloque se ejecuta con `if (true)`:

```php
//if ($respuesta == true && $respuesta['usuario'] == $user && $respuesta['password'] == $encriptar) {
if (true) {
```

La prueba `DEF-01` de `auth.spec.ts` documenta el comportamiento actual con `test.fail()`.

**Corrección sugerida (opcional):**

> No reemplaces el `if (True)` que valida la respuesta de reCAPTCHA. La corrección se aplica solo a la comprobación de la contraseña y, en la rama con reCAPTCHA, también a la comprobación del estado.

En las dos ramas de `ctrIngresoUsuario` (con y sin reCAPTCHA), reemplace:

```php
if (true) {
```

por:

```php
if ($respuesta && $respuesta['usuario'] === $user
    && hash_equals($respuesta['password'], $encriptar)) {
```

y, en la primera rama, reemplace el `if (true)` interno por:

```php
if ($respuesta['estado'] == 1) {
```

Después:

1. Quite la línea `test.fail(...)` de `tests/auth.spec.ts`.
2. Ejecute `npx playwright test tests/auth.spec.ts`.
3. Verifique que `demo` / `123456` sigue entrando y que una clave incorrecta ya no entra.

> Este ejercicio convierte un defecto en una prueba de regresión permanente: es exactamente la recomendación del informe de convertir cada defecto corregido en una prueba automatizada.

#### DEF-02: fecha de nacimiento vacía provoca un error 500

**Evidencia:** al crear un cliente sin fecha de nacimiento, el controlador envía `''` a una columna `DATE` y MariaDB lanza `SQLSTATE[22007]`. La página queda en blanco.

**Corrección sugerida (opcional):** en `ControladorClientes::ctrCrearCliente()`, normalice el valor:

```php
"fecha_nacimiento" => $_POST['nuevaFechaNacimiento'] !== ''
    ? $_POST['nuevaFechaNacimiento']
    : null,
```

y agregue una prueba que cree un cliente sin fecha. Es un caso de mantenimiento correctivo con regresión asociada.

#### DEF-03: TIA no ve los cambios del backend PHP

No es un defecto del sistema, sino una limitación de `--only-changed`: el archivo PHP no está en el grafo de importaciones de las pruebas. Por eso el pipeline ejecuta también `@critico` y la regresión completa.

### Anexo C. Solución de problemas

| Problema | Solución |
|---|---|
| `docker compose` no reconoce `--profile` | Actualice Compose a v2. |
| `npm ci` falla dentro de `e2e` | Borre `e2e/node_modules` y vuelva a ejecutar. |
| Chromium no arranca en Linux | `npx playwright install --with-deps chromium` (requiere `sudo`) o use Docker. |
| `net::ERR_CONNECTION_REFUSED` en el contenedor `e2e` | Verifique que `app` esté arriba y que el servicio use `BASE_URL=http://app.local`. |
| `--only-changed` devuelve 0 pruebas | Es esperado si solo cambió código de la app; documente la limitación. |
| `--only-changed=origin/main` falla | El clon es superficial; use `fetch-depth: 0`. |
| `git apply` falla con `patch does not apply` | El parche ya está aplicado o el archivo cambió. Use `git apply -R e2e/cambios/regresion-simulada.patch` o `git checkout -- vistas/modulos/menu-rapido.php`. |
| `git apply -R` falla | El parche no está aplicado. Verifique `git status` y no lo revierta dos veces. |
| Los reportes quedan con propietario `root` | `sudo chown -R "$USER":"$USER" e2e/test-results e2e/playwright-report e2e/resultados`. |
| El login no funciona tras cambiar la clave | Restaure `demo` / `123456` o use `docker compose down -v` para reiniciar la base. |
| Las pruebas acumulan datos | Use nombres únicos (la suite ya lo hace) o reinicie el volumen. |
| Una prueba falla con `intercepts pointer events` | El overlay global `.reload-all` quedó visible por una petición de fondo fallida. La suite lo neutraliza en `tests/helpers/auth.ts` (`neutralizarOverlayCarga`); verifique que ese helper esté aplicado y repita la medición. |
| GitHub Actions no ejecuta el workflow | Verifique que el archivo esté en `.github/workflows/` y que Actions esté habilitado. |
| El job de PR falla en TIA | Revise si el cambio afecta una prueba; si no, es la limitación esperada. El job falla si hay pruebas inesperadas en TIA o en `@critico`. |

### Anexo D. Chuleta de comandos

```bash
# Levantar el sistema
docker compose up -d --build

# Estado y logs
docker compose ps
docker compose logs -f app

# Suite completa con Docker
docker compose --profile test run --rm e2e

# Suite crítica con Docker
docker compose --profile test run --rm e2e \
  sh -c "npm ci --no-audit --no-fund && npx playwright test --grep @critico"

# Suite local
cd e2e
npm ci
npx playwright install --with-deps chromium
npm test
npm run test:critico
npm run test:changed:local

# Reporte HTML
npm run report

# Medir
node scripts/medir.mjs resultados/mediciones/completa-*.json -- resultados/mediciones/critico-*.json

# Regresión simulada
cd ..
git apply e2e/cambios/regresion-simulada.patch
cd e2e && npx playwright test --project=chromium --reporter=line
cd .. && git apply -R e2e/cambios/regresion-simulada.patch

# Reiniciar la base de datos
docker compose down -v
docker compose up -d --build
```

### Anexo E. Relación con el informe

| Concepto del informe | Dónde se aplica en el laboratorio |
|---|---|
| ISO/IEC/IEEE 14764:2022, cambio perfectivo | El parche modifica la interfaz del panel. |
| Regresión completa (sección 3.1) | `npx playwright test`. |
| TIA (sección 3.4) | `--only-changed`. |
| Regresión basada en riesgos (sección 3.3) | `--grep @critico`. |
| Pipeline por etapas (sección 6.1) | Jobs `regresion-pr`, `regresion-integracion`, `regresion-nocturna`. |
| Métricas (capítulo V) | `scripts/medir.mjs` y tabla de resultados. |
| Capítulo VII pendiente | Este laboratorio produce las evidencias para completarlo. |

### Anexo F. Limpieza

Al terminar:

```bash
docker compose --profile test down
docker compose down
```

Si desea conservar la base de datos, no use `-v`. Si desea borrarla:

```bash
docker compose down -v
```

Para eliminar los artefactos de prueba:

```bash
rm -rf e2e/test-results e2e/playwright-report e2e/resultados
```

### Anexo G. Guion sugerido para la exposición (10–12 minutos)

| Minuto | Qué mostrar | Mensaje clave |
|---|---|---|
| 0–1 | Contexto: sistema legado y cambio perfectivo en el menú de facturación. | Un cambio pequeño en la interfaz puede romper un flujo crítico. |
| 1–3 | `docker compose ps` y login `demo` / `123456`. | El entorno es reproducible con un comando. |
| 3–4 | `npx playwright test --grep @critico`. | 8 pruebas cubren los flujos de mayor riesgo; `DEF-01` es un fallo esperado. |
| 4–6 | Tabla de métricas: 31 vs 8 pruebas, ~25 s vs ~8 s. | La suite crítica reduce ~74 % de las pruebas y ~68 % del tiempo. |
| 6–8 | Aplicar el parche: suite completa con 1 fallo; TIA con 0 pruebas; revertir. | TIA es rápido, pero ciego a los cambios del backend PHP. |
| 8–10 | Workflow: PR (TIA + crítica), `main` (4 shards) y nocturna (3 navegadores). | El pipeline combina eficiencia y cobertura. |
| 10–12 | Conclusiones y limitaciones. | Usar TIA para cambios de pruebas y complementarla con riesgo y regresión completa. |

**Comandos para la demo (desde la raíz del repositorio):**

```bash
# 1. Estado del entorno
docker compose ps

# 2. Suite crítica
cd e2e
npx playwright test --project=chromium --grep @critico

# 3. Regresión simulada
cd ..
git apply e2e/cambios/regresion-simulada.patch
cd e2e
npx playwright test --project=chromium --grep @critico       # 1 fallo esperado
npx playwright test --project=chromium --only-changed=HEAD \
  --pass-with-no-tests --list                                # 0 pruebas
cd ..
git apply -R e2e/cambios/regresion-simulada.patch
cd e2e
npx playwright test --project=chromium --grep @critico       # 8 passed
npx playwright test --project=chromium                       # 31 passed (opcional)
```

**Preguntas frecuentes del jurado:**

| Pregunta | Respuesta breve |
|---|---|
| ¿Por qué TIA no detecta la regresión? | El cambio está en PHP y no pertenece al grafo de importaciones de las pruebas; `--only-changed` solo ve pruebas y sus imports. |
| ¿Por qué `DEF-01` aparece con `✘` y el pipeline sigue verde? | Usa `test.fail()`: es un fallo esperado que documenta un defecto conocido. |
| ¿No basta con TIA para ahorrar tiempo? | No en E2E: reduce tiempo, pero puede dar falsos verdes. Se complementa con `@critico` y la regresión completa. |
| ¿Cómo se evita que la suite sea inestable? | Datos únicos, esperas por estado, neutralización del overlay de carga y reintentos en CI. |
| ¿Qué evidencia demuestra la detección? | El fallo de `dashboard.spec.ts` con el parche, el reporte HTML, los JSON y el job de Actions. |

---

**Fin del laboratorio.**  
Documente sus resultados, adjunte las capturas y explique con datos qué estrategia recomendaría para un cambio correctivo, uno adaptativo y uno perfectivo.
