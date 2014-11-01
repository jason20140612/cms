<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $_global['title']?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo getCssFile('bootstrap.css')?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo getCssFile('bootstrap-theme.css')?>" />
</head>
<body>
<div class="jumbotron">
  <h1><?php echo $_global['title'];?></h1>
  <?php foreach ($_global['message'] as $v):?>
  <p><?php echo $v;?></p>
  <?php endforeach;?>
  <p><a class="btn btn-primary btn-lg" role="button" href="<?php echo  $_global['jumpUrl']?>">点击跳转</a></p>
</div>
</body>
</html>