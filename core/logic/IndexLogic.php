<?php
	class IndexLogic
	{
		private $lib;
		public function __construct()
		{
			fimport('IndexLib','lib');
			$this->lib = new IndexLib();
		}
		
		public function indexLogic()
		{
			$data = $this->lib->getDataList();
			return $data;
		}
	}
?>
