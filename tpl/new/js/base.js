$(function(){
	$("#submit").click(function(){
		var msg = '';
		if($.trim($("input[name='name']").val()).length == 0)
		{
			msg += '名称不能为空\n';
		}
		if($.trim($("textarea[name='content']").val()).length == 0)
		{
			msg +="内容不能为空\n";
		}
		if(msg.length > 0)
		{
			alert(msg);
			return false;
		}
		else
		{
			$("#addForm").submit();
		}
	});
})