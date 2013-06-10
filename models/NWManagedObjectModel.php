<?php
class NWManagedObjectModel {
	public $instance;
	function __construct() {
		settype($this->instance, 'integer');
	}
	function save() {
		$db = new SQLite3('db.sqlite3');
		$entity = $db->escapeString(str_replace("Model", "", get_class($this)));
		$cols = "";
		foreach (get_object_vars($this) as $key => $value) {
			if ($key != "instance") {
				if (gettype($this->$key) == "integer") {
					$value = $db->escapeString($value);
					$type = "INTEGER";
				}
				elseif (gettype($this->$key) == "double" || gettype($this->$key) == "float") {
					$value = $db->escapeString($value);
					$type = "REAL";
				}
				elseif (gettype($this->$key) == "string") {
					$value = $db->escapeString($value);
					$value = "\"$value\"";
					$type = "TEXT";
				}
				elseif (gettype($this->$key) == "boolean" || gettype($this->$key) == "bool") {
					$value = $db->escapeString($value);
					$type = "INTEGER";
				}
				elseif (gettype($this->$key) == "NULL") {
					$value = $db->escapeString($value);
					$type = "NULL";
				}
				elseif (gettype($this->$key) == "object" || gettype($this->$key) == "resource" || gettype($this->$key) == "array") {
					$type = "BLOB";
					// Do something with data before retrieving saving...
					$value = base64_encode(serialize($value));
					$value = $db->escapeString($value);
					$value = "\"$value\"";
				}
				$key = $db->escapeString($key);
				$cols .= "`$key` $type,";
				$insCols .= "`$key`,";
				$insVals .= "$value,";
				$updateQ .= "`$key` = $value,";
			}
			else {
				$key = $db->escapeString($key);
				$cols .= "`$key` INTEGER PRIMARY KEY AUTOINCREMENT,";
			}
		}	
		$cols = substr($cols, 0, -1);

		$insCols = substr($insCols, 0, -1);
		$insVals = substr($insVals, 0, -1);
		$updateQ = substr($updateQ, 0, -1);

		$sql = "CREATE TABLE IF NOT EXISTS $entity ($cols)";
		$db->query($sql);
		if ($this->instance != null) {
			$inst = $this->instance;
			$query = "UPDATE $entity SET $updateQ WHERE `instance` = $inst";
			$db->query($query);
		}
		else {
			$query = "INSERT INTO $entity ($insCols) VALUES ($insVals)";
			// echo $query;
			$result = $db->query($query);
			$this->instance = $db->lastInsertRowid();
		}
		$sql = "";
	}
	function dumpVars() {
		header("content-type:application/json");
		foreach (get_object_vars($this) as $key => $value) {
			echo str_pad($key, 20);
			echo str_pad(gettype($this->$key), 20);
			echo $value;
			echo "\n";
		}
	}
	function purge() {
		$db = new SQLite3('db.sqlite3');
		if ($this->instance != null) {
				$entity = $db->escapeString(str_replace("Model", "", get_class($this)));
				$db = new SQLite3('db.sqlite3');
				$inst = $this->instance;
				$db->query("DELETE FROM `$entity` WHERE `instance` = $inst");
		}
		return $self;
	}
}
?>