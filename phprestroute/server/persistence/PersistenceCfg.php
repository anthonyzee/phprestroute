<?php
require_once __ROOT__.'/server/persistence/classes/persistence.php';

class PersistenceCfg {
	public static $mysql_user='root';
	public static $mysql_password='password';
	public static $mysql_pdo='mysql:host=127.0.0.1;port=3306;dbname=homestead;charset=utf8';	

	public static $db;

	public static function connect(){
        $pdo=new \PDO(
        	\PersistenceCfg::$mysql_pdo, 
        	\PersistenceCfg::$mysql_user, 
        	\PersistenceCfg::$mysql_password
       	);
        \PersistenceCfg::$db = new \PersistenceDB($pdo, "pageartifact");		
	}

	public static function random($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

}
?>
