<?php
fimport('DbBase','db');
class ProductMod extends DbBase
{
	private $tableName;
	public function __construct()
	{
		parent::__construct('product');
		$this->tableName = 'info';
	}
	public function getProductList()
	{
		$sql = "select * from ".$this->tableName;
		return $this->select($sql,'',array());
	}
	
	public function addNew($arr)
	{
		$fiels = implode(',', array_keys($arr));
		$values = array_values($arr);
		$types = parent::getValType($values);
		$v = implode(',', array_fill(0, count($values), '?'));
		$sql = "insert into ".$this->tableName." ($fiels) values($v)";
		return $this->add($sql, $types, $values);
	}	
}
?>