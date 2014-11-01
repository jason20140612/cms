<?php echo getTplFile('common/header.php');?>
<div class="container">
<form class="form-horizontal" role="form" id="addForm" method="POST" action="<?php echo $_global['site_url'].'info/doadd'?>">
  <div class="form-group">
    <label for=""name"" class="col-sm-2 control-label">名称</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" placeholder="名称" name="name">
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
    	<input type="hidden" nale="flag" value="0">
      <input type="submit" class="btn btn-default" id="submit" value="提交A库"/>
      <input type="submit" class="btn btn-default" id="submit" value="提交B库"/>
    </div>
  </div>
</form>
</div>
<?php echo getTplFile('common/footer.php');?>