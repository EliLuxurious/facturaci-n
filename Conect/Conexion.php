<?php
namespace Conect;

date_default_timezone_set('America/Lima'); 

class Conexion {

    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const BDNAME = 'dbfacturacion';
    
    public static function conectar() {
        $host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? self::HOST);
        $user = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? self::USER);
        $pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($_ENV['DB_PASSWORD'] ?? ($_ENV['DB_PASS'] ?? self::PASSWORD)));
        $dbname = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? self::BDNAME);
        $port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '3306');

        $link = new \PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8",
                        $user, $pass);
        $link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $link->exec("set names utf8");
        return $link;
    }
}
?>
