<?php
if (!defined('__ROOT__')) {
	define('__ROOT__', realpath(dirname(__FILE__)).'/../..');
}
require_once(__ROOT__."/server/restclass/RestInit.php");

// -- uncomment below for access to mysql db connection, 
// -- make sure you configured mysql in PersistenceCfg.php.

// require_once(__ROOT__.'/server/persistence/PersistenceCfg.php');
// \PersistenceCfg::connect();

$apiId=restclass\classes\RestRoute::getRouteApiId();
$apiResult=restclass\classes\RestRoute::processRoute($apiId);
$apiResult->showResult();

?>