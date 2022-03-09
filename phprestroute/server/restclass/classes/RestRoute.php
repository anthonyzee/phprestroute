<?php
namespace restclass\classes;
require_once("RestMethods.php");
require_once("RestResult.php");
require_once("RestAuth.php");

class RestRoute {
	static function getRouteVersion(){
		return "RestRoute 1.0";
	}
	static function getRouteViewId(){
		// php take cares of return type
		// better use javascript window.location.pathname.substr(1)
		$uri=$_SERVER['REQUEST_URI'];
		return basename($uri, ".php");		
		//$_SERVER['HTTP_HOST'];
		//print_r($_REQUEST);
	}
	static function getRouteApiId(){
		$uri=$_SERVER['REQUEST_URI'];
		$uriList=explode("?", $uri);
		if (count($uriList)>1){
			$uri=$uriList[0];
		}
		return basename($uri, ".php");				
	}
	static function checkRoute($pAuth){
		for ($j=0; $j<count(AuthList::$data); $j++){
			$itm1=AuthList::$data[$j];
			if ($itm1->authName==$pAuth){
				$auth=new $itm1->authClass();
				return $auth->checkAuth("");
			}
		}
		return false;
	}
	static function processRoute($apiId){
		// execute route based on method type get post and apiId
		for ($i=0; $i<count(MethodList::$data); $i++){
			$itm=MethodList::$data[$i];

			if (
				$itm->routeApiId==$apiId && 
				strtoupper($itm->routeMethod)==strtoupper($_SERVER['REQUEST_METHOD'])
			){
				//check authentication here
				if (!RestRoute::checkRoute($itm->routeAuth)){
					return new RestResult("AuthAck", false);
				}
				$route=new $itm->methodClass($apiId);
				return $route->processMethod();
			}
		}
		return new RestResult("TimeAck", false);
	}
}
?>
