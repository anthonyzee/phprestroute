<?php
class PersistenceDB {
	private $db;
	private $persistence_table;

	function __construct(PDO $db, $persistence_table) {
		$this->db = $db;
		$this->persistence_table = $persistence_table;
	}

	public function getObjectQuery($sql) {			
		$statement = $this->db->prepare($sql);
		
		$statement->execute();
		$changes = array();
		
		foreach ($statement->fetchAll(PDO::FETCH_CLASS) as $content) {
			$changes[]=$content;
		}
		
		return $changes;
	}
	
	public function getObjectChanges($id, $where) {		
		$sql="SELECT * FROM `{$id}`".$where;				
		$statement = $this->db->prepare($sql);
		
		if ($statement->execute()){
		}else{
			echo json_encode('{"error":"error"}');
			exit;
			//echo $statement->errorInfo()[2];
		}
		$changes = array();
		
		foreach ($statement->fetchAll(PDO::FETCH_CLASS) as $content) {
			$changes[]=$content;
		}
		
		return $changes;
	}

	public function applyObjectChanges($id, $now, array $changes) {
		$values = array();
		$keys="";
		$keysValue="";
		$keysUpdate="";
		foreach ($changes[0] as $key => $value){
			if ($key=="id"){
				$keys="`".$key."`";
				$keysValue=":".$key;
			}else{
				if ($keysUpdate==""){
					$keysUpdate="`".$key."`=:".$key;
				}else{
					$keysUpdate.=",`".$key."`=:".$key;
				}
				$keys.=",`".$key."`";
				$keysValue.=",:".$key;
			}
		}

		$sql="INSERT INTO `".$id."` (".$keys.") VALUES (".$keysValue.")";
		if ($keysUpdate!=""){
			$sql.=" ON DUPLICATE KEY UPDATE ".$keysUpdate;
		}
		
		$statement = $this->db->prepare($sql);
		$sql2="DELETE FROM `".$id."` WHERE id=:id";
		$statement2 = $this->db->prepare($sql2);
		
		foreach ($changes as $change) {
			$deleted=false;
			if (isset($change->deleted)){
				if (!is_null($change->deleted)){
					
					if ($change->deleted=="1"||$change->deleted==1||$change->deleted==true){
						$deleted=true;
					}
				}
			}
			
			if ($deleted){
				if ($statement2->execute(array(':id' => $change->id))){
				}else{
					echo json_encode('{"error":"error"}');
					exit;
				}
			}else{
				$values=array();
				
				foreach ($change as $key => $value){
					$newValue=array(":".$key => $value);
					$values=array_merge($values,$newValue);
				}
				//echo $sql;
				//exit();
				if ($statement->execute($values)){
				}else{
					echo json_encode('{"error":"'.$statement->errorInfo()[0].'"}');
					exit;
				};
			}			
		}
	}
}
?>