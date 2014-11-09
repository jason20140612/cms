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
			if(!empty($_FILES['files']))
			{
				$upload = new UploadFile($_FILES['files'],'adv');
				//保存图片
				$rs = $upload->save();
				//生成缩略图
//				$upload->thumb($upload->file['localTarget']);
				//图片水印
				$upload->water($upload->file['localTarget'],SITE_ROOT.'/public/water/water.png');
				var_dump($rs);exit;
			}
			if(!$oteMod->addInfo($arr))
			{
				throw new Exception('添加失败');
			}
			return true; 
		}	
	}
?>