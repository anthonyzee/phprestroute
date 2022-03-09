<?php
namespace restclass\abstracts;

abstract class RestMethod {
	public $reqMethodId;
	
	public function __construct($reqMethodId) {
		$this->reqMethodId = $reqMethodId;
	}
	abstract public function processMethod();
}

abstract class RestResult {
	public $resultData;

	public function __construct($resultData) {
		$this->resultData = $resultData;
	}
	abstract public function getResult();
}

abstract class RestAuth {
	public function __construct() {
	}
	abstract public function checkAuth($key);
	abstract public function loginAuth($passname, $passcode);
	abstract public function logoutAuth($key);
	abstract public function registerAuth($passname, $passcode, $usertype);
	abstract public function deleteAuth($passname);
	abstract public function changeAuth($passname, $passcode);
}

?>
