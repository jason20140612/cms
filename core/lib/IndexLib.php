<?php
	class IndexLib
	{
		public function __construct()
		{
			fimport('oteMod','models');
			fimport('productMod','models');
		}
		
		public function getOteDataList()
		{
			$oteMod = new OteMod();
			$list = $oteMod->getOteList();
			return $list;
		}
		public function getProductDataList()
		{
			$productMod = new ProductMod();
			$list = $productMod->getProductList();
			return $list;
		}
	}
?>
