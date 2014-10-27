	<?php echo getTplFile('common/header.php');?>
<div class="container">
	<?php if(!empty($_global['data']['ote'])):?>
<table id="newList" class='table table-striped table-bordered'>
<tr>
	<th>名称</th>
	<th>图片</th>
	<th>内容</th>
</tr>
<?php foreach($_global['data']['ote'] as $k=>$v):?>
<tr>
	<td><?php echo $v['name']?></td>
	<td><?php echo $v['pic']?></td>
	<td><?php echo $v['content']?></td>
</tr>
<?php endforeach;?>
</table>
<?php endif;?>

<?php if(!empty($_global['data']['product'])):?>
<table id="newList" class='table table-striped table-bordered'>
<tr>
	<th>名称</th>
	<th>图片</th>
	<th>内容</th>
</tr>
<?php foreach($_global['data']['product'] as $k=>$v):?>
<tr>
	<td><?php echo $v['name']?></td>
	<td><?php echo $v['pic']?></td>
	<td><?php echo $v['content']?></td>
</tr>
<?php endforeach;?>
</table>
<?php endif;?>
</div>
<?php echo getTplFile('common/footer.php')?>
