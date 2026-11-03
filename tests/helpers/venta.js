const { expect } = require('@playwright/test');

/** Convierte "S/ 1,234.50" en 1234.5 */
function aNumero(texto) {
  return Number(texto.replace(/[^0-9.]/g, ''));
}

/** Lee un valor del cuadro RESUMEN (ej. 'op-igv', 'op-total', 'op-gravadas'). */
async function leerTotal(page, clase) {
  const texto = await page.locator(`.totales tr.${clase} td`).nth(1).innerText();
  return aNumero(texto);
}

/** Espera la proxima respuesta del servidor para clientes.ajax.php */
function esperarAjaxClientes(page) {
  return page.waitForResponse((r) => r.url().includes('ajax/clientes.ajax.php'));
}

/**
 * Busca un cliente ya registrado por su documento. tipoDoc: '1' = DNI, '6' = RUC
 *
 * Importante: al cambiar el "tipo de documento" el sistema hace una consulta al
 * servidor y, cuando responde, BORRA los campos del cliente. Si escribimos el
 * documento antes de esa respuesta (pasa en PCs lentas), el sistema lo borra y
 * el comprobante sale "sin RUC". Por eso esperamos cada respuesta antes de seguir.
 * Antes esperamos a que terminen las consultas que hace la pagina al cargar (tambien
 * llaman a clientes.ajax.php y podian confundirse con la respuesta que esperamos).
 */
async function elegirCliente(page, tipoDoc, documento) {
  await page.waitForLoadState('networkidle');
  const cambioTipo = esperarAjaxClientes(page);
  await page.selectOption('#tipoDoc', tipoDoc);
  await cambioTipo;

  await page.fill('#docIdentidad', documento);
  const busqueda = esperarAjaxClientes(page);
  await page.click('.buscarRuc');
  await busqueda;

  await expect(page.locator('#idCliente')).not.toHaveValue('', { timeout: 10_000 });
  await expect(page.locator('#docIdentidad')).toHaveValue(documento);
}

/** Abre la ventana de productos y agrega uno al carrito. */
async function agregarProducto(page, idProducto) {
  await page.click('.btn-agregar-carrito');
  const boton = page.locator(`button.agregarProducto[idProducto="${idProducto}"]`).first();
  await boton.click();
  // Espera a que el producto aparezca en la tabla del comprobante
  await expect(page.locator('#itemsP tr').first()).toBeVisible();
  await page.keyboard.press('Escape'); // cierra la ventana de productos
  await expect(page.locator('#modalProductosVenta')).toBeHidden();
}

/**
 * Hace clic en Guardar, confirma, y devuelve el texto que respondio el servidor.
 * El comprobante se guarda con la opcion "Solo Firmar e Imprimir",
 * es decir, NO se envia a SUNAT.
 */
async function guardarComprobante(page) {
  await page.check('#firmar'); // "Solo Firmar e Imprimir": nunca enviar a SUNAT en pruebas
  await page.click('.btnGuardarVenta');
  const respuesta = page.waitForResponse(
    (r) => r.url().includes('ajax/ventas.ajax.php') && r.request().method() === 'POST',
  );
  await page.click('.swal2-confirm'); // "Si, guardar!"
  const r = await respuesta;
  return { status: r.status(), texto: await r.text() };
}

/** Convierte una fecha de JavaScript a dd/mm/aaaa (formato del sistema). */
function fechaSistema(fecha) {
  const d = String(fecha.getDate()).padStart(2, '0');
  const m = String(fecha.getMonth() + 1).padStart(2, '0');
  return `${d}/${m}/${fecha.getFullYear()}`;
}

/** Fecha de hoy mas N dias. */
function diasDesdeHoy(n) {
  const f = new Date();
  f.setDate(f.getDate() + n);
  return f;
}

/**
 * Configura la venta "al Credito" con sus cuotas. cuotas = [{ fecha: Date, monto: number }]
 * Las fechas se eligen con el calendario del sistema (datepicker), igual que el usuario.
 */
async function configurarCredito(page, cuotas) {
  await page.selectOption('#tipopago', 'Credito');
  await page.selectOption('#numcuotas', String(cuotas.length));
  for (let i = 0; i < cuotas.length; i++) {
    const n = i === 0 ? '' : String(i + 1);
    const { fecha, monto } = cuotas[i];
    await page.evaluate(([id, a, m, d]) => window.$(id).datepicker('setDate', new Date(a, m, d)),
      [`#fecha_cuota${n}`, fecha.getFullYear(), fecha.getMonth(), fecha.getDate()]);
    await page.locator(`#cuotas${n}`).fill(String(monto));
  }
}

module.exports = { aNumero, leerTotal, elegirCliente, agregarProducto, guardarComprobante, configurarCredito, fechaSistema, diasDesdeHoy };
