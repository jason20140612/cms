<?php
	class NewForm
	{
		public static function add()
		{
			$fs = array();
			$fs[] = array('title','POST','i',array(1,255));
			$fs[] = array('title','POST','i',array(1,PHP_INT_MAX));
			return FormCheck::parse($fs);
		}
	}
?>