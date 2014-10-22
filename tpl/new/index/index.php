<?php echo getTplFile('common/header.php');?>
<table id="newList">
<th>列表</th>
<?php foreach($_global['data'] as $k=>$v):?>
<tr>
	<td><?php echo $v['Title']?></td>
	<td><?php echo $v['Content']?></td>
</tr>
<?php endforeach;?>
</table>
<?php echo getTplFile('common/footer.php')?>

<form id="addForm" method="post">
<table>
<th>添加</th>
<tr>
	<td>
		标题
	</td>
	<td>
		<input type="text" name="title">
	</td>
</tr>
<tr>
	<td>
		内容
	</td>
	<td>
		<textarea rows="3" cols="20" name="content"></textarea>
	</td>
</tr>
<tr>
	<td>
		<input type="submit" value="提交" id="submit">
	</td>
	<td>
		<input type="reset" value="重置">
	</td>
</tr>
</table>
</form>