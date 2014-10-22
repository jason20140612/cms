<?php
	class Index
	{
		private $logic;
		public function __construct()
		{
			fimport('IndexLogic','logic');
			fimport('IndexForm','form');
			$this->logic = new IndexLogic();
		}
		
		public function index()
		{
			try
			{
				global $_global;
				IndexForm::index();
				$_global['data'] =$this->logic->indexLogic();
 				template('index/index');
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}
?>
