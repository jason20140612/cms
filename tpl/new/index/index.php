<table>
<?php foreach($_global['data'] as $k=>$v):?>
<tr>
	<td><?php echo $v['Title']?></td>
	<td><?php echo $v['Content']?></td>
</tr>
<?php endforeach;?>
</table>