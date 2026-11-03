-- MAD-001 (aditivo): registro de abonos a las cuotas de ventas al credito.
-- Ejecutar una sola vez:  mysql -u root -p dbfacturacion < BD/migraciones/002_abonos_cuotas.sql
CREATE TABLE IF NOT EXISTS abono_cuota (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_pago_credito INT NOT NULL,
  nro_cuota INT NOT NULL,
  monto DECIMAL(11,2) NOT NULL,
  fecha DATETIME NOT NULL,
  id_usuario INT NULL,
  usuario VARCHAR(100) NOT NULL,
  INDEX idx_abono_cuota (id_pago_credito, nro_cuota),
  CONSTRAINT fk_abono_pago_credito FOREIGN KEY (id_pago_credito) REFERENCES pago_credito(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
