<?php

class NWManagedObjectContext {
	public $db;
	function __construct($database = 'db.sqlite3') {
		$this->db = new sqlite3($database) or die("Unable to Open DB");
	}
	function getEntitiesForStatement($entity, $statement = "", $cols = "*") {
		$db = $this->db;
		if ($statement != "") {
			$statement = "WHERE $statement";
		}
		$res = @$db->query("SELECT $cols FROM `$entity` $statement");
		if (!$res) {
			return array();
		}
		// print_r($res);
		$entities = array();
		while($row = $res->fetchArray(SQLITE3_ASSOC)) {
			$entityModel = $entity . "Model";
			$obj = new $entityModel();
			foreach ($row as $key => $value) {
				$obj->$key = $value;
			}
			array_push($entities, $obj);
		}
		return $entities;
	}
	function getEntityForInstance($entity, $instance) {
		return $this->getEntitiesForStatement($entity, "`instance` = $instance");
	}
}

?>