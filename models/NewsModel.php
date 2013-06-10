<?php
	class NewsModel extends NWManagedObjectModel {
		public $onlineid;
		public $title;
		public $href;
		public $excerpt;
		public $content;
		public $addedToCache;
		public $lastUpdate;
		public $deleted;
		public $idSlider;
		public $image;
		function __construct() {
			parent::__construct();
			settype($this->onlineid, 'integer');
			settype($this->title, 'string');
			settype($this->href, 'string');
			settype($this->excerpt, 'string');
			settype($this->content, 'string');
			settype($this->addedToCache, 'integer');
			settype($this->lastUpdate, 'integer');
			settype($this->deleted, 'integer');
			settype($this->idSlider, 'integer');
			settype($this->image, 'string');
		}
	}

?>