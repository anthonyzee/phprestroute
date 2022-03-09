<?php
namespace restclass\classes;
require_once(__ROOT__."/server/restclass/abstracts/RestAbstract.php");

class MethodType {
	public $methodClass;
	public $routeApiId;
	public $routeMethod;
	public $routeAuth;
	
	public function __construct($methodClass, $routeApiId, $routeMethod, $routeAuth) {
		$this->methodClass = $methodClass;
		$this->routeApiId = $routeApiId;
		$this->routeMethod = $routeMethod;
		$this->routeAuth = $routeAuth;
	}
}

class MethodList {
    public static $data = array();
}

class Dummy extends \restclass\abstracts\RestMethod {
	public function processMethod(){
		return new RestResult("TimeAck", true);
	}
}
class UserLogin extends \restclass\abstracts\RestMethod {
	public function processMethod(){
		// getting the parameters
		$body = file_get_contents('php://input');
        $changes = json_decode($body);

		$passName=$changes->passname;
		$passCode=$changes->passcode;

		$result=array("status"=>"nok", "key"=>"", "usertype"=>"");
		// sub out login processing to RestAuth
		for ($j=0; $j<count(\restclass\classes\AuthList::$data); $j++){
			$itm1=\restclass\classes\AuthList::$data[$j];
			if ($itm1->authName=="simple-auth"){
				$auth=new $itm1->authClass();
				$result=$auth->loginAuth($passName, $passCode);
			}
		}

		return new RestResult("AuthAck", $result);
	}
}
class UserRegister extends \restclass\abstracts\RestMethod {
	public function processMethod(){
		// getting the parameters
		$body = file_get_contents('php://input');
        $changes = json_decode($body);

		$passName=$changes->passname;
		$passCode=$changes->passcode;

		$result=array("status"=>"nok", "key"=>"", "usertype"=>"");
		
		// sub out login processing to RestAuth
		for ($j=0; $j<count(\restclass\classes\AuthList::$data); $j++){
			$itm1=\restclass\classes\AuthList::$data[$j];
			if ($itm1->authName=="simple-auth"){
				$auth=new $itm1->authClass();
				$result=$auth->registerAuth($passName, $passCode, "user");
			}
		}

		return new RestResult("AuthAck", $result);
	}
}
class UserDelete extends \restclass\abstracts\RestMethod {
	public function processMethod(){
		// getting the parameters
		$body = file_get_contents('php://input');
        $changes = json_decode($body);

		$passName=$changes->passname;
		$result=array("status"=>"nok", "key"=>"", "usertype"=>"");

		// sub out login processing to RestAuth
		for ($j=0; $j<count(\restclass\classes\AuthList::$data); $j++){
			$itm1=\restclass\classes\AuthList::$data[$j];
			if ($itm1->authName=="simple-auth"){
				$auth=new $itm1->authClass();
				$result=$auth->deleteAuth($passName);
			}
		}

		return new RestResult("AuthAck", $result);
	}
}
class UserChange extends \restclass\abstracts\RestMethod {
	public function processMethod(){
		// getting the parameters
		$body = file_get_contents('php://input');
        $changes = json_decode($body);

		$passName=$changes->passname;
		$passCode=$changes->passcode;

		$result=array("status"=>"nok", "key"=>"", "usertype"=>"");

		// sub out login processing to RestAuth
		for ($j=0; $j<count(\restclass\classes\AuthList::$data); $j++){
			$itm1=\restclass\classes\AuthList::$data[$j];
			if ($itm1->authName=="simple-auth"){
				$auth=new $itm1->authClass();
				$result=$auth->changeAuth($passName, $passCode);
			}
		}

		return new RestResult("AuthAck", $result);
	}
}

array_push(MethodList::$data, 
	new \restclass\classes\MethodType(
		\restclass\classes\Dummy::class, "dummy", "GET", "none"),
	new \restclass\classes\MethodType(
		\restclass\classes\UserLogin::class, "userlogin", "POST", "none"),
	new \restclass\classes\MethodType(
		\restclass\classes\UserRegister::class, "userregister", "POST", "none"),
	new \restclass\classes\MethodType(
		\restclass\classes\UserDelete::class, "userdelete", "POST", "simple-auth"),
	new \restclass\classes\MethodType(
		\restclass\classes\UserChange::class, "userchange", "POST", "simple-auth"),		
);

?>
