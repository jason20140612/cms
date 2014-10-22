<?php
	class IndexLib
	{
		public function __construct()
		{
			fimport('NewMod','models');
		}
		
		public function getDataList()
		{
			$newMod = new NewMod();
			$list = $newMod->getNewList();
			return $list;
		}
	}
?>
