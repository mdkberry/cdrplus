<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>CDR Reporter - Ext Based</title>

<script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
<style type="text/css">
a { color: blue; }
a:hover { color: white; }
thead tr { color: white;}
tbody {color: black; background: white;}
tbody input {
  border: 0 none;
  font-family: helvetica;
  font-size: 16px;
  font-weight: bold;
  text-align: center;
  width: 100%;
}
button { width: 70px; }
</style>
</head>
<body>
<div style="margin: 100px auto; background: none repeat scroll 0% 0% black; width: 600px; font-family: helvetica; text-align: center;font-weight : bold;padding-bottom: 25px;" > 
	<div style="padding: 15px 0;">
		<div style="float: left; width: 53%; text-align: right;color: yellow;">Settings</div>
		<div style="float: left; width: 30%; color: white;">?</div>
		<div style="float: left; width: 15%; "><a href="index.php" style="text-decoration: none;cursor: pointer;">Main Page</a></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding: 10px 75px; " >
		<table width="97%">
			<thead><tr>	<th width="10%">Del</th>
						<th width="20%">Ext.</th>
						<th width="40%">Username</th>
						<th width="30%">Team</th>
			</tr></thead>
		</table>
		<div style="padding: 0; font-size: 16px; height: 300px; overflow: auto;" >
		<table width="100%" style="table-layout: fixed;">
			<tbody id="userinfo">
			</tbody>
		</table>
		</div>
	</div>
	
	<div style="text-align: center;" >
		<button id="reload">Reload</button>
		<button id="update">Update</button>
		<button id="delete">Delete</button>
	</div>

</div>
</body>
<script type='text/javascript'>
	$(document).ready(function(){
		$("#reload").click();
	});
	
	$("#reload").click(function (){
        $.ajax({
            type : 'POST',
            url  : 'usermanager.php',
            async : false,
            data : 'action=read',
            dataType: 'json',
            cache: false,
            success: function(data, textStatus, jqXHR) {
				var html = "";
				for(var i=0; i < data.length; i++)
				{
					if(i==0) 
						html += "<tr id='" + data[i].id + "'>" + 
									"<td width='10%'><input type='checkbox' /></td>" +
									"<td width='20%'>" + data[i].ext   + "</td>" +
						  			"<td width='40%'>" + data[i].name  + "</td>" + 
						  			"<td width='30%'>" + data[i].group + "</td></tr>";
					else
						html += "<tr id='" + data[i].id + "'>" + 
									"<td><input type='checkbox' /></td>" +
									"<td>" + data[i].ext   + "</td>" +
						  			"<td>" + data[i].name  + "</td>" + 
						  			"<td>" + data[i].group + "</td></tr>";
				}
            	$("#userinfo").html(html);

            	$("#userinfo td").click(function(){
                	//if(!$(this).index()) return;
                	if($(this).index() < 2) return;
                	
					var html = $(this).html();
					if(html.indexOf("input") < 0)
					{
						$(this).html("<input type='text' value='" + html + "' />");
						var inp = $(this).find("input");
						$(inp[0]).focus().select();
						$(inp[0]).blur(function(){
							$(this).parent().html($(this).val());	
						});
					}
				});
            },
            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
            complete: function( jqXHR, textStatus){}
         });				
	});

	$("#update").click(function (){
		var rows = $("#userinfo tr");
		var users = new Array();
		for(var i=0, j=0; i < rows.length; i++)
		{
			users[j] =  new Array();
			users[j][0] = $(rows[i]).attr("id");
			users[j][1]   = $(rows[i]).find("td:eq(1)").html();
			users[j][2]  = $(rows[i]).find("td:eq(2)").html();
			users[j][3] = $(rows[i]).find("td:eq(3)").html();

			j++;
		}
		
        $.ajax({
            type : 'POST',
            url  : 'usermanager.php',
            async : false,
            data : 'action=update&users=' + users,
            dataType: 'text',
            cache: false,
            success: function(data, textStatus, jqXHR) {
				if(data == "ok") alert("update success.");
				else alert("update failed.");
            },
            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
            complete: function( jqXHR, textStatus){}
         });		
	});

	$("#delete").click(function (){
		
	});
	
	$("#delete").click(function (){
		var rows = $("#userinfo tr");
		var users = new Array();
		for(var i=0, j=0; i < rows.length; i++)
		{
			if(! $(rows[i]).find("td:eq(0) input")[0].checked) continue;
			 
			users[j] =  $(rows[i]).attr("id");

			j++;
		}
		
        $.ajax({
            type : 'POST',
            url  : 'usermanager.php',
            async : false,
            data : 'action=delete&users=' + users,
            dataType: 'text',
            cache: false,
            success: function(data, textStatus, jqXHR) {
				if(data == "ok") 
				{ 
					for(var i=rows.length-1; i >= 0; i--)
					{
						if(! $(rows[i]).find("td:eq(0) input")[0].checked) continue;

						$(rows[i]).remove(); 
					}
					
					alert("delete success.");
				}
				else alert("delete failed.");
            },
            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
            complete: function( jqXHR, textStatus){}
         });		
	});
</script>

</html>
