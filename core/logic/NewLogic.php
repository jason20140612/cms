<?php
	class NewLogic
	{
		public function __construct()
		{
			fimport('NewMod','models');
		}
		
		public function addNewLogic()
		{
			$newMod = new NewMod();
			$arr = array();
			$arr['Title'] = $_POST['title'];
			$arr['Content'] = $_POST['content'];
			$id = $newMod->addNew($arr);
			$result  = array();
			if($id)
			{
				$result['status'] = 1;
				$result['new'] = $newMod->getOneNew($id);
			}
			else
			{
				$result['status'] = 0;
			}
			echo json_encode($result);
		}
	}
?>