<?php
	fimport('DbBase','db');
	class NewMod extends DbBase
	{
		private $tableName;
	
		public function __construct()
		{
			parent::__construct('trans');
			$this->tableName = 'trans_news';
		}
		
		public function getNewList()
		{
			$sql = "select * from ".$this->tableName;
			return $this->select($sql,array(),array());
		}
	}
?>
