<?php
	class InfoForm
	{
		public static function add()
		{
			$fs = array();
			$fs[] = array('title','POST#','s',array(1,255));
			$fs[] = array('content','POST#','s',array(1,PHP_INT_MAX));
			return FormCheck::parse($fs);
		}	
	}
?>