// Datos de prueba que ya existen en BD/bdsistema.sql
module.exports = {
  usuario: process.env.TEST_USER || 'demo',
  password: process.env.TEST_PASSWORD || '123456',

  // Cliente con RUC registrado en la base de datos
  clienteRuc: { ruc: '20601487871', razonSocial: 'ROSLIF CIA S.A.C.' },

  // Cliente con DNI registrado en la base de datos
  clienteDni: { dni: '42116576', nombre: 'ESTOLCIMO GOMEZ JARRO' },

  // Producto: MOUSE INALAMBRICO M280 LOGITECH, precio S/ 63.00 (IGV incluido)
  producto: { id: '45', precio: 63.0 },
};
