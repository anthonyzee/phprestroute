<?php
namespace restclass\classes;
require_once(__ROOT__."/server/restclass/abstracts/RestAbstract.php");

class AuthType {
	public $authClass;
	public $authName;
	
	public function __construct($authClass, $authName) {
		$this->authClass = $authClass;
		$this->authName = $authName;
	}
}

class AuthList {
    public static $data = array();
}

class None extends \restclass\abstracts\RestAuth {
	public function checkAuth($key){
		return true;
	}
	public function loginAuth($passname, $passcode){
		return true;
	}
	public function logoutAuth($key){
		return true;
	}
	public function registerAuth($passname, $passcode, $usertype){
		return true;
	}
	public function deleteAuth($passname){
		return true;
	}
	public function changeAuth($passname, $passcode){
		return true;
	}
}

class Standard extends \restclass\abstracts\RestAuth {
	public function checkAuth($key){
		$key1=$_REQUEST["k"];
        $sql="SELECT * FROM `pageuser` WHERE `apikey`='".$key1."'";
        $changes = \PersistenceCfg::$db->getObjectQuery($sql);
        if (count($changes)==0){
        	return false;
        }
		return true;
	}
	public function loginAuth($passname, $passcode){
		$sql="SELECT * FROM `pageuser` WHERE `passname`='".$passname."'";
		$changes = \PersistenceCfg::$db->getObjectQuery($sql);
		if (count($changes)==0){
			return array("status"=>"nok", "key"=>"", "usertype"=>"");
		}
		if (!password_verify($passcode, $changes[0]->passcode)){
			return array("status"=>"nok", "key"=>"", "usertype"=>"");
		}
		$apikey = base64_encode(\PersistenceCfg::random(40));
		$usertype=$changes[0]->usertype;
		$sql="UPDATE `pageuser` SET `apikey`='".$apikey."' WHERE `passname`='".$passname."'";
		$changes = \PersistenceCfg::$db->getObjectQuery($sql);
		return array("status"=>"ok", "key"=>$apikey, "usertype"=>$usertype);
	}
	public function logoutAuth($key){
		$sql="UPDATE `pageuser` SET `apikey`='' WHERE `apikey`='".$key."'";
		$changes = \PersistenceCfg::$db->getObjectQuery($sql);
		return array("status"=>"ok", "key"=>"", "usertype"=>"");
	}
	public function registerAuth($passname, $passcode, $usertype){
		$sql="INSERT INTO `pageuser` (`id`, `passname`, `passcode`, `usertype`, `apikey`) VALUES ('".
			$passname."','".$passname."','".password_hash($passcode,PASSWORD_DEFAULT)."','".$usertype."','')";
		$changes = \PersistenceCfg::$db->getObjectQuery($sql);
		return array("status"=>"ok", "key"=>"", "usertype"=>"");
	}
	public function deleteAuth($passname){
		$sql="DELETE FROM `pageuser` WHERE `passname`='".$passname."'";
		$changes = \PersistenceCfg::$db->getObjectQuery($sql);
		return array("status"=>"ok", "key"=>"", "usertype"=>"");
	}
	public function changeAuth($passname, $passcode, ){
		$sql="UPDATE `pageuser` SET `passcode`='".password_hash($passcode,PASSWORD_DEFAULT)."' WHERE `passname`='".$passname."'";
		$changes = \PersistenceCfg::$db->getObjectQuery($sql);
		return array("status"=>"ok", "key"=>"", "usertype"=>"");
	}
}

array_push(AuthList::$data, 
	new AuthType(\restclass\classes\Standard::class, "simple-auth", "GET"),
	new AuthType(\restclass\classes\None::class, "none", "GET"),
);

?>
