<?php
	class News
	{
		private $logic;
		public function __construct()
		{
			fimport('NewLogic','logic');
			fimport('NewForm','form');
			$this->logic = new NewLogic();
		}
		
		public function add()
		{
			try
			{
				global $_global;
				NewForm::add();
				$this->logic->addNewLogic();
				
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}
?>