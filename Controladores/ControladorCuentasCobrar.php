<?php
namespace Controladores;

use Modelos\ModeloCuentasCobrar;

/**
 * MAD-001 (aditivo): modulo "Cuentas por cobrar".
 */
class ControladorCuentasCobrar
{
    /** Perfiles con acceso al modulo (RNF02). "Especial" cumple el rol de Contador. */
    const PERFILES_PERMITIDOS = array('Administrador', 'Especial');

    /** Dias de anticipacion para avisar que una cuota esta por vencer (RF03). */
    const DIAS_AVISO = 7;

    public static function ctrTieneAcceso()
    {
        return isset($_SESSION['perfil']) && in_array($_SESSION['perfil'], self::PERFILES_PERMITIDOS);
    }

    /** Convierte la fecha guardada (dd/mm/aaaa o aaaa-mm-dd) a DateTime. */
    private static function fecha($texto)
    {
        $texto = trim((string)$texto);
        foreach (array('d/m/Y', 'Y-m-d') as $formato) {
            $fecha = \DateTime::createFromFormat('!' . $formato, $texto);
            if ($fecha) {
                return $fecha;
            }
        }
        return null;
    }

    /**
     * Lista de cuotas con saldo. Cada cuota: comprobante, cliente, nro, vencimiento,
     * monto, abonado, saldo, estado y dias para vencer.
     */
    public static function ctrCuotas($soloPendientes = true)
    {
        $abonos = ModeloCuentasCobrar::mdlTotalAbonado();
        $hoy = new \DateTime('today');
        $cuotas = array();

        foreach (ModeloCuentasCobrar::mdlVentasCredito() as $venta) {
            $fechas = json_decode($venta['fecha'], true);
            $montos = json_decode($venta['cuota'], true);
            if (!is_array($fechas) || !is_array($montos)) {
                continue;
            }
            foreach ($montos as $i => $monto) {
                $nro = $i + 1;
                $monto = round((float)$monto, 2);
                $abonado = round($abonos[$venta['id_pago_credito']][$nro] ?? 0, 2);
                $saldo = round($monto - $abonado, 2);
                if ($soloPendientes && $saldo <= 0) {
                    continue;
                }
                $vence = self::fecha($fechas[$i] ?? '');
                $dias = $vence ? (int)$hoy->diff($vence)->format('%r%a') : null;
                $estado = $saldo <= 0 ? 'Pagada' : (($dias !== null && $dias < 0) ? 'Vencida' : 'Pendiente');

                $cuotas[] = array(
                    'id_pago_credito' => (int)$venta['id_pago_credito'],
                    'id_venta' => (int)$venta['id_venta'],
                    'comprobante' => $venta['serie_correlativo'],
                    'id_cliente' => $venta['id_cliente'],
                    'cliente' => $venta['cliente'],
                    'documento' => $venta['documento'],
                    'nro' => $nro,
                    'vence' => $vence ? $vence->format('d/m/Y') : ($fechas[$i] ?? ''),
                    'dias' => $dias,
                    'monto' => $monto,
                    'abonado' => $abonado,
                    'saldo' => $saldo,
                    'estado' => $estado,
                );
            }
        }
        return $cuotas;
    }

    /** Cuotas con saldo que vencen dentro de DIAS_AVISO dias o ya vencieron (RF03). */
    public static function ctrCuotasPorVencer($cuotas)
    {
        return array_values(array_filter($cuotas, function ($c) {
            return $c['saldo'] > 0 && $c['dias'] !== null && $c['dias'] <= self::DIAS_AVISO;
        }));
    }

    /** Saldo total pendiente por cliente (RF04). */
    public static function ctrSaldosPorCliente($cuotas)
    {
        $saldos = array();
        foreach ($cuotas as $c) {
            $clave = $c['id_cliente'] ?? $c['cliente'];
            if (!isset($saldos[$clave])) {
                $saldos[$clave] = array('cliente' => $c['cliente'], 'documento' => $c['documento'], 'cuotas' => 0, 'saldo' => 0);
            }
            $saldos[$clave]['cuotas']++;
            $saldos[$clave]['saldo'] = round($saldos[$clave]['saldo'] + $c['saldo'], 2);
        }
        usort($saldos, function ($a, $b) { return $b['saldo'] <=> $a['saldo']; });
        return $saldos;
    }

    /** Registra un abono validando el monto contra el saldo de la cuota (RF02, RNF03). */
    public static function ctrRegistrarAbono($idPagoCredito, $nroCuota, $monto)
    {
        if (!self::ctrTieneAcceso()) {
            return array('ok' => false, 'mensaje' => 'No tiene permiso para registrar abonos.');
        }
        $monto = round((float)$monto, 2);
        if ($monto <= 0) {
            return array('ok' => false, 'mensaje' => 'El monto del abono debe ser mayor a cero.');
        }
        $cuota = null;
        foreach (self::ctrCuotas(false) as $c) {
            if ($c['id_pago_credito'] == $idPagoCredito && $c['nro'] == $nroCuota) {
                $cuota = $c;
                break;
            }
        }
        if (!$cuota) {
            return array('ok' => false, 'mensaje' => 'La cuota no existe.');
        }
        if ($monto > $cuota['saldo']) {
            return array('ok' => false, 'mensaje' => 'El abono (S/ ' . number_format($monto, 2) . ') no puede ser mayor al saldo pendiente (S/ ' . number_format($cuota['saldo'], 2) . ').');
        }
        $respuesta = ModeloCuentasCobrar::mdlRegistrarAbono(array(
            'id_pago_credito' => (int)$idPagoCredito,
            'nro_cuota' => (int)$nroCuota,
            'monto' => $monto,
            'id_usuario' => $_SESSION['id'] ?? null,
            'usuario' => $_SESSION['nombre'] ?? ($_SESSION['usuario'] ?? 'desconocido'),
        ));
        return $respuesta == 'ok'
            ? array('ok' => true, 'mensaje' => 'Abono registrado. Nuevo saldo: S/ ' . number_format($cuota['saldo'] - $monto, 2))
            : array('ok' => false, 'mensaje' => 'No se pudo registrar el abono.');
    }

    public static function ctrUltimosAbonos()
    {
        return ModeloCuentasCobrar::mdlUltimosAbonos(20);
    }
}
