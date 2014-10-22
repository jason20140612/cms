<?php
fimport('Db','db');
class DbBase
{
	private $db;

	private $result = FALSE;

	private $msg;

	private $dev;

	function __construct($dbName)
	{
		global $_global;
		$this->db = Db::getInstance($dbName);
		$config = $_global['config'];
		$this->dev = $config['setting']['debug'];
	}

	public function add($query, $types, array $values)
	{
		$stmt = $this->db->prepare($query);
		if($stmt)
		{
			$this->execQuery($stmt, $types, $values);
			if($this->result)
			{
				$last = $this->db->insert_id;
				if($last == 0)
				{
					$last = true;
				}
			}
			$stmt->close();
			return $last;
		}
		return false;
	}

	public function update($query, $types, array $values)
	{
		$stmt = $this->db->prepare($query);
		if($stmt)
		{
			$this->execQuery($stmt, $types, $values);
			$stmt->close();
			if($this->result)
			{
				return true;
			}
		}
		return false;
	}

	public function delete($query, $types, array $values)
	{
		$stmt = $this->db->prepare($query);
		if($stmt)
		{
			$this->execQuery($stmt, $types, $values);
			$stmt->close();
			if($this->result)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * 获取一条记录，自动增加limit 1
	 *
	 * @param string $query        	
	 * @param string $types        	
	 * @param array $values        	
	 * @return array | boolean
	 */
	public function getRow($query, $types, array $values)
	{
		return $this->select($query . ' limit 1', $types, $values, true);
	}

	/**
	 * 获取第一个字段 主要用于count
	 *
	 * @param string $query        	
	 * @param string $types        	
	 * @param array $values        	
	 * @return boolean | string
	 */
	public function getOne($query, $types, array $values)
	{
		$data = $this->getRow($query, $types, $values);
		return array_pop($data);
	}

	/**
	 * 执行查询语句
	 *
	 * @param string $query        	
	 * @param string $types        	
	 * @param array $values        	
	 * @param boolean $limitOne        	
	 * @return array string
	 */
	public function select($query, $types, array $values, $limitOne = FALSE)
	{
		$stmt = $this->db->prepare($query);
		if($stmt)
		{
			$result = array();
			$this->execQuery($stmt, $types, $values);
			if($this->result)
			{
				$result = $this->fetchResult($stmt, $limitOne);
			}
			$stmt->close();
			return $result;
		}
		return false;
	}

	/**
	 * 处理SQL语句绑定时 in 语句的值绑定 把2维数组转成1维
	 * 
	 * @param array $values        	
	 * @return array
	 */
	private function prepareInValues(array $values)
	{
		$newValue = array();
		foreach($values as $v)
		{
			if(is_array($v))
			{
				foreach ($v as $subValue)
				{
					$newValue[] = $subValue;
				}
			}
			else
			{
				$newValue[] = $v;
			}
		}
		return $newValue;
	}

	/**
	 * 执行语句
	 *
	 * @param \mysqli_stmt $stmt        	
	 * @param string $types        	
	 * @param array $values
	 * @return boolean
	 */
	private function execQuery(mysqli_stmt $stmt, $types, array $values)
	{
		$this->bindParams($stmt, $types, $this->prepareInValues($values));
		$this->result = $stmt->execute();
		if($this->result)
		{
			return true;
		}
		else
		{
			$this->setErrorMsg();
			return false;
		}
	}

	public function __destruct()
	{
		if(is_resource($this->db))
		{
			$this->db->close();
		}
	}

	private function setErrorMsg()
	{
		$this->msg = $this->db->error . ',code:' . $this->db->errno;
		if($this->dev == 1)
		{
			die('db error:' . $this->msg);
		}
		return FALSE;
	}

	public function getLastError()
	{
		return $this->msg;
	}

	private function bindParams(mysqli_stmt $stmt, $types, array $values)
	{
		if($values)
		{
			$var = array();
			$var[] = $types;
			foreach($values as $k => $v)
			{
				$var[] = &$values[$k]; // not use &$v
			}
			call_user_func_array(array($stmt,'bind_param'), $var);
			unset($values);
		}
	}

	private function fetchResult($result, $limitOne = FALSE)
	{
		$array = array();
		$variables = array();
		$data = array();
		$meta = $result->result_metadata();
		while($field = $meta->fetch_field())
		{
			$variables[] = &$data[$field->name];
		}
		call_user_func_array(array($result,'bind_result'), $variables);
		
		$i = 0;
		while($result->fetch())
		{
			$array[$i] = array();
			foreach($data as $k => $v)
			{
				$array[$i][$k] = $v;
			}
			$i ++;
		}
		unset($data);
		if($limitOne)
		{
			return isset($array[0])? $array[0] :array();
		}
		return $array;
	}
}