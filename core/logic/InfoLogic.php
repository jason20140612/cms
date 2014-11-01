<?php
	class InfoLogic
	{
		public function __construct()
		{
			fimport('oteMod','models');
			fimport('UploadFile','common');
		}
		public function addInfoLogic()
		{
			$oteMod = new OteMod();
			$arr = array();
			$arr['name'] = $_POST['name'];
			$arr['content'] = $_POST['content'];
			$arr['addTime'] = time();
			print_r($_FILES);exit;
			if(!$oteMod->addInfo($arr))
			{
				throw new Exception('添加失败');
			}
			return true; 
		}	
	}
?>