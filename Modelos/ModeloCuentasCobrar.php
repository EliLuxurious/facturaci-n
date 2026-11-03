<?php
namespace Modelos;

use Conect\Conexion;
use PDO;

/**
 * MAD-001 (aditivo): consultas del modulo "Cuentas por cobrar".
 * Las cuotas de cada venta al credito se guardan en pago_credito como listas JSON
 * (fecha y monto); los pagos parciales se guardan en abono_cuota.
 */
class ModeloCuentasCobrar
{
    /** Ventas al credito con datos del comprobante y del cliente. */
    public static function mdlVentasCredito()
    {
        $stmt = Conexion::conectar()->prepare(
            "SELECT pc.id AS id_pago_credito, pc.id_venta, pc.fecha, pc.cuota,
                    v.serie_correlativo, v.codmoneda,
                    c.id AS id_cliente,
                    COALESCE(NULLIF(c.razon_social, ''), c.nombre) AS cliente,
                    COALESCE(NULLIF(c.ruc, ''), c.documento) AS documento
             FROM pago_credito pc
             INNER JOIN venta v ON v.id = pc.id_venta
             LEFT JOIN clientes c ON c.id = v.codcliente
             WHERE pc.tipopago = 'Credito'
             ORDER BY pc.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Total abonado por cada cuota: [id_pago_credito][nro_cuota] => monto */
    public static function mdlTotalAbonado()
    {
        $stmt = Conexion::conectar()->prepare(
            "SELECT id_pago_credito, nro_cuota, SUM(monto) AS abonado FROM abono_cuota GROUP BY id_pago_credito, nro_cuota"
        );
        $stmt->execute();
        $abonos = array();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $abonos[$fila['id_pago_credito']][$fila['nro_cuota']] = (float)$fila['abonado'];
        }
        return $abonos;
    }

    public static function mdlRegistrarAbono($datos)
    {
        $stmt = Conexion::conectar()->prepare(
            "INSERT INTO abono_cuota (id_pago_credito, nro_cuota, monto, fecha, id_usuario, usuario)
             VALUES (:id_pago_credito, :nro_cuota, :monto, NOW(), :id_usuario, :usuario)"
        );
        $stmt->bindValue(':id_pago_credito', $datos['id_pago_credito'], PDO::PARAM_INT);
        $stmt->bindValue(':nro_cuota', $datos['nro_cuota'], PDO::PARAM_INT);
        $stmt->bindValue(':monto', $datos['monto']);
        $stmt->bindValue(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
        $stmt->bindValue(':usuario', $datos['usuario'], PDO::PARAM_STR);
        return $stmt->execute() ? 'ok' : 'error';
    }

    /** Ultimos abonos registrados (historial para auditoria). */
    public static function mdlUltimosAbonos($limite = 20)
    {
        $stmt = Conexion::conectar()->prepare(
            "SELECT a.*, pc.id_venta, v.serie_correlativo
             FROM abono_cuota a
             INNER JOIN pago_credito pc ON pc.id = a.id_pago_credito
             INNER JOIN venta v ON v.id = pc.id_venta
             ORDER BY a.id DESC LIMIT " . (int)$limite
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
