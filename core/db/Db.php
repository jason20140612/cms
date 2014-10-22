<?php
class db
{

	private static $conn = array();

	private function __construct($configName, $configInfo)
	{
		if(isset(self::$conn[$configName]) && self::$conn[$configName])
		{
			return self::$conn[$configName];
		}
		$conn = new mysqli($configInfo['hostname'], $configInfo['username'], $configInfo['password'], $configInfo['database'], $configInfo['port']);
		if($conn->connect_error)
		{
			die('Connect Error (' . $conn->connect_errno . ') ' . $conn->connect_error);
		}
		$conn->set_charset('utf8');
		self::$conn[$configName] = $conn;
		return $conn;
	}

	public static function getInstance($configName)
	{
		global $_global;
		if(empty($configName))
		{
			throw new Exception("configName can not empty", 3001);
		}
		$config = $_global['config'];
		if(!isset($config['db']))
		{
			throw new Exception("db config not exists");
		}
		$dbInfo = $config['db'][$configName];
		new db($configName, $dbInfo);
		return self::$conn[$configName];
	}
}
