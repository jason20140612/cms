<?php
class Form
{

	public static function checkNum($val)
	{
		$msg = '';
		$val = intval($val);
		return preg_match('/\d+/', $val);
	}
}
?>