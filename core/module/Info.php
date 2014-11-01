<?php
	class Info
	{
		private $logic;
		public function __construct()
		{
			fimport('InfoForm','form');
			fimport('InfoLogic','logic');
			$this->logic = new InfoLogic();
		}
		
		public function add()
		{
			try
			{
				global $_global;
				template('info/add');
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
		}
		
		public function doAdd()
		{
			try
			{
				InfoForm::add();
				if($this->logic->addInfoLogic())
				{
					showSuccess('info/add');
				}
			}
			catch (Exception $e)
			{
				showError('info/add','添加失败',$e->getMessage());
			}
		}
	}
?>