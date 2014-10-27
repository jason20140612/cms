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
			$data['ote'] = $this->lib->getOteDataList();
			$data['product'] = $this->lib->getProductDataList();
			return $data;
		}
	}
?>
