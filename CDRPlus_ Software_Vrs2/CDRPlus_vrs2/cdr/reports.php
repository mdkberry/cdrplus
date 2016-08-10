<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>CDR Reporter - Ext Based</title>

<script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
<script type='text/javascript' src='js/jquery-ui.js'></script>
<link rel='stylesheet' type='text/css' href='css/jquery-ui.css' />
<link rel='stylesheet' type='text/css' href='css/parseTheme.css' />

<style type="text/css">
a { color: blue; text-decoration: none;}
a:hover { color: white; cursor: pointer; }
.datepicker {
    background: url("image/calendar.png") no-repeat scroll 0 0 transparent;
    cursor: pointer;
    float: left;
    height: 20px;
    width: 20px;
	position: relative;
	left: 2px;
}
input {height: 13px;}
td {overflow: hidden; }	
tr.selected {background-color: blue;}
</style>
</head>
<body>
<div style="margin: 100px auto; background: none repeat scroll 0% 0% black; width: 600px; font-family: helvetica; text-align: center;font-weight : bold;padding-bottom: 25px; color: white;" > 
	<div style="padding: 15px 0;font-size: 16px;">
		<div style="float: left; width: 53%; text-align: right;color: yellow;">CALL REPORTS</div>
		<div style="float: left; width: 30%; color: white;">?</div>
		<div style="float: left; width: 15%; "><a href="index.php" style="text-decoration: none;cursor: pointer;">MAIN PAGE</a></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="text-align: left; padding: 10px;font-size: 14px;">
		<div style="padding: 10px; " ><a id="load_report" href="javascript:void(0);">Load Report</a></div>
		<hr style="padding: 0px; background-color: white; border: 1px solid white;" />
		
		<div id="individual_schedule" style="height: 210px;">
			<div style="padding: 10px;" >
				<div style="float: left; line-height: 21px; width: 130px;" >Email address :</div>
				<input id="emailaddr" type="text" style="float: left; width: 250px; " />
				<div style="clear: both;" ></div>
			</div>	
			<div style="padding: 10px;" >
				<div style="float: left; line-height: 21px; width: 130px;" >Extension List :</div>
				<input id="extensionlist" type="text" style="float: left; width: 420px; " />
				<div style="clear: both;" ></div>
			</div>	
			<div style="padding: 10px;" >
				<div style="float: left; line-height: 21px; width: 130px;" >Date Filter :</div>
				<span style="float: left;">From:&nbsp;&nbsp;</span> <input id="fromdate" type="text" style="float: left; width: 100px; " /> <div id="frompicker" class="datepicker"></div>
				<span style="float: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To:&nbsp;&nbsp;</span> <input id="todate" type="text" style="float: left; width: 100px; " /> <div id="topicker" class="datepicker"></div>
				<div style="clear: both;" ></div>
			</div>	
			<div style="padding: 10px;" >
				<div style="float: left; line-height: 21px; width: 130px;" >Schedule :</div>
				<select id="period" style="float: left; width: 100px; " >
					<option value="empty"></option>
					<option value="daily">Daily</option>
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
				</select>
				<div style="clear: both;" ></div>
			</div>	
			<div style="padding: 10px;" >
				<div style="float: left; line-height: 21px; width: 130px;" >Report Type :</div>
				<input id="type_callcount" type="checkbox" style="float: left; width: 30px; margin: 5px 0; " /><span style="float: left;line-height: 23px;">Call Counts</span>
				<input id="type_detailed" type="checkbox" style="float: left; width: 30px; margin: 5px 0 5px 20px;  " /><span style="float: left;line-height: 23px;">Detailed report</span>
				<div style="clear: both;" ></div>
			</div>	
		</div>
		
		<div id="schedule_panel" style="height: 210px; display: none; text-align: center;" >
			<table width="97%" >
				<thead><tr>	<th width="5%">Del</th>
							<th width="22%">Email</th>
							<th width="31%">Ext. List</th>
							<th width="12%">From</th>
							<th width="12%">To</th>
							<th width="8%">Period</th>
							<th width="10%">Type</th>
				</tr></thead>
			</table>
			<div style="padding: 0; font-size: 16px; height: 185px; overflow: auto;" >
				<table width="100%" style="table-layout:fixed; font-size: 12px;">
					<tbody id="schedules" style="background-color: white; color: black;	">
					</tbody>
				</table>
			</div>			
		</div>
		<hr style="padding: 0px; background-color: white; border: 1px solid white;margin-bottom: 35px;" />
		
		<div id="buttonpanel1">
			<div style="padding: 0; width: 20%; text-align: center; float: left;" ><a id="save_report" href="javascript:void(0);">Save Report</a></div>
			<div style="padding: 0; width: 20%; text-align: center; float: left; display:none;" ><a id="update_report" href="javascript:void(0);">Update Report</a></div>
			<div style="padding: 0; width: 60%; text-align: center; float: left;" ><a id="display_report" href="javascript:void(0);">Display Report</a></div>
			<div style="padding: 0; width: 20%; text-align: center; float: left;" ><a id="send_now" href="javascript:void(0);">Send Now</a></div>
			<div style="clear: both;" ></div>
		</div>
		<div id="buttonpanel2" style="display: none;">
			<div style="padding: 0; width: 20%; text-align: center; float: left;" ><a id="createschedule" href="javascript:void(0);">New</a></div>
			<div style="padding: 0; width: 60%; text-align: center; float: left;" ><a id="loadschedule" href="javascript:void(0);">Load</a></div>
			<div style="padding: 0; width: 20%; text-align: center; float: left;" ><a id="deleteschedule" href="javascript:void(0);">Delete</a></div>
			<div style="clear: both;" ></div>
		</div>
	</div>
</div>
</body>
<script type='text/javascript'>
	function validateReportSchedule()
	{
		var email = $("#emailaddr").val();
		var extlist = $("#extensionlist").val();
		var fromdate = $("#fromdate").val();
		var todate = $("#todate").val();
		var period = $("#period").val();
		var type_callcount = $("#type_callcount")[0].checked;
		var type_detailed = $("#type_detailed")[0].checked;

		var errmsg = "Please make sure all fields on the 'Call Report' page are correctly entered";
		
		if(email == "") { alert(errmsg); return false; }
		if((fromdate == "" || todate == "") && period == "empty") { alert(errmsg); return false; }
		if(period == "empty") { fromdate = fromdate.split("-"); fromdate = new Date(fromdate[2], fromdate[1] - 1, fromdate[0]);
								todate = todate.split("-"); todate = new Date(todate[2], todate[1] - 1, todate[0]); }

		if( fromdate > todate) { alert(errmsg); return false; }
		if(!type_callcount && !type_detailed) { alert(errmsg); return false; }

		return true;
	}
	
	$(document).ready(function(){
		$("#fromdate").datepicker({dateFormat: "dd-mm-yy"});
		$("#todate").datepicker({dateFormat: "dd-mm-yy"});

		$("#frompicker").click(function(){$("#fromdate").focus();});
		$("#topicker").click(function(){$("#todate").focus();});

		$("#emailaddr").val("");
		$("#extensionlist").val("");
		
		$("#fromdate").val("");
		$("#todate").val("");
		$("#period").val("empty");

		$("#type_callcount")[0].checked = false;
		$("#type_detailed")[0].checked = false;

		$("#fromdate").change(function(){ if($(this).val() != "") $("#period").val("empty"); });
		$("#todate").change(function(){ if($(this).val() != "") $("#period").val("empty"); });
		$("#period").change(function(){ if($(this).val() != "empty") $("#fromdate").val(""); $("#todate").val(""); });

		$("#type_callcount").change(function(){	if(this.checked) $("#type_detailed")[0].checked = false; });
		$("#type_detailed").change(function(){ if(this.checked) $("#type_callcount")[0].checked = false; });

		$("#load_report").click(function(){
			$("#individual_schedule").hide();
			$("#schedule_panel").show();

			$("#buttonpanel1").hide();
			$("#buttonpanel2").show();
			
			$.ajax({
	            type : 'POST',
	            url  : 'schedulemanager.php',
	            async : false,
	            data : 'action=load',
	            dataType: 'json',
	            cache: false,
	            success: function(data, textStatus, jqXHR) {
					var html = "";
					for(var i=0; i < data.length; i++)
					{
						if(data[i].extlist == null) data[i].extlist = "";
						if(data[i].fromdate == null) data[i].fromdate = ""; 
						else { 	
							var dd = data[i].fromdate.split("-"); 
							data[i].fromdate = dd[2] + "-" + dd[1] + "-" + dd[0]; 
						}
						if(data[i].todate == null) data[i].todate = "";
						else { 	
							var dd = data[i].todate.split("-"); 
							data[i].todate = dd[2] + "-" + dd[1] + "-" + dd[0]; 
						}
						if(data[i].period == null) data[i].period = "";
						
						if(i==0) 
							html += "<tr id='" + data[i].id + "'>" + 
										"<td width='5%'><input type='checkbox' /></td>" +
										"<td width='22%'>" + data[i].mail   + "</td>" +
							  			"<td width='31%'>" + data[i].extlist  + "</td>" + 
							  			"<td width='12%'>" + data[i].fromdate + "</td>" + 
							  			"<td width='12%'>" + data[i].todate + "</td>" + 
							  			"<td width='8%'>" + data[i].period + "</td>" + 
							  			"<td width='10%'>" + data[i].type + "</td></tr>";
						else
							html += "<tr id='" + data[i].id + "'>" + 
										"<td><input type='checkbox' /></td>" +
										"<td>" + data[i].mail   + "</td>" +
							  			"<td>" + data[i].extlist  + "</td>" + 
							  			"<td>" + data[i].fromdate + "</td>" + 
							  			"<td>" + data[i].todate + "</td>" + 
							  			"<td>" + data[i].period + "</td>" + 
							  			"<td>" + data[i].type + "</td></tr>";
					}
	            	$("#schedules").html(html);
	            	$("#schedules tr").click(function(){ $("#schedules tr").removeClass("selected"); $(this).addClass("selected");})
		        },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });			
		});
		
		$("#loadschedule").click(function(){
			if($("tr.selected").length == 0) {alert("Please select a schedule first."); return; }

			var tds = $("tr.selected td");

			$("#emailaddr").val($(tds[1]).html());
			$("#extensionlist").val($(tds[2]).html());
			$("#fromdate").val($(tds[3]).html());
			$("#todate").val($(tds[4]).html());
			$("#period").val($(tds[5]).html());
			if($(tds[6]).html() =="callcount")  { $("#type_callcount")[0].checked = true;  $("#type_detailed")[0].checked = false; }
			else  								{ $("#type_callcount")[0].checked = false; $("#type_detailed")[0].checked = true;  }

			$("#schedule_panel").hide();
			$("#individual_schedule").show();

			$("#buttonpanel2").hide();
			$("#buttonpanel1").show();

			$("#save_report").parent().hide();
			$("#update_report").parent().show();
			$("#update_report").attr("scheduleid", $("tr.selected").attr("id"));
		});
		
		$("#createschedule").click(function(){
			$("#schedule_panel").hide();
			$("#individual_schedule").show();

			$("#buttonpanel2").hide();
			$("#buttonpanel1").show();

			$("#update_report").parent().hide();
			$("#save_report").parent().show();
		});
		
		$("#deleteschedule").click(function(){
			var checks = $("#schedule_panel input[type='checkbox']:checked");

			var schs = new Array();
			for(var i=0; i < checks.length; i++)
			{
				schs[i] = $(checks[i]).parent().parent().attr("id");
			}

			$.ajax({
	            type : 'POST',
	            url  : 'schedulemanager.php',
	            async : false,
	            data : 'action=delete&ids=' + schs,
	            dataType: 'text',
	            cache: false,
	            success: function(data, textStatus, jqXHR) {
					if(data == "ok") alert("Schedule deleted successfully.");
					else alert("Schedule delete failed.");

					for(var i=checks.length-1; i >= 0; i--)
					{
						$(checks[i]).parent().parent().remove();
					}
					
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });

		});
		
		$("#update_report").click(function(){
			if(!validateReportSchedule()) return;

			var scheduleid = $(this).attr("scheduleid");
			var email = $("#emailaddr").val();
			var extlist = $("#extensionlist").val();
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			var period = $("#period").val();
			var type_callcount = $("#type_callcount")[0].checked;
			var type_detailed = $("#type_detailed")[0].checked;

			var param = (extlist=="" ? "" : "&extlist=" + extlist) + 
						(fromdate=="" ? "&period=" + period : "&fromdate=" + fromdate + "&todate=" + todate) + 
						(type_callcount ? "&type=callcount" : "&type=detailed");
			
			$.ajax({
	            type : 'POST',
	            url  : 'schedulemanager.php',
	            async : false,
	            data : 'action=update&id=' + scheduleid + '&mail=' + email + param,
	            dataType: 'text',
	            cache: false,
	            success: function(data, textStatus, jqXHR) {
					if(data == "ok") alert("Schedule updated successfully.");
					else alert("Schedule update failed.");
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });
		});
		
		$("#save_report").click(function(){
			if(!validateReportSchedule()) return;

			var email = $("#emailaddr").val();
			var extlist = $("#extensionlist").val();
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			var period = $("#period").val();
			var type_callcount = $("#type_callcount")[0].checked;
			var type_detailed = $("#type_detailed")[0].checked;

			var param = (extlist=="" ? "" : "&extlist=" + extlist) + 
						(fromdate=="" ? "&period=" + period : "&fromdate=" + fromdate + "&todate=" + todate) + 
						(type_callcount ? "&type=callcount" : "&type=detailed");
			
			$.ajax({
	            type : 'POST',
	            url  : 'schedulemanager.php',
	            async : false,
	            data : 'action=save&mail=' + email + param,
	            dataType: 'text',
	            cache: false,
	            success: function(data, textStatus, jqXHR) {
					if(data == "fail") alert("Schedule save failed.");
					else alert("Schedule saved successfully.");
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });
			
		});

		$("#display_report").click(function(){
			if(!validateReportSchedule()) return ;

			var email = $("#emailaddr").val();
			var extlist = $("#extensionlist").val();
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			var period = $("#period").val();
			var type_callcount = $("#type_callcount")[0].checked;
			var type_detailed = $("#type_detailed")[0].checked;

			var param = (extlist=="" ? "" : "&extlist=" + extlist) + 
						(fromdate=="" ? "&period=" + period : "&fromdate=" + fromdate + "&todate=" + todate) + 
						(type_callcount ? "&type=callcount" : "&type=detailed");
			
			$.ajax({
	            type : 'POST',
	            url  : 'schedulemanager.php',
	            async : false,
	            data : 'action=display&mail=' + email + param,
	            dataType: 'text',
	            cache: false,
	            success: function(disp_id, textStatus, jqXHR) {
					if(disp_id == "fail") {alert("Can't display."); return; }

					window.open("displayschedule.php?id=" + disp_id, "_blank");					
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });
			
		});

		$("#send_now").click(function(){
			if(!validateReportSchedule()) return;

			var email = $("#emailaddr").val();
			var extlist = $("#extensionlist").val();
			var fromdate = $("#fromdate").val();
			var todate = $("#todate").val();
			var period = $("#period").val();
			var type_callcount = $("#type_callcount")[0].checked;
			var type_detailed = $("#type_detailed")[0].checked;

			var param = (extlist=="" ? "" : "&extlist=" + extlist) + 
						(fromdate=="" ? "&period=" + period : "&fromdate=" + fromdate + "&todate=" + todate) + 
						(type_callcount ? "&type=callcount" : "&type=detailed");
			
			$.ajax({
	            type : 'POST',
	            url  : 'schedulemanager.php',
	            async : false,
	            data : 'action=sendmail&mail=' + email + param,
	            dataType: 'text',
	            cache: false,
	            success: function(disp_id, textStatus, jqXHR) {
					if(disp_id == "fail") {alert("Can't send."); return; }
					else {alert("successfully sent."); }
					//window.open("displayschedule.php?id=" + disp_id, "_blank");					
	            },
	            error : function( jqXHR, textStatus, errorThrown ){ alert(textStatus);},
	            complete: function( jqXHR, textStatus){}
	         });
			
		});
		
	});
</script>

</html>
