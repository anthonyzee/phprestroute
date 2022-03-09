<?php
namespace restclass\classes;

require_once(__ROOT__."/server/restclass/abstracts/RestAbstract.php");
require_once(__ROOT__."/server/restclass/classes/RestMethods.php");
require_once(__ROOT__."/server/restclass/classes/RestResult.php");
require_once(__ROOT__."/server/restclass/classes/RestRoute.php");

// RestMethod - Register api call method name here --

class Call extends \restclass\abstracts\RestMethod {
	public function processMethod(){
		return new RestResult("TimeList", true);
	}
}
class Callp extends \restclass\abstracts\RestMethod {
	public function processMethod(){
		return new RestResult("TimeAck", true);
	}
}

array_push(\restclass\classes\MethodList::$data, 
	new \restclass\classes\MethodType(
		\restclass\classes\Call::class, "dev", "GET", "none"),
	new \restclass\classes\MethodType(
		\restclass\classes\Callp::class, "dev", "POST", "simple-auth")
);

// RestResult - Register api call result type here so that you can use in your method --

class TimeList extends \restclass\abstracts\RestResult {
	public function getResult():string{
		// set header, environment
		// prepare the result according to result type
		// echo json
		$changes=$this->resultData;
		$now = floor(microtime(true)*1000);
		return json_encode(array('type'=>'TimeList', 'now' => $now, "updates" => $changes));;
	}
}

class TimeAck extends \restclass\abstracts\RestResult {
	public function getResult():string{
		$now = floor(microtime(true)*1000);
		$status="ok";
		if (!$this->resultData){
			$status="nok";
		}
		return json_encode(array('type'=>'TimeAck', 'now' => $now, "status" => $status));
	}
}

array_push(\restclass\classes\ResultList::$data, 
	new \restclass\classes\ResultType(\restclass\classes\TimeList::class, "TimeList"),
	new \restclass\classes\ResultType(\restclass\classes\TimeAck::class, "TimeAck")
);

?>
