<?php echo getTplFile('common/header.php');?>
<div class="container">
<form class="form-horizontal" role="form" id="addForm" method="POST">
  <div class="form-group">
    <label for="title" class="col-sm-2 control-label">标题</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="title" placeholder="标题" name="title">
    </div>
  </div>
  <div class="form-group">
    <label for="content" class="col-sm-2 control-label">内容</label>
    <div class="col-sm-10">
    	<textarea rows="3" cols="" name="content" id="content" class="form-control" placeholder="内容"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="content" class="col-sm-2 control-label">图片</label>
    <div class="col-sm-10">
    		<input type="file" name="files" >
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" id="submit">提交</button>
    </div>
  </div>
</form>
</div>
<?php echo getTplFile('common/footer.php');?>