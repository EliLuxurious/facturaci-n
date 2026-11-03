/**
 * Escribe un texto en un buscador del sistema y dispara UNA sola busqueda.
 *
 * Los buscadores consultan al servidor en cada tecla (onkeyup). Si se teclea letra
 * por letra, las respuestas pueden llegar desordenadas y la tabla termina mostrando
 * el resultado de una busqueda incompleta. Por eso se escribe todo y se dispara un
 * solo evento keyup.
 */
async function buscar(campo, texto) {
  await campo.fill(texto);
  await campo.dispatchEvent('keyup');
}

module.exports = { buscar };
