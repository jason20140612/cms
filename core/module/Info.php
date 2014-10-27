<?php
	class Info
	{
		public function __construct()
		{
// 			fimport('InfoForm','form');
// 			fimoprt('InfoLogic','logic');
		}
		
		public function add()
		{
			try
			{
				template('info/add');
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}
?>