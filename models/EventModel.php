<?php
	class EventModel extends NWManagedObjectModel {
		public $name;
		public $place;
		public $latitude;
		public $longitude;
		public $startTime;
		public $endTime;
		function __construct() {
			parent::__construct();
			settype($this->name, 'string');
			settype($this->place, 'string');
			settype($this->latitude, 'float');
			settype($this->longitude, 'float');
			settype($this->startTime, 'integer');
			settype($this->endTime, 'integer');
		}
	}

?>