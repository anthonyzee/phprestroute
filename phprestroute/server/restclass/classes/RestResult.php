<?php
namespace restclass\classes;

class ResultList {
    public static $data = array();
}

class ResultType {
	public $resultClass;
	public $resultType;
	
	public function __construct($resultClass, $resultType) {
		$this->resultClass = $resultClass;
		$this->resultType = $resultType;
	}
}

class GeneralAck extends \restclass\abstracts\RestResult {
	public function getResult():string{
		$now = floor(microtime(true)*1000);
		$status="ok";
		if (!$this->resultData){
			$status="nok";
		}
		return json_encode(array('type'=>'GeneralAck', 'now' => $now, "status" => $status));
	}
}

class AuthAck extends \restclass\abstracts\RestResult {
	public function getResult():string{
		$now = floor(microtime(true)*1000);
		$status="ok";
		if ($this->resultData["status"]=="nok"){
			$status="nok";
		}
		return json_encode(array('type'=>'AuthAck', 'now' => $now, "status" => $status, "key" => $this->resultData["key"], "usertype"=>$this->resultData["usertype"]));
	}
}

array_push(ResultList::$data, 
	new ResultType(\restclass\classes\GeneralAck::class, "GeneralAck"),
	new ResultType(\restclass\classes\AuthAck::class, "AuthAck")
);

class RestResult {
	public $resultType; //timelist, timeack
	public $resultList;
	
	public function __construct($resultType, $resultList) {
		$this->resultType = $resultType;
		$this->resultList = $resultList;
	}

	public function showResult(){
		for ($i=0; $i<count(ResultList::$data); $i++){
			$itm=ResultList::$data[$i];
			if ($itm->resultType==$this->resultType){
				$resultType=new $itm->resultClass($this->resultList);
				echo $resultType->getResult();
				return true;
			}
		}
		$resultType=new \restclass\classes\TimeAck(false);
		echo $resultType->getResult();
		return false;
	}
}
?>
