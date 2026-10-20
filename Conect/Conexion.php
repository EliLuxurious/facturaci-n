<?php
namespace Conect;

 date_default_timezone_set('America/Lima'); 
 class Conexion{

        const HOST = 'localhost';
        const USER = 'root';
        const PASSWORD = '';
        const BDNAME = 'dbfacturacion';
        
        public static function conectar() {
            //Para PHP las variables se declaran con el signo $
        $link = new \PDO("mysql:host=".self::HOST."; dbname=".self::BDNAME.";",
                        self::USER, self::PASSWORD);
        $link->exec("set names utf8");
        return $link;
    }
}
?>