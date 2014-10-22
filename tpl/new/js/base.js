$(function(){
	$("#submit").click(function(){
		var msg = '';
		if($.trim($("input[name='title']").val()).length == 0)
		{
			msg += '标题不能为空\n';
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
		var obj = new Object();
		obj.title = $.trim($("input[name='title']").val());
		obj.content = $.trim($("textarea[name='content']").val());
		$.ajax({
			url:SITE_URL+'new'+'/add',
			type:'POST',
			data:obj,
			dataType:'json',
			success:function(result)
			{
				var html='';
				if(result.status == 1)
				{
					html += "<tr><td>"+result.title+"</td>"+result.content+"</tr>";
				}
				$("#newList").appendTo(html);
			}
		});
	});
})