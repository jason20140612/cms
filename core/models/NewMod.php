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
		
		public function addNew($arr)
		{
			$fiels = implode(',', array_keys($arr));
			$values = array_values($arr);
			$types = parent::getValType($values);
			$v = array_fill(0, count($values), '?');
			$sql = "insert into ".$this->tableName." ($fiels) values($v)";
			return $this->add($sql, $types, $values);
		}
		
		public function getOneNew($id)
		{
			$sql = "select * from ".$this->tableName." where Id=?";
			return $this->getRow($sql, 'i', array($id));
		}
	}
?>
