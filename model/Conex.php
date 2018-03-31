<?php
include_once __DIR__ . "/../config.php";

class Conex implements IConex{
             
    private static $db = DB;
    private static $host = DB_HOST;
    private static $banco = DB_NAME;
    private static $user = DB_USER;
    private static $pass = DB_PASS;
    private static $port = DB_PORT;
    private static $instance;
     
    public static function doConnect(){
            if(empty(self::$instance)) {
                try {
                    self::$instance = new PDO(self::$db.":host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$banco, self::$user, self::$pass );
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); // ERRMODE_SILENT
                }
                catch(PDOException $e) {
                    echo 'Nao foi possivel obter uma conexao com o banco de dados. '.$e->getMessage();
                }
            }
            return self::$instance;
    }
			
}


?>
