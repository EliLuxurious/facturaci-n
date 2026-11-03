-- MP-001 (perfectivo): stock minimo por producto para la alerta de reposicion.
-- Ejecutar una sola vez sobre la base de datos existente:
--   mysql -u root -p dbfacturacion < BD/migraciones/001_stock_minimo.sql
-- (En Docker se aplica automaticamente al crear la base de datos.)
ALTER TABLE productos
  ADD COLUMN IF NOT EXISTS stock_minimo INT NOT NULL DEFAULT 5 AFTER stock;
