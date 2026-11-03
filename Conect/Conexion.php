<?php
namespace Conect;

date_default_timezone_set('America/Lima'); 

/**
 * MPR-001 (preventivo): las credenciales ya NO estan escritas en el codigo.
 * Se leen, en este orden:
 *   1) Variables de entorno del servidor (Docker, Apache SetEnv, panel del hosting)
 *   2) El archivo .env en la raiz del proyecto (no se sube a Git y Apache bloquea su descarga)
 * Ver .env.example para la lista de variables necesarias.
 */
class Conexion {

    private static $archivoEnv = null;

    private static function config($clave, $porDefecto = null) {
        $valor = getenv($clave);
        if ($valor !== false) {
            return $valor;
        }
        if (self::$archivoEnv === null) {
            $ruta = dirname(__DIR__) . '/.env';
            self::$archivoEnv = is_readable($ruta) ? (parse_ini_file($ruta, false, INI_SCANNER_RAW) ?: array()) : array();
        }
        return array_key_exists($clave, self::$archivoEnv) ? self::$archivoEnv[$clave] : $porDefecto;
    }

    public static function conectar() {
        $host = self::config('DB_HOST', 'localhost');
        $port = self::config('DB_PORT', '3306');
        $dbname = self::config('DB_NAME');
        $user = self::config('DB_USER');
        $pass = self::config('DB_PASSWORD', '');

        if (empty($dbname) || empty($user)) {
            self::errorConfiguracion('Faltan las variables DB_NAME o DB_USER. Cree el archivo .env a partir de .env.example.');
        }

        try {
            $link = new \PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8", $user, $pass);
        } catch (\PDOException $e) {
            // El detalle tecnico va al log del servidor, nunca a la pantalla
            error_log('Error de conexion a la base de datos: ' . $e->getMessage());
            self::errorConfiguracion('No se pudo conectar a la base de datos. Revise DB_HOST, DB_PORT, DB_NAME, DB_USER y DB_PASSWORD en el archivo .env.');
        }
        $link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $link->exec("set names utf8");
        return $link;
    }

    /** RNF02: mensaje descriptivo en lugar de un error tecnico o una pantalla en blanco. */
    private static function errorConfiguracion($mensaje) {
        http_response_code(500);
        echo '<div style="font-family:sans-serif;max-width:640px;margin:40px auto;padding:20px;border:1px solid #d33;border-radius:8px">'
           . '<h3 style="color:#d33">Error de configuración del sistema</h3><p>' . htmlspecialchars($mensaje) . '</p></div>';
        exit;
    }
}
